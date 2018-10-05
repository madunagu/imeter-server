<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $cat1 = new Category();
      $cat1->name = "Power Updates";
      $cat1->save();

      $cat2 = new Category();
      $cat2->name = "Power Outages";
      $cat2->save();

      $cat3 = new Category();
      $cat3->name = "Energy Management";
      $cat3->save();

      $cat4 = new Category();
      $cat4->name = "Green Energy";
      $cat4->save();
        //
    }
}
