<?php

namespace App\Helpers;

use App\Models\PdfParts as LabelModel;

class PdfParts
{
    public static function init($title = "")
    {
        $html = "";
        $html .= <<<HTML
            <style>
                @page {
                    margin: 17mm 10mm 15mm 10mm;
                }
                html, body, table, td, th {
                    font-size: 12px;
                    line-height: 1.5;
                    font-family: Arial, Helvetica, sans-serif !important;
                }

                h1, h2, h3, h4, h5, h6 {
                    margin: 0;
                    padding: 0;
                }

                .header {
                    border-bottom: 3px solid #CCC;
                    position: fixed;
                    top: -10mm;
                    left: 0;
                    right: 0;
                }

                .footer {
                    position: fixed;
                    bottom: -10mm;
                    left: 0;
                    right: 0;
                    border-top: 1px solid #000;
                    padding-top: 5px;
                    font-size: 10px;
                    font-style: italic;
                }
                .footer .pagenum:before {
                    content: counter(page);
                }
            </style>
        HTML;

        if ($title) {
            $html .= <<<HTML
                <div class="header">
                    <h1>$title</h1>
                </div>
            HTML;
        }

        $html .= '<div class="footer">';
        $html .= "<table style='width:100%;'>";
        $html .= "<tr>";
        $html .= "<td style='width:35%; text-align:left;'>";
        $html .= "Emissão: " . date("Y/m/d H:i:s");
        $html .= "</td>";
        $html .= "<td style='width:30%; text-align:center;'>";
        $html .= "- <span class='pagenum'></span> -";
        $html .= "</td>";
        $html .= "<td style='width:35%; text-align:right;'>";
        $html .= "NTSoft - IT Solutions";
        $html .= "</td>";
        $html .= "</tr>";
        $html .= "</table>";
        $html .= "</div>";

        return $html;
    }

    public static function title($title)
    {
        return <<<HTML
            <div style="text-align: center;">
                <h2>$title</h2>
            </div>
        HTML;
    }

    public static function text($text, $css = "")
    {
        return "<div style='$css'>$text</div>";
    }

    public static function html($htmlContent)
    {
        return $htmlContent;
    }

    public static function image($src)
    {
        return "<img src='" . $_SERVER['DOCUMENT_ROOT'] . "$src'/>";
    }

    public static function space($height = 10)
    {
        return "<div style='height: " . $height . "px;'></div>";
    }

    public static function fields($data, $columns = 1)
    {
        $html = '';
        $rows = ceil(count($data) / $columns);
        $table = [];

        for ($col = 0; $col < $columns; $col++) {
            for ($row = 0; $row < $rows; $row++) {
                $index = $col * $rows + $row;
                if (isset($data[$index])) {
                    $table[$row][$col] = $data[$index];
                } else {
                    $table[$row][$col] = null;
                }
            }
        }

        $html .= '<table style="width:100%">';
        foreach ($table as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                if ($cell) {
                    $html .= '<td style="vertical-align:top; padding-right:15px;"><b>' . $cell["label"] . ':</b> ' . $cell["value"] . '</td>';
                } else {
                    $html .= '<td></td>';
                }
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
        return $html;
    }

    public static function familyTree($sire, $dam, $level = 1, $maxLevel = 4)
    {
        $html = "";

        $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/imgs/family_tree.jpg';
        $imageData = '';
        if (file_exists($imagePath)) {
            $imageData = base64_encode(file_get_contents($imagePath));
        }

        if ($level == 1) {
            $html .= "<div style='width:100%;height:600px;position:relative;padding-top:28px;padding-left:5px;'>";
            $html .= "<img src='data:image/jpeg;base64,{$imageData}' style='position:absolute;top:0;left:0;z-index:-1;'>";
        }


        for ($i = 0; $i <= 1; $i++) {

            switch ($level) {
                case 1:
                    $height = 210;
                    $gapX = 40;
                    $gapY = 40;
                    break;
                case 2:
                    $height = 100;
                    $gapX = 11;
                    $gapY = 25;
                    break;
                case 3:
                    $height = 45;
                    $gapX = 11;
                    $gapY = 25;
                    break;
                case 4:
                    $height = 20;
                    $gapX = 11;
                    $gapY = 40;
                    break;
            }

            if ($i == 0) {
                $animal = $sire;
            } else {
                $animal = $dam;

                $html .= "<div style='height:" . $gapX . "px'></div>";
            }

            $html .= "<table style='width:100%;height:" . $height . "px;' cellspacing='0' cellpadding='0'>";
            $html .= "<tr style='width:100%;'>";

            $html .= "<td style='width:155px;height:" . $height . ";text-align:center;font-size:11px;'>";
            if ($i == 0) {
                $html .= ($animal["nmAnimal"] ?? "");
            } else {
                $html .= ($animal["nmAnimal"] ?? "");
            }
            if (@$animal["cdGoldMaresType"] <> "") {
                $html .= "<div style='position:absolute;bottom:0;'>";
                $html .= "<img src='" . $_SERVER['DOCUMENT_ROOT'] . "/imgs/escarapela_" . $animal['cdGoldMaresType'] . ".png' style='width:" . (50 / (($level + 1) * .5)) . "px;'/>";
                $html .= "</div>";
            }
            $html .=  "</td>";


            $html .= "<td style='height:" . $height . ";'>";
            if ($level < $maxLevel) {
                $html .= "<div style='width:100%;height:" . $height . ";margin-left:" . $gapY . "px;'>";
                $html .= self::familyTree(
                    sire: $animal["sire"] ?? null,
                    dam: $animal["dam"] ?? null,
                    level: $level + 1,
                    maxLevel: $maxLevel
                );
                $html .= "</div>";
            }
            $html .=  "</td>";

            $html .=  "</tr>";
            $html .=  "</table>";
        }



        if ($level == 1) {
            $html .= "</div>";
        }

        return $html;
    }
}
