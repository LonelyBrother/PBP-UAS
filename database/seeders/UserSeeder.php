<?php
namespace Database\Seeders;
use App\Models\User; // tambahkan
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;  //tambahkan hash

class UserSeeder extends Seeder
{
 	public function run(): void
 	{
		$users = [
			[
				'username'=>'Fata',
				'name'=>'Akun Fata',
				'email'=>'fata@gmail.com',
				'Level'=>'user',
				'password'=>Hash::make('123456')
			],
			[
				'username'=>'Syifa',
				'name'=>'Akun Syifa',
				'email'=>'syifa@gmail.com',
				'Level'=>'user',
				'password'=>Hash::make('123456')
			],
		];

		foreach ($users as $key => $value) {
			User::create($value);
		}
	}
}