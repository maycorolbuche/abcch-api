<?php

namespace App\Http\Controllers;

use App\Models\ContatoEmail;
use App\Models\ContatoDirecionamento;
use App\Models\ContatoObjetivo;
use App\Models\Protocolo;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class MailerController extends Controller
{
    public function index(Request $request)
    {
        $nome = $request->input('nome');
        $email = $request->input('email');
        $assunto = $request->input('assunto');
        $destinatario = $request->input('destinatario');
        $mensagem = $request->input('mensagem');
        $direcionamento = $request->input('direcionamento');
        $objetivo = $request->input('objetivo');

        $contato_email = ContatoEmail::find($destinatario);
        if (!$contato_email) {
            return response()->json(['error' => "Destinatário não localizado!"], 500);
        }
        $abrev = $contato_email->abrev;
        $dest_email = $contato_email->email;
        $dest_nome = $contato_email->nome;

        $contato_direcionamento = ContatoDirecionamento::find($direcionamento);
        if (!$contato_direcionamento) {
            return response()->json(['error' => "Direcionamento não localizado!"], 500);
        }
        $direcionamento_nome = $contato_direcionamento->nome;

        $contato_objetivo = ContatoObjetivo::find($objetivo);
        if (!$contato_objetivo) {
            return response()->json(['error' => "Objetivo não localizado!"], 500);
        }
        $objetivo_nome = $contato_objetivo->nome;


        $protocolo = Protocolo::select(DB::raw("CONCAT('$abrev', LPAD((IFNULL(MAX(CAST(MID(protocolo, 4, 7) AS SIGNED)), 0) + 1), 7, '0')) AS protocolo"))
            ->where(DB::raw('mid(protocolo,1,3)'), $abrev)
            ->value('protocolo');

        $protocolo = $protocolo . "-D" . str_pad($direcionamento, 2, "0", STR_PAD_LEFT) . "-O" . str_pad(@$objetivo, 2, "0", STR_PAD_LEFT);


        $mensagem = <<<HTML
            PROTOCOLO: <b>$protocolo</b>
            <br/>Direcionamento: <b>$direcionamento_nome</b>
            <br/>Objetivo: <b>$objetivo_nome</b>
            <br/>Remetente: <b>$nome</b>
            <br/>E-mail: <b>$email</b>
            <br/>Destinatário: <b>$dest_email</b>
            <br/>Titulo: <b>$assunto</b>
            <br/><br/>$mensagem
        HTML;
        $mensagem = trim($mensagem);

        $data = Protocolo::create([
            'origem' => 'contato',
            'protocolo' => $protocolo,
            'texto' => $mensagem,
            'id_objetivo' => $objetivo,
            'id_direcionamento' => $direcionamento,
            'id_pessoa_sistema' => 0 // $pessoa_id,
        ]);
        if (!$data) {
            return response()->json(['error' => "Erro ao gerar protocolo!"], 500);
        }


        try {
            Mail::send([], [], function (Message $msg) use ($nome, $email, $dest_nome, $dest_email, $assunto, $mensagem) {
                $msg->to($email, $nome)
                    ->subject($assunto)
                    ->html($mensagem);

                $ccs = [];
                if (!empty($dest_email)) {
                    $ccs[] = ['address' => $dest_email, 'name' => $dest_nome];
                }

                $msg->cc($ccs);

                //Log: stourage/logs
                /*
                \Illuminate\Support\Facades\Log::info('E-mail enviado', [
                    'to' => ['email' => $email, 'name' => $nome],
                    'cc' => $ccs,
                    'subject' => $assunto,
                    'mensagem_resumida' => mb_strimwidth(strip_tags($mensagem), 0, 120, '...'),
                ]);
                */
            });

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
