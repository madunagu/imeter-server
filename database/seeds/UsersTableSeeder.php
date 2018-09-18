<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $ekene = new User();
        $ekene->name = 'Ekene Madunagu';
        $ekene->email = 'ekenemadunagu@gmail.com';
        $ekene->password = bcrypt('ekene1996');
        $ekene->save();
    }
}
