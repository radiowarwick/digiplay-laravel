<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('new_groups')->insert([
			'name' => 'Admin',
		]);

		DB::table('permissions')->insert([
			'name' => 'Can edit groups',
		]);

		DB::table('permissions')->insert([
			'name' => 'Audiowall admin',
		]);

		DB::table('permissions')->insert([
			'name' => 'Can view studio logins'
		]);

		DB::table('permissions')->insert([
			'name' => 'Can view admin page'
		]);

		DB::table('permissions')->insert([
			'name' => 'Sustainer admin',
		]);

		DB::table('permissions')->insert([
			'name' => 'Can upload audio'
		]);

		DB::table('permissions')->insert([
			'name' => 'Playlist editor'
		]);
	}
}
