<?php

namespace App\Models;

use App\Exceptions\CampoInvalidoException;
use App\Exceptions\DbException;
use Error;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Symfony\Component\Process\ExecutableFinder;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;
    use Notifiable;

    protected $primarykey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'us_nome',
        'us_cpf',
        'us_email',
        'us_password',
        'us_dt_nascimento',
        'us_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function __construct()
    {
        $this->horaAtual = date("Y/m/d h:i:s");
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function buscaUsuarios()
    {
        try {
            $users = DB::table('users')
                ->get();

            $retUsers = [];
            $items = (object)[];

            foreach ($users as $user) {
                $items->nome = $user->us_nome;
                $items->cpf = $user->us_cpf;
                $items->email = $user->us_email;
                $items->password = $user->us_password;
                $items->dataNascimento = $user->us_dt_nascimento;

                array_push($retUsers, $items);
            }
            if (!$users) {
                return;
            }

            return $retUsers;
        } catch (Exception $e) {
            throw new Error("Erro ao buscar usuarios");
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function insereUsuario($dados)
    {
        try {
            if (!validaCPF($dados->cpf)) {
                return (object)["id" => 0, "mensagem" => "O campo CPF está inválido", "status" => 400];
            }

            if (!validaEmail($dados->email)) {
                return (object)["id" => 0, "mensagem" => "O campo EMAIL está inválido", "status" => 400];
            }

            $userInDb = DB::table('users')
                ->where('us_cpf', $dados->cpf)
                ->get()
                ->first();

            if ($userInDb) {
                return (object)["id" => 0, "mensagem" => "Usuário existente", "status" => 400];
            }

            $user = DB::table('users')
                ->insert([
                    'us_nome'               => $dados->nome,
                    'us_cpf'                => $dados->cpf,
                    'us_password'           => bcrypt($dados->password),
                    'us_email'              => bcrypt($dados->email),
                    'us_dt_nascimento'      => $dados->dataNascimento,
                    'us_status'             => "S"
                ]);

            if (!$user) {
                return (object)["id" => 0, "mensagem" => "Erro ao criar usuário", "status" => 404];
            }

            return (object)["id" => 1, "mensagem" => "Usuário criado com sucesso", "status" => 201];
        } catch (Exception $e) {
            throw new Error("Erro ao criar usuario");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizaUsuario($dados, $id)
    {
        try {
            if (!validaCPF($dados->cpf)) {
                return (object)["id" => 0, "mensagem" => "O campo CPF está inválido", "status" => 400];
            }

            if (!validaEmail($dados->email)) {
                return (object)["id" => 0, "mensagem" => "O campo EMAIL está inválido", "status" => 400];
            }

            $user = DB::table('users')
                ->where('id', $id)
                ->update([
                    'us_nome'               => $dados->nome,
                    'us_cpf'                => $dados->cpf,
                    'us_password'           => bcrypt($dados->password),
                    'us_email'              => bcrypt($dados->email),
                    'us_dt_nascimento'      => $dados->dataNascimento,
                    'us_status'             => "S"
                ]);

            if (!$user) {
                return (object)["id" => 0, "mensagem" => "Erro ao criar usuário", "status" => 400];
            }

            return (object)["id" => 1, "mensagem" => "Usuário atualizado com sucesso", "status" => 204];
        } catch (Exception $e) {
            throw new Error("Erro ao atualizar usuário");
        }
    }

    public function buscaUsuario($id)
    {
        try {
            $users = DB::table('users')
                ->where('id', $id)
                ->get()
                ->first();

            if (!$users) {
                return false;
            }

            $retUsers = [];
            $items = (object)[];

            $items->nome = $users->us_nome;
            $items->cpf = $users->us_cpf;
            $items->email = $users->us_email;
            $items->password = $users->us_password;
            $items->dataNascimento = $users->us_dt_nascimento;

            array_push($retUsers, $items);

            if (!$users) {
                return;
            }

            return $retUsers;
        } catch (Exception $e) {
            throw new Error("Erro na busca dos dados do usuário!");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteUsuario($id)
    {

        try {
            $user = DB::table('users')
                ->where('id', $id)
                ->update([
                    'us_status' => 'N'
                ]);

            if (!$user) {
                return false;
            }
            return response()->noContent();
        } catch (Exception $e) {
            throw new Error("Erro ao apagar usuário");
        }
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getAuthPassword()
    {
        return $this->attributes['us_password'];
    }
    public function username()
    {
        return 'us_cpf';
    }
}
