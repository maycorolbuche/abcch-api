<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CavaloBhService;
use App\Helpers\PdfParts;

class AnimalController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('tipo', 1);
        $year = $request->input('ano', 0);
        $name = $request->input('nome', '');

        try {
            $result = CavaloBhService::list(name: $name, type: $type, year: $year);
            if (isset($result["message"]) && $result["message"] <> "") {
                $result["error"] = $result["message"];
            } elseif (isset($result["result"])) {
                $result["data"] = $result["result"];
                unset($result["result"]);
                foreach ($result["data"] as $key => $item) {
                    $result["data"][$key]['DtFoaledBr'] = ($item["DtFoaled"] <> null ? date("d/m/Y", strtotime($item["DtFoaled"])) : null);
                    $result["data"][$key]['NmGender'] = ($item["CdGender"] == "M" ? "MACHO" : "FÊMEA");
                }
            }
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $result = CavaloBhService::getAnimal($id);
            if (isset($result["message"]) && $result["message"] <> "") {
                $result["error"] = $result["message"];
            } elseif (isset($result["result"])) {
                $result["data"] = $result["result"];
                unset($result["result"]);

                $result["data"]['DtFoaledBr'] = ($result["data"]["DtFoaled"] <> null ? date("d/m/Y", strtotime($result["data"]["DtFoaled"])) : null);
                $result["data"]['DtDeathBr'] = ($result["data"]["DtDeath"] <> null ? date("d/m/Y", strtotime($result["data"]["DtDeath"])) : null);
                $result["data"]['DsStatus'] = ($result["data"]["DtDeath"] == null ? "Vivo" : "Morto em " . date("d/m/Y", strtotime($result["data"]["DtDeath"])));

                foreach ($result["data"]['lstProgeny'] as $key => $item) {
                    $result["data"]['lstProgeny'][$key]['DtFoaledBr'] = ($item["DtFoaled"] <> null ? date("d/m/Y", strtotime($item["DtFoaled"])) : null);
                }

                foreach ($result["data"]['lstBrothers'] as $key => $item) {
                    $result["data"]['lstBrothers'][$key]['DtFoaledBr'] = ($item["DtFoaled"] <> null ? date("d/m/Y", strtotime($item["DtFoaled"])) : null);
                }
            }


            try {
                $resultPedigree = CavaloBhService::getPedigree($id);
                if (isset($resultPedigree["message"]) && $resultPedigree["message"] <> "") {
                    $resultPedigree["error"] = $resultPedigree["message"];
                } elseif (isset($resultPedigree["result"])) {
                    $resultPedigree["resultCode"] = [];
                    foreach ($resultPedigree["result"] as $key => $item) {
                        $resultPedigree["resultCode"][$item['vlCode']] = $item;
                    }
                    $resultPedigree["data"] = $this->buildPedigree($resultPedigree["resultCode"]);
                    unset($resultPedigree["result"]);
                    unset($resultPedigree["resultCode"]);
                }

                $result['data']['lstPedigree'] = $resultPedigree['data'];
            } catch (\Exception $e) {
            }

            return response()->json($result['data']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function print($id)
    {
        $data = self::show($id);
        $data = $data->getData(true);

        $html = "";

        $html .= PdfParts::init(
            title: $data["NmAnimal"]
        );

        $html .= PdfParts::space();

        $html .= PdfParts::fields(columns: 2, data: [
            ['label' => 'Nome', 'value' => $data['NmAnimal'] ?? ''],
            ['label' => 'Registro', 'value' => $data['NrRegistration'] ?? ''],
            ['label' => 'Microchip', 'value' => $data['CdMicrochip'] ?? ''],
            ['label' => 'Nascimento', 'value' => $data['DtFoaledBr'] ?? ''],
            ['label' => 'Sexo', 'value' => $data['DsGender'] ?? ''],
            ['label' => 'Criador', 'value' => $data['NmUserBreeder'] ?? ''],
            ['label' => 'Proprietário', 'value' => $data['NmUserOwner'] ?? ''],
            ['label' => 'Status', 'value' => $data['DsStatus'] ?? ''],
            ['label' => 'Raça', 'value' => $data['DsBreed'] ?? ''],
            ['label' => 'Pelagem', 'value' => $data['DsCoatColor'] ?? ''],
            ['label' => 'DNA', 'value' => $data['CdDNALaboratory'] ?? ''],
            ['label' => 'Status do DNA', 'value' => $data['DsDNAResult'] ?? ''],
            ['label' => 'Local de Nascimento', 'value' => $data['DsFoalBirthplace'] ?? ''],
        ]);

        if (@$data['DsSiteComments'] <> "" || @$data["CdGoldMaresType"] <> "") {
            $html .= PdfParts::space(10);
            $html .= PdfParts::html("<table style='width:100%'><tr><td style='width:100%'>");
            $html .= PdfParts::text($data['DsSiteComments'] ?? "", "color: red; font-weight: bold;");
            if (@$data["CdGoldMaresType"] <> "") {
                $html .= PdfParts::html("</td><td>");
                $html .= PdfParts::image("/imgs/escarapela_" . $data['CdGoldMaresType'] . ".png");
            }
            $html .= PdfParts::html("</td></tr></table>");
        }

        $html .= PdfParts::space(50);

        $html .= PdfParts::title("GENEALOGIA");
        $html .= PdfParts::space();

        $html .= PdfParts::familyTree(
            sire: $data["lstPedigree"]["sire"],
            dam: $data["lstPedigree"]["dam"]
        );

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        return $pdf->stream('Genealogia - ' . $data["NmAnimal"] . '.pdf');
    }

    public function crossingPrint($sire, $dam)
    {
        $data = self::show($sire);
        $sire_data = $data->getData(true);

        $data = self::show($dam);
        $dam_data = $data->getData(true);

        //dd($sire_data, $dam_data);

        $html = "";

        $html .= PdfParts::init(
            title: "GENEALOGIA DO FUTURO POTRO"
        );

        $html .= PdfParts::space(40);

        $html .= "<table style='width: 100%; border-collapse: collapse;'>";
        $html .= "<tr><td>";
        $html .= "<div style='background:#9dbfe2;padding:25px 10px 25px 10px;text-align:center;border-radius:10px;'>" . $sire_data["NmAnimal"] . "</div>";
        $html .= "</td><td style='text-align: center;font-size: 40px;color: #afafaf;'>";
        $html .= "<b>X</b>";
        $html .= "</td><td>";
        $html .= "<div style='background:#f2ddee;padding:25px 10px 25px 10px;text-align:center;border-radius:10px;'>" . $dam_data["NmAnimal"] . "</div>";
        $html .= "</td></tr>";
        $html .= "</table>";


        $html .= PdfParts::space(50);

        $html .= PdfParts::title("GENEALOGIA");
        $html .= PdfParts::space();

        $html .= PdfParts::familyTree(
            sire: $sire_data["lstPedigree"],
            dam: $dam_data["lstPedigree"]
        );

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        return $pdf->stream('Cruzamento Virtual - ' . $sire_data["NmAnimal"] . ' X ' . $dam_data["NmAnimal"] . '.pdf');
    }

    public function types()
    {
        return self::listTypes();
    }

    private function listTypes()
    {
        return [
            ['id' => 1, 'text' => 'Animal'],
            ['id' => 2, 'text' => 'Garanhão'],
            ['id' => 3, 'text' => 'Égua'],
            ['id' => 4, 'text' => 'Pai'],
            ['id' => 5, 'text' => 'Mãe'],
            ['id' => 6, 'text' => 'Avô Paterno'],
            ['id' => 7, 'text' => 'Avô Materno'],
        ];
    }

    private function buildPedigree(array $flat): array
    {
        $make = function ($id) use (&$make, $flat) {
            if (!isset($flat[$id])) return null;

            $node = $flat[$id];

            $fatherId = $id . '1';
            $motherId = $id . '2';

            if (isset($flat[$fatherId])) {
                $node['sire'] = $make($fatherId);
            }

            if (isset($flat[$motherId])) {
                $node['dam'] = $make($motherId);
            }

            return $node;
        };

        return $make('1');
    }
}
