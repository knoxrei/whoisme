<?php

namespace App\Http\Requests;

use App\Enum\Visibility;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use function Laravel\Prompts\warning;

class PastebinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (auth()->check() && auth()->user()->identification->role === \App\Enum\Role::BANNED) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:65535',
            'description' => 'nullable|string|max:255',
            'cover_path' => 'nullable|image',
            'image' => 'nullable|array|max:5',
            'image.*' => 'nullable',
            'is_self_destruct' => 'nullable|boolean',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:image_pastebins,id',
        ];

        // Check if user has permission for password
        if ($this->has('password') && $this->filled('password')) {
            if (!auth()->check() || !auth()->user()->canUsePremiumFeatures()) {
                $rules['password'] = 'prohibited';
            } else {
                $rules['password'] = 'nullable|string|min:8';
            }
        }

        // Check if user has permission for visibility
        if ($this->has('visibility') && $this->filled('visibility') && $this->visibility !== 'public') {
            if (!auth()->check() || !auth()->user()->canUsePremiumFeatures()) {
                $rules['visibility'] = 'prohibited';
            } else {
                $rules['visibility'] = [Rule::enum(Visibility::class), 'nullable'];
            }
        } else {
            $rules['visibility'] = [Rule::enum(Visibility::class), 'nullable'];
        }

        return $rules;
    }
}
