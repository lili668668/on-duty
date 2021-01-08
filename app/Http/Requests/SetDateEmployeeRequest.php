<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetDateEmployeeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            '*.id' => 'integer',
            '*.duty_date' => 'integer'
        ];
    }
}
