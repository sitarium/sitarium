<?php
use Illuminate\Database\Seeder;
use App\Models\User;

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
            'name' => 'admin',
	        'email' => 'admin@sitarium.fr',
	        'password' => Hash::make('sitarium'),
	        'admin' => TRUE,
        ]);
    }
}
