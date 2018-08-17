<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(AccountPoliciesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(EventTypesTableSeeder::class);
        $this->call(SystemConfigTableSeeder::class);
        $this->call(ContactsTableSeeder::class);
        $this->call(DeviceInfoTableSeeder::class);
    }
}
