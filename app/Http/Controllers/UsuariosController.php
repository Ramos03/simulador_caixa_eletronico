<?php

namespace App\Http\Controllers;

use App\Models\User as Usuarios;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

date_default_timezone_set('America/Sao_Paulo');

class UsuariosController extends Controller
{

    public function create(Request $request)
    {
        $dados = (object)[
            "nome" => $request->input('nome'),
            "password" => $request->input('password'),
            "cpf" => $request->input('cpf'),
            "email" => $request->input('email'),
            "dataNascimento" => $request->input('dataNascimento')
        ];

        $user = new Usuarios();
        $retorno = $user->insereUsuario($dados);

        return response()->json(["mensagem" => $retorno->mensagem], $retorno->status);
    }

    public function buscaUsuarios(Request $request)
    {
        $usuarios = new Usuarios();
        $retUsuarios = $usuarios->buscaUsuarios();

        if (!$retUsuarios) {
            return response()->json(['mensagem' => "Sem usuários"], 404);
        }

        return response()->json($retUsuarios, 200);
    }

    public function alteraUsuario(Request $request)
    {
        $dados = (object)[
            "nome" => $request->input('nome'),
            "password" => $request->input('password'),
            "cpf" => $request->input('cpf'),
            "email" => $request->input('email'),
            "dataNascimento" => $request->input('dataNascimento')
        ];

        $id = Auth::id();

        $usuarios = new Usuarios();
        $retUsuarios = $usuarios->atualizaUsuario($dados, $id);

        if (!$retUsuarios) {
            return response()->json(['mensagem' => "Sem usuários"], 404);
        }

        if ($retUsuarios->id === 1) {
            return response()->noContent();
        }

        return response()->json(['mensagem' => $retUsuarios->mensagem], $retUsuarios->status);
    }

    public function buscaUsuario(Request $request)
    {
        $id = Auth::id();

        $usuarios = new Usuarios();
        $retUsuarios = $usuarios->buscaUsuario($id);

        if (!$retUsuarios) {
            return response()->json(['mensagem' => "Usuário não localizado"], 404);
        }

        return response()->json($retUsuarios, 200);
    }

    public function deleteUsuario(Request $request)
    {
        $id = Auth::id();

        $usuarios = new Usuarios();
        $retUsuarios = $usuarios->deleteUsuario($id);

        if (!$retUsuarios) {
            return response()->json(['mensagem' => "Usuário não localizado"], 404);
        }
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json($retUsuarios, 200);
    }
}
