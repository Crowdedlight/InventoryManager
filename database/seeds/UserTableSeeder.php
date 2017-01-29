<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        DB::table('users')->insert(
            [
                [
                    'name' => 'Frederik Mazur Andersen',
                    'username' => 'crowdedlight',
                    'email' => 'mazurandersen@gmail.com',
                    'password' => bcrypt("mazur"),
                    'admin' => true
                ]
            ]
        );
    }
}
