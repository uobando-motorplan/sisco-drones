<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Test',
            'last_name' => '',
            'email' => 'test@test.com',
            'password' => bcrypt('secret'),
            'email_verified_at' => Carbon::now(),
        ]);
    }
}
