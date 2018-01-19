<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Adminarea;

use Illuminate\Foundation\Http\FormRequest;

class AbilityFormRequest extends FormRequest
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
    protected function prepareForValidation(): void
    {
        $data = $this->all();

        // Set roles
        if ($this->user()->can('assign-roles') && $data['roles']) {
            $data['roles'] = array_map('intval', $data['roles']);
            $data['roles'] = $this->user()->isSuperadmin() ? $data['roles']
                : array_intersect($this->user()->roles->pluck('id')->toArray(), $data['roles']);
        } else {
            unset($data['roles']);
        }

        $this->replace($data);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = $this->route('ability') ?? app('rinvex.fort.ability');
        $user->updateRulesUniques();
        $rules = $user->getRules();
        $rules['roles'] = 'nullable|array';

        return $rules;
    }
}
