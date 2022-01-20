<?php

use App\Models\Contas;
use Tests\TestCase;

class ContasTest extends TestCase
{
    /**
     * @test
     */
    public function novaConta()
    {
        $dados = (object)[
            "idUsuario" => 2,
            "tpConta" => "cc"
        ];

        $contas = new Contas();
        $retorno = $contas->novaConta($dados);

        $this->assertEquals("Conta criada com sucesso", $retorno->mensagem);
    }

    /**
     * @test
     */
    public function novaContaTpInvalido()
    {
        $dados = (object)[
            "idUsuario" => 1,
            "tpConta" => "ADSADSA"
        ];

        $contas = new Contas();
        $retorno = $contas->novaConta($dados);

        $this->assertEquals("Tipo de conta inválido", $retorno->mensagem);
    }
}
