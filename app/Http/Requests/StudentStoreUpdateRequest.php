<?php

namespace App\Http\Requests;

use App\Rules\UniqueTenant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class StudentStoreUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = Route::current()->parameter("id") ?? Route::current()->parameter("student");

        return [
            "name" => ["required", "min:3", "max:255", new UniqueTenant("students", "id", $id, "Nome")],
            "email" => ["required", "email", new UniqueTenant("students", "id", $id, "Email")],
            "height" => "required|min:2|max:5",
            "weight" => "required|min:2|max:5",
            "date_birth" => "date_format:Y-m-d|before:today",
            "gender" => "in:m,f,o",
        ];
    }
}
