<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookingRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            '*.email' => ['required', 'email'],
            '*.first_name' => ['required'],
            '*.last_name' => ['required'],
            '*.booking_date' => ['required', 'date'],
            '*.start_time' => ['required'],
            '*.end_time' => ['required'],
            '*.event_id' => ['required']
        ];
    }
}
