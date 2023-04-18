<?php

namespace Modules\Faq\Http\Requests\FaqCategory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFaqCategoryRequest extends FormRequest
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
            'position' => ['required', 'numeric'],
            'subject' => ['required'],
            'image_head' => ['sometimes', 'image'],
            'image_tail' => ['sometimes', 'image'],
            'image_head_delete' => ['sometimes', 'boolean'],
            'image_tail_delete' => ['sometimes', 'boolean'],
            'head_html' => [],
            'tail_html' => [],
            'mobile_head_html' => [],
            'mobile_tail_html' => [],
            
        ];
    }
}
