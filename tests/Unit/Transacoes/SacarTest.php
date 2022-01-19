<?php

use App\Models\Transacoes;
use Tests\TestCase;

class SacarTest extends TestCase
{
    /**
     * @test
     */
    public function sacarOk()
    {
        $dados = (object)[
            "idUsuario" => 1,
            "idConta" => 2,
            "valorSaque" => 100,
            "ipRequisicao" => '127.0.0.1'
        ];

        $contas = new Transacoes();
        $retorno = $contas->saque($dados);

        $this->assertEquals("Saque realizado com sucesso", $retorno->mensagem);
    }

    /**
     * @test
     */
    public function sacarValorIncorreto()
    {
        $dados = (object)[
            "idUsuario" => 1,
            "idConta" => 2,
            "valorSaque" => 30,
            "ipRequisicao" => '127.0.0.1'
        ];

        $contas = new Transacoes();
        $retorno = $contas->saque($dados);

        $this->assertEquals("Valor indisponível", $retorno->mensagem);
    }

    /**
     * @test
     */
    public function sacarValorMenor20()
    {
        $dados = (object)[
            "idUsuario" => 1,
            "idConta" => 2,
            "valorSaque" => 10,
            "ipRequisicao" => '127.0.0.1'
        ];

        $contas = new Transacoes();
        $retorno = $contas->saque($dados);

        $this->assertEquals("Valor indisponível", $retorno->mensagem);
    }

    /**
     * @test
     */
    public function saqueMaiorQueOSaldo()
    {
        $dados = (object)[
            "idUsuario" => 1,
            "idConta" => 2,
            "valorSaque" => 50000000000,
            "ipRequisicao" => '127.0.0.1'
        ];

        $contas = new Transacoes();
        $retorno = $contas->saque($dados);

        $this->assertEquals("Saldo insuficiente", $retorno->mensagem);
    }

    /**
     * @test
     */
    public function saque30()
    {
        $dados = (object)[
            "idUsuario" => 1,
            "idConta" => 2,
            "valorSaque" => 30,
            "ipRequisicao" => '127.0.0.1'
        ];

        $contas = new Transacoes();
        $retorno = $contas->saque($dados);

        $this->assertEquals("Valor indisponível", $retorno->mensagem);
    }
}
