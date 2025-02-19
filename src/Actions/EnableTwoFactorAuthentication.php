<?php

namespace Stia\AuthidPhp\Actions;

use Illuminate\Support\Collection;
use Stia\AuthidPhp\Contracts\TwoFactorAuthenticationProvider;
use Stia\AuthidPhp\Events\TwoFactorAuthenticationEnabled;
use Stia\AuthidPhp\RecoveryCode;

class EnableTwoFactorAuthentication
{
    /**
     * The two factor authentication provider.
     *
     * @var TwoFactorAuthenticationProvider
     */
    protected $provider;

    /**
     * Create a new action instance.
     *
     * @param  TwoFactorAuthenticationProvider  $provider
     * @return void
     */
    public function __construct(TwoFactorAuthenticationProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Enable two factor authentication for the user.
     *
     * @param  mixed  $user
     * @param  bool  $force
     * @return void
     */
    public function __invoke($user, $force = false)
    {
        if (empty($user->two_factor_secret) || $force === true) {
            $secretLength = (int) config('authid-options.two-factor-authentication.secret-length', 16);

            $user->forceFill([
                'two_factor_secret' => encrypt($this->provider->generateSecretKey($secretLength)),
                'two_factor_recovery_codes' => encrypt(json_encode(Collection::times(8, function () {
                    return RecoveryCode::generate();
                })->all())),
            ])->save();

            TwoFactorAuthenticationEnabled::dispatch($user);
        }
    }
}
