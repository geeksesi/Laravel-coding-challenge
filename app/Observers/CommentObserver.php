<?php

namespace App\Observers;

use App\Models\Comment;
use App\Services\ProductService;

class CommentObserver
{
    public function __construct(protected ProductService $service)
    {
    }

    public function created(Comment $comment): void
    {
        $this->service->updateCommentsCountInProductComments($comment->product);
    }
}
