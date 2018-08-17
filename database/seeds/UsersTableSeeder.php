<?php

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
        \App\User::create([
            'username'=>'System','canLogin'=>false,'role_id'=>1,'isProtected'=>true,'canEdit'=>false
        ]);
        
        \App\User::create([
            'username'=>'Unknown','canLogin'=>false,'role_id'=>3,'isProtected'=>true,'canEdit'=>false
        ]);
        
        \App\User::create([
            'username'=>'Admin','password'=>\Hash::make('password'),'canLogin'=>true,'canEdit'=>false,'role_id'=>1,'isProtected'=>false
        ]);
        
        \App\User::create([
            'username'=>'Mobile','canLogin'=>false,'role_id'=>3,'isProtected'=>true,'canEdit'=>false
        ]);
        
    }
}
