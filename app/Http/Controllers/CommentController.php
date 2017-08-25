<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use LRedis;

class CommentController extends Controller
{
    /**
     * store comment
     */
    public function store(Request $request) {
    	$comment = new Comment;
    	$comment->user_id = $request->user_id;
    	$comment->trip_id = $request->trip_id;
    	$comment->content = $request->content;
    	$comment->save();
        $data = Comment::where('id', $comment->id)->with('user')->get();
        $redis = LRedis::connection();
        $redis->publish('message', json_encode($data));
        return "true";
    }
}
