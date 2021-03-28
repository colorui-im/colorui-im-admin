<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
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
            case 'image':
            case 'file':
            case 'fileAudio':
                $rules = [
                    'file' => ['required:file'],
                ];
                break;
            case 'audio':
                $rules = [
                    'audio' => ['required','string'],
                ];
                break;
        }
        return $rules;
    }
}
