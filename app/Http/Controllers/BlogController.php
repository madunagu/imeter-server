<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Post;
use App\Custom\SwissKnife;
use App\DeletedPost;

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
        $post->body = $request['body'];
        $post->user_id = $request['user_id'];
        $post->views = 0;
        $post->save();

        $categories = $request['categories'];

        foreach ($categories as $category) {
            $post->categories()->attach($category);
        }

        return response(
            compact("successfully inserted post id: $post->id"),
            200
        );
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => "required",
        ]);

        $post = Post::where('id', $request['id'])->first();
        $deleted_post = new DeletedPost();
        $deleted_post->id = $post->id;
        $deleted_post->title = $post->title;
        $deleted_post->body = $post->body;
        $deleted_post->views = $post->views;
        $deleted_post->user_id = $post->user_id;
        $deleted_post->save();

        $post->delete();

        return response(
            compact("successfully deleted post id: $deleted_post->id"),
            200
        );
    }


    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            "id" => "required",
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

        $post = Post::where('id', $request['id'])->first();
        $post->title = $request['title'];
        $post->body = $request['body'];
        $post->user_id = $request['user_id'];
        $post->save();

        $categories = $request['categories'];

        foreach ($categories as $category) {
            $post->categories()->attach($category);
        }

        return response(
            "successfully updated post id: $post->id",
            200
        );


    }
}
