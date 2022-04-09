<?php

namespace App\Http\Requests;

use App\Rules\UniqueTenant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class StoreUpdateSubscriptionRequest extends FormRequest
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
        $id = Route::current()->parameter("id") ?? Route::current()->parameter("subscription");

        return [
            "student_id" => "required|exists:students,id",
            "plan_id" => "required|exists:plans,id",
            "dt_start" => ["required"],
        ];
    }
}
