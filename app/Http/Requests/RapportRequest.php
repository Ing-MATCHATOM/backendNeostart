<?php
// app/Http/Requests/RapportRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RapportRequest extends FormRequest
{
    public function authorize()
    {
        return true; // mettre une logique si besoin
    }

    public function rules()
    {
        return [
            'date_rapport'  => 'required|date',
            'heure_debut'   => 'required|date_format:H:i',
            'heure_fin'     => 'required|date_format:H:i|after:heure_debut',
            'contenu'       => 'nullable|string|max:1000',
        ];
    }
}
