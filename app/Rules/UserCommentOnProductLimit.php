<?php

namespace App\Rules;

use App\Models\Comment;
use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UserCommentOnProductLimit implements ValidationRule
{
    public function __construct(protected ?Product $product)
    {
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = auth()->user();
        if (Comment::userProduct($user->id, $this->product->id)->count() >= 2) {
            $fail("you cannot submit more than 2 comment for :attribute product");
        }
    }

    public static function docs(): array
    {
        return [
            "description" => "prevent user to create more than 2 comment on a product",
        ];
    }
}
