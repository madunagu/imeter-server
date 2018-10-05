<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Post;
use App\Custom\SwissKnife;

class BlogController extends Controller
{
    public function list(Request $request)
    {
        $blog_posts = Post::all();
        SwissKnife::encode($blog_posts);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "title" => "required",
            "body" => "required",
            "user_id"  => "required",
        ]);

        if ($validator->fails()) {
            return response(
                $validator->errors(),
                400
            );
        }

        $post = new Post();
        $post->title = $request['title'];
        $post->post = $request['body'];
        $post->user_id = $request['user_id'];
        $post->views = 0;
        $post->save();

        $categories = $request['categories'];

        foreach ($categories as $category) {
            $post->categories()->attach($category);
        }

        return response(
            "successfully inserted post id: $post->id",
            200
        );
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => "required",
        ]);

        $post = Post::where('id', $request['id'])->first();
        $post->visible = 0;
        $post->save();
    }
}
