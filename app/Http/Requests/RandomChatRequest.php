<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RandomChatRequest extends FormRequest
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
            case 'init':
                $rules = [
                    'group_id'=> ['required']
                ];
                break;
            case 'join':
                $rules = [
                    'random_chat_id'=> ['required']
                ];
                break;
         }
         return $rules;

    }
}
