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

    	DB::table('group_permissions')->insert([
    		'group_id' => 1,
    		'permission_id' => 1,
    	]);
    }
}
