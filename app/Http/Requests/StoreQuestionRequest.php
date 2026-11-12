<?php

namespace App\Http\Requests;

use App\Enums\QuestionType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manageQuestions', $this->route('exam'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'text'           => ['required', 'string'],
            'type'           => ['required', Rule::enum(QuestionType::class)],
            'points'         => ['required', 'integer', 'min:1'],
            'correct_answer' => ['required_if:type,short_text', 'nullable', 'string', 'max:255'],

            'options'              => ['exclude_if:type,short_text', 'required', 'array', 'min:2'],
            'options.*.text'       => ['required', 'string', 'max:255'],
            'options.*.is_correct' => ['nullable'],
        ];
    }
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $type = $this->input('type');

                if ($type === QuestionType::ShortText->value) {
                    return;
                }

                $correct = collect($this->input('options', []))
                    ->filter(fn($option) => isset($option['is_correct']))
                    ->count();

                if ($type === QuestionType::SingleChoice->value && $correct !== 1) {
                    $validator->errors()->add('options', 'Single choice needs exactly one correct option.');
                }

                if ($type === QuestionType::MultipleChoice->value && $correct < 1) {
                    $validator->errors()->add('options', 'Multiple choice needs at least one correct option.');
                }
            },
        ];
    }
    protected function prepareForValidation(): void
    {
        $options = collect($this->input('options', []))
            ->filter(fn($option) => filled($option['text'] ?? null))
            ->values()
            ->all();

        $this->merge(['options' => $options]);
    }
}
