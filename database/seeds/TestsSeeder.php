<?php

use Illuminate\Database\Seeder;
use App\Models\Website;
use App\Models\User;

class TestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $website = Website::create([
                'host' => 'demo.localhost',
                'name' => 'Demo',
                'email' => 'demo@localhost.fr',
                'active' => true,
            ]);

            $user = User::where('email', 'admin@sitarium.fr')->firstOrFail();
            $user->websites()->save($website);
        });
    }
}
