<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(MetersTableSeeder::class);
        $this->call(EncryptionKeySeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(PostsTableSeeder::class);
        $this->call(MeterTypesTableSeeder::class);
        $this->call(TimeOfUseTableSeeder::class);
        $this->call(DiscosTableSeeder::class);
    }
}
