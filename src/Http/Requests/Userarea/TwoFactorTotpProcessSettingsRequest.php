<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Userarea;

class TwoFactorTotpProcessSettingsRequest extends TwoFactorTotpSettingsRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return ['token' => 'required|integer'];
    }
}
