<?php

namespace App\Models;

use Error;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Contas extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'co_id_usuario',
        'co_tipo',
        'co_saldo',
        'created_at',
        'updated_at'
    ];


    public function novaConta($dados)
    {
        try {
            if ($dados->tpConta !== "cc" && $dados->tpConta !== "cp") {
                return (object)["id" => 0, "mensagem" => "Tipo de conta inválido", "status" => 404];
            }

            $contasExistente = DB::table('contas')
                ->where('co_id_usuario', $dados->idUsuario)
                ->get();

            if ($contasExistente) {
                foreach ($contasExistente as $contaExistente) {
                    if ($contaExistente->co_tipo == $dados->tpConta) {
                        return (object)["id" => 0, "mensagem" => "Já existe uma conta para esse usuário", "status" => 400];
                    }
                }
            }

            $conta = DB::table('contas')
                ->insert([
                    'co_id_usuario'         => $dados->idUsuario,
                    'co_tipo'               => $dados->tpConta,
                    'co_saldo'              => 0,
                    'co_status'             => 'S'
                ]);

            if (!$conta) {
                return (object)["id" => 0, "mensagem" => "Erro ao criar conta", "status" => 404];
            }

            return (object)["id" => 1, "mensagem" => "Conta criada com sucesso", "status" => 201];
        } catch (Exception $e) {
            throw new Error("Erro ao criar nova conta");
        }
    }

    public function deleteConta($dados)
    {
        try {
            if ($dados->tpConta !== "cc" && $dados->tpConta !== "cp") {
                return (object)["id" => 0, "mensagem" => "Tipo de conta inválido", "status" => 404];
            }

            $contasExistente = DB::table('contas')
                ->where('co_id_usuario', $dados->idUsuario)
                ->where('co_id', $dados->idConta)
                ->get();

            if (!$contasExistente) {
                return (object)["id" => 0, "mensagem" => "Sem conta para esse usuário", "status" => 400];
            }

            $conta = DB::table('contas')
                ->where('co_id', $dados->idConta)
                ->where('co_id_usuario', $dados->idUsuario)
                ->update([
                    'co_status' => 'N',
                ]);

            if (!$conta) {
                return (object)["id" => 0, "mensagem" => "Erro ao atualizar conta", "status" => 404];
            }

            return (object)["id" => 1, "mensagem" => "Conta atualizada com sucesso", "status" => 204];
        } catch (Exception $e) {
            throw new Error("Erro ao deletar a conta");
        }
    }

    public function buscarDadosConta($id)
    {
        try {
            $users = DB::table('users')
                ->where('id', $id)
                ->get()
                ->first();

            $contas = DB::table('contas')
                ->where('co_id_usuario', $id)
                ->get();

            if (!$users) {
                return false;
            }

            $retUsers = [];
            $items = (object)[];

            $items->nome = $users->us_nome;
            $items->cpf = $users->us_cpf;
            $items->email = $users->us_email;
            $items->dataNascimento = $users->us_dt_nascimento;
            $items->contas = $contas;

            array_push($retUsers, $items);

            if (!$users) {
                return;
            }

            return $retUsers;
        } catch (Exception $e) {
            throw new Error("Erro ao buscar contas");
        }
    }
}
