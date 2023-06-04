<?php

namespace App\Http\Requests\CommentController;

use App\Models\Product;
use App\Rules\UserCommentOnProductLimit;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        $product = Product::where("name", $this->product_name)->firstOrCreate(["name" => $this->product_name]);
        $this->merge(["product" => $product]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "product_name" => ["required", "string", new UserCommentOnProductLimit($this->product)],
            "comment" => ["required", "string"],
        ];
    }
}
