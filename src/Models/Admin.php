<?php

declare(strict_types=1);

namespace Cortex\Auth\Models;

use Cortex\Auth\Notifications\PhoneVerificationNotification;
use Cortex\Auth\Notifications\AdminPasswordResetNotification;
use Cortex\Auth\Notifications\AdminEmailVerificationNotification;

class Admin extends User
{
    /**
     * {@inheritdoc}
     */
    protected $passwordResetNotificationClass = AdminPasswordResetNotification::class;

    /**
     * {@inheritdoc}
     */
    protected $emailVerificationNotificationClass = AdminEmailVerificationNotification::class;

    /**
     * {@inheritdoc}
     */
    protected $phoneVerificationNotificationClass = PhoneVerificationNotification::class;

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('cortex.auth.tables.admins'));
        $this->setRules([
            'username' => 'required|alpha_dash|min:3|max:150|unique:'.config('cortex.auth.tables.admins').',username',
            'password' => 'sometimes|required|min:'.config('cortex.auth.password_min_chars'),
            'two_factor' => 'nullable|array',
            'email' => 'required|email|min:3|max:150|unique:'.config('cortex.auth.tables.admins').',email',
            'email_verified' => 'sometimes|boolean',
            'email_verified_at' => 'nullable|date',
            'phone' => 'nullable|phone:AUTO',
            'phone_verified' => 'sometimes|boolean',
            'phone_verified_at' => 'nullable|date',
            'full_name' => 'required|string|max:150',
            'title' => 'nullable|string|max:150',
            'country_code' => 'nullable|alpha|size:2|country',
            'language_code' => 'nullable|alpha|size:2|language',
            'birthday' => 'nullable|date_format:Y-m-d',
            'gender' => 'nullable|string|in:male,female',
            'is_active' => 'sometimes|boolean',
            'last_activity' => 'nullable|date',
            'tags' => 'nullable|array',
        ]);
    }
}
