<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImRequest extends FormRequest
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
        $rules = [];
        $method = $this->route()->getActionMethod();
        switch ($method) {
            case 'bindUid':
                $rules = [
                    'client_id' => ['required'],
                ];
                break;
            case 'send':
                //{type/from/to/data/self}
                $rules = [
                    'type' => ['required','in:friend,group'],
                    'message_type'=> ['required','in:text'],

                    'to' => ['required'],
                    'data' => ['required']
                ];
                break;

        }
        return $rules;
    }
}
