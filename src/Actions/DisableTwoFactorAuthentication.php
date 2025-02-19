<?php

namespace Stia\AuthidPhp\Actions;

use Stia\AuthidPhp\Events\TwoFactorAuthenticationDisabled;
use Stia\AuthidPhp\AuthID;

class DisableTwoFactorAuthentication
{
    /**
     * Disable two factor authentication for the user.
     *
     * @param  mixed  $user
     * @return void
     */
    public function __invoke($user)
    {
        if (! is_null($user->two_factor_secret) ||
            ! is_null($user->two_factor_recovery_codes) ||
            ! is_null($user->two_factor_confirmed_at)) {
            $user->forceFill([
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
            ] + (AuthID::confirmsTwoFactorAuthentication() || ! is_null($user->two_factor_confirmed_at) ? [
                'two_factor_confirmed_at' => null,
            ] : []))->save();

            TwoFactorAuthenticationDisabled::dispatch($user);
        }
    }
}
