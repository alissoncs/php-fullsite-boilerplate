<?php

use Phinx\Seed\AbstractSeed;
use App\Util\Seeder;
use App\Model\Usuario;

class AdminUsers extends Seeder
{
    public function run()
    {

      $email = 'admin@unimedvs.com.br';
      $usuario = Usuario::where([
        'email' => $email
      ])->first();

      if ($usuario) {
        throw new \Exception('Usuário ' . $email . ' já existe');
      }

      $usuario = new Usuario();
      $usuario->email = $email;
      $usuario->login = 'admin';
      $usuario->ativo = 1;
      $usuario->senha = password_hash('admin@321', PASSWORD_DEFAULT);
      $usuario->save();

    }
}
