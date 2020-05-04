<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Varpath;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $user = User::create([
			'username'	=>	'admin',
			'password'	=>	Hash::make('admin'),
			'name'	=>	'Administrator',
			'type'	=>	'admin',
			'email'	=>	'admin@admin.com',
		]);

        $var = Varpath::create([
            'name'  =>  'system_title',
            'value'  =>  'Barangay Record Information System',
        ]);
    }
}
