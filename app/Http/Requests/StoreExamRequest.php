<?php

namespace App\Http\Requests;

use App\Models\Exam;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreExamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manageStudents', $this->route('course'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'=>['required','string','max:255'],
            'description'=>['nullable','string',],
            'duration_minutes'=>['required','integer','min:1'],
            'opens_at'=>['required','date'],
            'closes_at'=>['required','date'],
            'pass_mark'=>['required','numeric','min:0','max:100'],
            'show_correct_answers'=>['boolean'],
            'randomize_questions'=>['boolean'],
        ];
    }
}
