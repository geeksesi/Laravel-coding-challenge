<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentController\StoreCommentRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Create Comment
     * add a comment to a product. each user has limit to maximum 2 comment on each product
     *
     * @response 201
     *
     * @group Comment
     * @authenticated
     */
    public function store(StoreCommentRequest $request)
    {
        $data = $request->validated();
        $data["user_id"] = auth()->user()->id;

        $request->product->comments()->create($data);
        return response("", 201);
    }
}
