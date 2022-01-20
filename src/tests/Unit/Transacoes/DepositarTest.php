<?php

use App\Models\Transacoes;
use Tests\TestCase;

class DepositarTest extends TestCase
{
    /**
     * @test
     */
    public function depositarOk()
    {
        $dados = (object)[
            "idUsuario" => 1,
            "idConta" => 2,
            "valorDeposito" => 100,
            "ipRequisicao" => '127.0.0.1'
        ];

        $contas = new Transacoes();
        $retorno = $contas->deposito($dados);

        $this->assertEquals("Deposito efetuado com sucesso", $retorno->mensagem);
    }

    /**
     * @test
     */
    public function depositarErro()
    {
        $dados = (object)[
            "idUsuario" => 1,
            "idConta" => 2,
            "valorDeposito" => 13012.11,
            "ipRequisicao" => '127.0.0.1'
        ];

        $contas = new Transacoes();
        $retorno = $contas->deposito($dados);

        $this->assertEquals("Valor não suportado", $retorno->mensagem);
    }

    /**
     * @test
     */
    public function depositarSemConta()
    {
        $dados = (object)[
            "idUsuario" => 1,
            "idConta" => -4,
            "valorDeposito" => 13012.11,
            "ipRequisicao" => '127.0.0.1'
        ];

        $contas = new Transacoes();
        $retorno = $contas->deposito($dados);

        $this->assertEquals("Conta não localizada", $retorno->mensagem);
    }

    /**
     * @test
     */
    public function depositarContaInvalida()
    {
        $dados = (object)[
            "idUsuario" => 1,
            "idConta" => "adadsa",
            "valorDeposito" => 13012.11,
            "ipRequisicao" => '127.0.0.1'
        ];

        $contas = new Transacoes();
        $retorno = $contas->deposito($dados);

        $this->assertEquals("Campo conta inválido", $retorno->mensagem);
    }

    /**
     * @test
     */
    public function depositarCampoNaoSuportado()
    {
        $dados = (object)[
            "idUsuario" => 1,
            "idConta" => "adadsa",
            "valorDeposito" => 13012.11,
            "ipRequisicao" => '127.0.0.1'
        ];

        $contas = new Transacoes();
        $retorno = $contas->deposito($dados);

        $this->assertEquals("Campo conta inválido", $retorno->mensagem);
    }
}
