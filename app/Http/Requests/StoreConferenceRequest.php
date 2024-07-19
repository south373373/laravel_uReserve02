<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConferenceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //追記
            // 'event_name' => ['required','max:50'],
            // 'information' => ['required','max:200'],
            // 'event_date' => ['required','date'],
            // 'start_time' => ['required'],
            // 'end_time' => ['required','after:start_time'],
            // 'max_people' => ['required','numeric','between:1,20'],
            // 'is_visible' => ['required','boolean'],
            //validationを定義
            'event_name' => ['required', 'max:50'],
            'information' => ['required', 'max:200'],
            'event_date' => ['required', 'date'],
            'start_time' => ['required'],
            // 開始時間より後の時間を指定
            'end_time' => ['required', 'after:start_time'],
            'max_people' => ['required', 'numeric', 'between:1,20'],
            'is_visible' => ['required', 'boolean'],
        ];
    }
}
