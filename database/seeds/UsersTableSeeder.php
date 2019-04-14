<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use TCG\Voyager\Models\Role;
use TCG\Voyager\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        if (User::count() == 0) {

            $role = Role::where('name', 'admin')->firstOrFail();
            factory(User::class)->create([
                'name'           => 'Administrador',
                'username'       => 'admin',
                'email'          => 'admin@email.com',
                'password'       => bcrypt('admin'),
                'remember_token' => Str::random(60),
                'role_id'        => $role->id,
            ]);

            factory(User::class)->create([
                'name'           => 'User One',
                'username'       => 'user',
                'email'          => 'user@email.com',
                'password'       => bcrypt('user'),
            ]);
        }
    }
}