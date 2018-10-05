<?php

use Illuminate\Database\Seeder;
use App\Category;
use App\Post;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $power_cat = Category::where('name','Power Updates')->first();
        $green_cat = Category::where('name','Green Energy')->first();

        $post1 = new Post();
        $post1->title = "My First Blogpost";
        $post1->body = "This is a test body";
        $post1->views = 0;
        $post1->user_id = 1;
        $post1->save();

        $post1->categories()->attach($power_cat);
        $post1->categories()->attach($green_cat);


    }
}
