<?php

namespace App\Http\Requests;

use App\Models\PatientRecord;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class UpdatePatientRecordRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('patient_record_edit');
    }

    public function rules()
    {
        return [
            'date_processed' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'case_type' => [
                'string',
                'required',
            ],
            'control_number' => [
                'string',
                'required',
            ],
            'claimant_name' => [
                'string',
                'required',
            ],
            'case_category' => [
                'required',
            ],
            'patient_name' => [
                'string',
                'required',
            ],
            'diagnosis' => [
                'required',
            ],
            'age' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'address' => [
                'string',
                'required',
            ],
            'contact_number' => [
                'string',
                'max:14',
                'required',
            ],
            'case_worker' => [
                'string',
                'required',
            ],
        ];
    }
}