<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Adminarea;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class UserFormRequest extends FormRequest
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
        $data = $this->all();

        $user = $this->route('user') ?? app('rinvex.fort.user');
        $country = $data['country_code'] ?? null;
        $twoFactor = $user->getTwoFactor();

        $data['email_verified'] = $this->get('email_verified', false);
        $data['phone_verified'] = $this->get('phone_verified', false);

        if ($user->exists && empty($data['password'])) {
            unset($data['password'], $data['password_confirmation']);
        }

        // Update email verification date
        if ($data['email_verified'] && $user->email_verified !== $data['email_verified']) {
            $data['email_verified_at'] = Carbon::now();
        }

        // Update phone verification date
        if ($data['phone_verified'] && $user->phone_verified !== $data['phone_verified']) {
            $data['phone_verified_at'] = Carbon::now();
        }

        // Set abilities
        if ($this->user()->can('grant-abilities') && $data['abilities']) {
            $data['abilities'] = $this->user()->isSuperadmin() ? $data['abilities']
                : array_intersect($this->user()->allAbilities->pluck('id')->toArray(), $data['abilities']);
        } else {
            unset($data['abilities']);
        }

        // Set roles
        if ($this->user()->can('assign-roles') && $data['roles']) {
            $data['roles'] = $this->user()->isSuperadmin() ? $data['roles']
                : array_intersect($this->user()->roles->pluck('id')->toArray(), $data['roles']);
        } else {
            unset($data['roles']);
        }

        if ($twoFactor && (isset($data['phone_verified_at']) || $country !== $user->country_code)) {
            array_set($twoFactor, 'phone.enabled', false);
            $data['two_factor'] = $twoFactor;
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
        $user = $this->route('user') ?? app('rinvex.fort.user');
        $user->updateRulesUniques();
        $rules = $user->getRules();

        // Attach attribute rules
        $user->getEntityAttributes()->each(function ($attribute, $attributeSlug) use (&$rules) {
            switch ($attribute->type) {
                case 'datetime':
                    $type = 'date';
                    break;
                case 'text':
                case 'varchar':
                    $type = 'string';
                    break;
                default:
                    $type = $attribute->type;
                    break;
            }

            $rule = ($attribute->is_required ? 'required|' : 'nullable|').$type;
            $rules[$attributeSlug.($attribute->is_collection ? '.*' : '')] = $rule;
        });

        $rules['roles'] = 'nullable|array';
        $rules['abilities'] = 'nullable|array';
        $rules['password'] = $user->exists
            ? 'confirmed|min:'.config('rinvex.fort.password_min_chars')
            : 'required|confirmed|min:'.config('rinvex.fort.password_min_chars');

        return $rules;
    }
}
