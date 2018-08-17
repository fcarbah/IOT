<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Roles::create([
            'name'=>'Admin'
        ]);
        
        \App\Models\Roles::create([
            'name'=>'Power User'
        ]);
        
        \App\Models\Roles::create([
            'name'=>'User'
        ]);
    }
}
