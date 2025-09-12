<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EleveRequest extends FormRequest
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
        return [
            'nom_famille'=>"required",
            'prenom'=>"required",	
            'courriel'=>"required|unique:eleves",
            'niveau_id'=>"required"	

        ];


    }

    public function messages()
    {
        return[
            'courriel.unique'=>"Le courriel est déja utilisé"
        ];
    }
}
