<?php

use Illuminate\Database\Seeder;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Models\Contacts::create([
            'name'=>'Police',
            'phone'=>'911',
            'canDelete'=>false,
            'canEdit'=>false,
            'type'=> ContactTypes::Emergency,
            'alertTypes'=>json_encode([AlertTypes::Call])
        ]);
    }
}
