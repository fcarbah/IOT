<?php

use Illuminate\Database\Seeder;

class AccountPoliciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\AccountPolicy::create([
            'name'=>'Admin','role_id'=>1,'failedLoginAttempts'=>3,'lockoutDuration'=>5,'threshold'=>2,'reset'=>20,'createdBy'=>1,'updatedBy'=>1
        ]);
        
        \App\Models\AccountPolicy::create([
            'name'=>'Power Users','role_id'=>2,'failedLoginAttempts'=>3,'lockoutDuration'=>5,'threshold'=>2,'reset'=>15,'createdBy'=>1,'updatedBy'=>1
        ]);
        
        \App\Models\AccountPolicy::create([
            'name'=>'Users','role_id'=>3,'failedLoginAttempts'=>3,'lockoutDuration'=>5,'threshold'=>2,'reset'=>10,'createdBy'=>1,'updatedBy'=>1
        ]);
    }
}
