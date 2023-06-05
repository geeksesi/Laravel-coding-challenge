<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentController\StoreCommentRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request)
    {
        $data = $request->validated();
        $data["user_id"] = auth()->user()->id;

        $request->product->comments()->create($data);
        return response("", 201);
    }
}
