<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Tenantarea;

use Illuminate\Foundation\Http\FormRequest;
use Rinvex\Fort\Exceptions\GenericException;

class TwoFactorPhoneSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @throws \Rinvex\Fort\Exceptions\GenericException
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();

        if (! in_array('phone', config('rinvex.fort.twofactor.providers'))) {
            throw new GenericException(trans('cortex/fort::messages.verification.twofactor.phone.globaly_disabled'), route('tenantarea.account.settings'));
        }

        if (mb_strpos($this->route()->getName(), 'tenantarea.account.twofactor.phone') !== false && (! $user->phone || ! $user->phone_verified)) {
            throw new GenericException(trans('cortex/fort::messages.account.'.(! $user->phone ? 'phone_field_required' : 'phone_verification_required')), route('tenantarea.account.settings'));
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}