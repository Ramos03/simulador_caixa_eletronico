# Simulador De Caixa Eletronico

### 💻 Sobre o projeto

O projeto sobre o simulador de caixa eletronico consiste em um sistema para cadastro de usuários, autenticação, operações de saque, deposito e extrato.

Para operação de saque tem notas de 20, 50 e 100, onde o sistema irá devolver do maior para o menor. Ex: Saque de 150 reais, devolverá 1 nota de 100, 1 nota de 50.

Para operação de saque e depósito, só será aceito valores inteiros.

Para criação de contas, é possível um usuário possuir uma conta corrente ativa e uma conta poupança ativa.

Para todas operações será necessário informar o token de autenticação via bearer token, para pode executar qualquer ação.


### Pré-requisitos

Antes de começar, é preciso ter instalado em sua máquina as seguintes ferramentas:
[Git](https://git-scm.com), [DOCKER](https://hub.docker.com/_/mysql) e [INSOMNIA](https://insomnia.rest/download).

Além disto é bom ter um editor para trabalhar com o código como [VSCode](https://code.visualstudio.com/)

### Autenticação
Ao se autenticar, será gerado um JWT, será necessário repassar esse token no bearer de cada requisição diferente de auth e criação de usuário.

### Para consumo das Endpoints do método GET não é necessário passar nenhum parametro no body, pois tudo é vinculado ao ID do usuário autenticado através do JWT.

### Exemplo de consumo da Endpoint /api/usuarios (POST) (Cadastro de usuários)
```bash
{
	"nome": "XXXXXXX",
	"usuario": "XXXXXXXX",
	"password": "XXXXXXXXX",
	"cpf": "XXX.XXX.XXX-XX",
	"email": "XXXXXXXXX@XXXXXX.XXX",
	"dataNascimento": "XXXX-XX-XX"
}
```

### Exemplo de consumo da Endpoint /api/auth (POST) (Autenticação)
```bash
{
	"cpf": "XXX.XXX.XXX-XX",
	"password": "XXXXXXX"
}
```

### Exemplo de consumo da Endpoint /api/usuario (PUT) (Atualização dados do usuário)
```bash
{
	"nome": "XXXXXXX",
	"usuario": "XXXXXXXX",
	"password": "XXXXXXXXX",
	"cpf": "XXX.XXX.XXX-XX",
	"email": "XXXXXXXXX@XXXXXX.XXX",
	"dataNascimento": "XXXX-XX-XX"
}
```

### Exemplo de consumo da Endpoint /api/conta (POST) (Criar nova conta)
```bash
{
	"tpConta": "cc"
}
```

### Exemplo de consumo da Endpoint /api/transacao/saque (PUT) (Realizar novo saque)
```bash
{
	"conta": 1,
	"valorSaque": "3adada"
}
```

### Exemplo de consumo da Endpoint /api/transacao/deposito (PUT) (Realizar novo deposito)
```bash
{
	"conta": 1,
	"valorDeposito": 5000
}
```

### Exemplo de consumo da Endpoint /api/transacao/extrato (GET) (Extrato)
```bash
	http://127.0.0.1:8000/api/transacao/extrato?conta=1
```

### 🎲 Rodando a aplicação

O sistema está configurado com o docker-compose, assim que executar iniciará a aplicação e o banco (MariaDB).

```bash
# Clone este repositório
$ git clone https://github.com/Ramos03/simulador_caixa_eletronico

# Execução do docker compose
$ docker-compose up

# Para execução dos testes
$ php artisan test

# O servidor inciará na porta:8000 - acesse http://localhost:8000 
```