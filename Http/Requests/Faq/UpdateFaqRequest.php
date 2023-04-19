<?php

namespace Modules\Faq\Http\Requests\Faq;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFaqRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'position' => $this->position ?: 0,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'faq_category_id' => ['required', 'exists:faq_categories,id'],
            'position'        => ['required', 'numeric'],
            'subject'         => ['required'],
            'content'         => ['required'],
        ];
    }
}
