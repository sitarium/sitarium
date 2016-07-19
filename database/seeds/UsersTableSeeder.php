<?php

<<<<<<< HEAD
use Illuminate\Database\Seeder;
=======
>>>>>>> refs/remotes/sitarium-master/analysis-8jl2wy
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate([
<<<<<<< HEAD
            'name' => 'admin',
            'email' => 'admin@sitarium.fr',
            'password' => Hash::make('sitarium'),
            'admin' => true,
=======
            'name'     => 'admin',
            'email'    => 'admin@sitarium.fr',
            'password' => Hash::make('sitarium'),
            'admin'    => true,
>>>>>>> refs/remotes/sitarium-master/analysis-8jl2wy
        ]);
    }
}
