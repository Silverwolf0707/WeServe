<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Response;

class UpdateDocumentRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('documents_management');
    }

    public function rules()
    {
        return [
            'patient_id' => ['required', 'exists:patient_records,id'],
            'file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,docx'],
            'document_type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
