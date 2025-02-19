<?php

namespace Stia\AuthidPhp;

use Stia\AuthidPhp\Contracts\TwoFactorChallengeViewResponse;

class AuthID
{
    const RECOVERY_CODES_GENERATED = 'recovery-codes-generated';
    const TWO_FACTOR_AUTHENTICATION_CONFIRMED = 'two-factor-authentication-confirmed';
    const TWO_FACTOR_AUTHENTICATION_DISABLED = 'two-factor-authentication-disabled';
    const TWO_FACTOR_AUTHENTICATION_ENABLED = 'two-factor-authentication-enabled';

    /**
     * Determine if authid is confirming two factor authentication configurations.
     *
     * @return bool
     */
    public static function confirmsTwoFactorAuthentication()
    {
        return Features::enabled(Features::twoFactorAuthentication()) &&
               Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm');
    }
}
