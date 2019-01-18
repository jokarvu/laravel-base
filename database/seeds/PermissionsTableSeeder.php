<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            'name' => 'access-admin-center',
            'description' => 'User can access admin dashboard',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('permissions')->insert([
            'name' => 'delete-permission',
            'description' => 'User can delete permission',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('permissions')->insert([
            'name' => 'create-permission',
            'description' => 'User can create new permission',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('permissions')->insert([
            'name' => 'view-permission',
            'description' => 'User can view permission',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('permissions')->insert([
            'name' => 'update-permission',
            'description' => 'User can update permission',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
