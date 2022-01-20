<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Transacoes;
use Illuminate\Http\Request;


date_default_timezone_set('America/Sao_Paulo');

class TransacoesController extends Controller
{

    public function sacar(Request $request)
    {
        $id = Auth::id();

        $dados = (object)[
            "idUsuario" => $id,
            "idConta" => $request->input('conta'),
            "valorSaque" => $request->input('valorSaque'),
            "ipRequisicao" => $request->ip()
        ];

        $saque = new Transacoes();
        $retorno = $saque->saque($dados);
        return response()->json(["mensagem" => $retorno->mensagem], $retorno->status);

    }

    public function depositar(Request $request)
    {
        $id = Auth::id();

        $dados = (object)[
            "idUsuario" => $id,
            "idConta" => $request->input('conta'),
            "valorDeposito" => $request->input('valorDeposito'),
            "ipRequisicao" => $request->ip()
        ];

        $saque = new Transacoes();
        $retorno = $saque->deposito($dados);
        return response()->json(["mensagem" => $retorno->mensagem], $retorno->status);
    }

    public function extrato(Request $request)
    {
        $id = Auth::id();

        $dados = (object)[
            "idUsuario" => $id,
            "idConta" => $request->query('conta'),
            "ipRequisicao" => $request->ip()
        ];

        $saque = new Transacoes();
        $retorno = $saque->extrato($dados);
        return response()->json($retorno->original, 200);
    }
}
