<?php

use App\Models\User;
use Tests\TestCase;

class UsuariosTest extends TestCase
{
    /**
     * @test
     */
    public function criarUsuarioOk()
    {
        $dados = (object)[
            "nome" => "TEST",
            "password" => "12344",
            "cpf" => "076.567.700-89",
            "email" => "teste.1@teste.com.br",
            "dataNascimento" => "1998-04-01"
        ];

        $usuarios = new User();
        $retorno = $usuarios->insereUsuario($dados);

        $this->assertEquals("Usuário criado com sucesso", $retorno->mensagem);
    }

    /**
     * @test
     */
    public function criarUsuarioExistente()
    {
        $dados = (object)[
            "nome" => "TEST",
            "password" => "12344",
            "cpf" => "097.337.580-94",
            "email" => "teste.1@teste.com.br",
            "dataNascimento" => "1998-04-01"
        ];

        $usuarios = new User();
        $retorno = $usuarios->insereUsuario($dados);

        $this->assertEquals("Usuário existente", $retorno->mensagem);
    }

    /**
     * @test
     */
    public function criarUsuarioErroCPF()
    {
        $dados = (object)[
            "nome" => "TESTE",
            "password" => "12344",
            "cpf" => "1241411442",
            "email" => "teste.1@teste.com.br",
            "dataNascimento" => "1998-04-01"
        ];

        $usuarios = new User();
        $retorno = $usuarios->insereUsuario($dados);

        $this->assertEquals("O campo CPF está inválido", $retorno->mensagem);
    }
}
