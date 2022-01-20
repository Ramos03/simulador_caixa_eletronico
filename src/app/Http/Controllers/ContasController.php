<?php

namespace App\Http\Controllers;

use App\Models\Contas;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContasController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function novaConta(Request $request)
    {

        $dados = (object)[
            "idUsuario" => Auth::id(),
            "tpConta" => $request->input('tpConta'),
        ];

        $conta = new Contas();
        $retContas = $conta->novaConta($dados);

        return response()->json(["mensagem" => $retContas->mensagem], $retContas->status);
    }

    public function deletarConta(Request $request)
    {
        $dados = (object)[
            "idUsuario" => Auth::id(),
            "idConta" => $request->input('idConta'),
        ];

        $conta = new Contas();
        $retContas = $conta->deleteConta($dados);

        return response()->json(["mensagem" => $retContas->mensagem], $retContas->status);
    }

    public function buscaContas(Request $request)
    {

        $id = Auth::id();
        $usuarios = new Contas();
        $retContas = $usuarios->buscarDadosConta($id);

        if (!$retContas) {
            return response()->json(['mensagem' => "Sem usuários"], 404);
        }

        return response()->json($retContas, 200);
    }
}
