<?php

namespace App\Models;

use Error;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transacoes extends Model
{
    use HasFactory;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function saque($dados)
    {

        try {
            $notasDisponiveis = array(100, 50, 20);

            $dadosContas = DB::table('contas')
                ->where('co_id_usuario', $dados->idUsuario)
                ->where('co_id', $dados->idConta)
                ->get()
                ->first();

            if (!$dadosContas) {
                return (object)["id" => 0, "mensagem" => "Conta não localizada", "status" => 404];
            }

            if ((empty($dados->idConta) || !(is_int($dados->idConta)))) {
                return (object)["id" => 0, "mensagem" => "Valor não suportado", "status" => 400];
            }
            if (is_int($dados->valorSaque)) {

                if ($dados->valorSaque < 20) {
                    return (object)["id" => 0, "mensagem" => "Valor indisponível", "status" => 404];
                }

                if ($dados->valorSaque > $dadosContas->co_saldo) {
                    return (object)["id" => 0, "mensagem" => "Saldo insuficiente", "status" => 404];
                }

                if (!($dados->valorSaque % 10 == 0) || !($dados->valorSaque % 20 == 0)) {
                    return (object)["id" => 0, "mensagem" => "Valor indisponível", "status" => 404];
                }

                $valorASacar = $dadosContas->co_saldo - $dados->valorSaque;

                DB::beginTransaction();

                $retornoSaque = DB::table('contas')
                    ->where('co_id_usuario', $dados->idUsuario)
                    ->where('co_id', $dados->idConta)
                    ->update([
                        'co_saldo' => $valorASacar,
                        'co_updated_at' => date('Y-m-d H:i:s')
                    ]);

                $registroOperacao = DB::table('transacoes')
                    ->insert([
                        'tr_id_usuario' => $dados->idUsuario,
                        'tr_id_conta' => $dados->idConta,
                        'tr_tipo_operacao' => 'SAQUE',
                        'tr_detalhes' => '{"SAQUE": "' . $dados->valorSaque . '"}',
                        'tr_ip' => $dados->ipRequisicao
                    ]);

                if (!$retornoSaque && !$registroOperacao) {
                    DB::rollBack();
                    return (object)["id" => 0, "mensagem" => "Erro ao efetuar o saque", "status" => 503];
                }

                DB::commit();

                $notas = array();

                foreach ($notasDisponiveis as $nota) {

                    while ($nota <= $dados->valorSaque) {
                        array_push($notas, $nota);
                        $dados->valorSaque -= $nota;
                    }
                }
                return (object)["id" => 1, "mensagem" => "Saque realizado com sucesso", "notas" => $notas, "status" => 200];
            }
            return (object)["id" => 0, "mensagem" => "Valor inválido", "status" => 404];
        } catch (Exception $e) {
            throw new Error("Erro ao efetuar o saque");
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deposito($dados)
    {
        try {
            if ((empty($dados->idConta) || !(is_int($dados->idConta)))) {
                return (object)["id" => 0, "mensagem" => "Campo conta inválido", "status" => 400];
            }

            $contas = DB::table('contas')
                ->where('co_id_usuario', $dados->idUsuario)
                ->where('co_id', $dados->idConta)
                ->get()
                ->first();

            if (!$contas) {
                return (object)["id" => 0, "mensagem" => "Conta não localizada", "status" => 404];
            }

            if (is_int($dados->valorDeposito)) {

                $valorADepositar = $contas->co_saldo + $dados->valorDeposito;

                DB::beginTransaction();

                $retornoDeposito = DB::table('contas')
                    ->where('co_id_usuario', $dados->idUsuario)
                    ->where('co_id', $dados->idConta)
                    ->update([
                        'co_saldo' => $valorADepositar,
                        'co_updated_at' => date('Y-m-d H:i:s')
                    ]);

                $registroOperacao = DB::table('transacoes')
                    ->insert([
                        'tr_id_usuario' => $dados->idUsuario,
                        'tr_id_conta' => $dados->idConta,
                        'tr_tipo_operacao' => 'DEPOSITO',
                        'tr_detalhes' => '{"DEPOSITO": "' . $dados->valorDeposito . '"}',
                        'tr_ip' => $dados->ipRequisicao
                    ]);

                if (!$retornoDeposito && !$registroOperacao) {
                    DB::rollBack();
                    return (object)["id" => 0, "mensagem" => "Erro ao efetuar depósito", "status" => 503];
                }

                DB::commit();

                return (object)["id" => 1, "mensagem" => "Deposito efetuado com sucesso", "status" => 201];
            }

            return (object)["id" => 0, "mensagem" => "Valor não suportado", "status" => 400];
        } catch (Exception $e) {
            throw new Error("Erro ao realizar depósito");
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function extrato($dados)
    {
        try {

            $extrato = DB::table('contas')
                ->join('users', 'users.id', '=', 'co_id_usuario')
                ->select('contas.co_id as idConta', 'co_tipo', 'co_saldo', 'us_nome', 'us_cpf')
                ->where('co_id_usuario', $dados->idUsuario)
                ->where('contas.co_id', $dados->idConta)
                ->get()
                ->first();

            if (!$extrato) {
                return response()->json(['mensagem' => "Conta não localizada"], 400);
            }

            $dadosTransacoes = DB::table('transacoes')
                ->select('tr_tipo_operacao as operacao', 'tr_detalhes as detalhes', 'tr_created_at as dataDaTransacao')
                ->where('tr_id_conta', $dados->idConta)
                ->get();

            if (!$dadosTransacoes) {
                return response()->json(['mensagem' => "Transações não localizada"], 400);
            }

            $makeTpConta = $extrato->co_tipo == 'cc' ? 'Conta Corrente' : 'Conta Poupança';

            $extrato = [
                "nome" => $extrato->us_nome,
                "cpf" => $extrato->us_cpf,
                "tpConta" => $makeTpConta,
                "saldo" => $extrato->co_saldo,
                "transacoes" => $dadosTransacoes
            ];

            return response()->json($extrato);
        } catch (Exception $e) {
            throw new Error("Erro na geracao do extrato da conta: ");
        }
    }
}
