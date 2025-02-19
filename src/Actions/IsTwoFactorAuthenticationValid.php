<?php

namespace Stia\AuthidPhp\Actions;

use Illuminate\Validation\ValidationException;
use Stia\AuthidPhp\Contracts\TwoFactorAuthenticationProvider;
use Stia\AuthidPhp\Events\TwoFactorAuthenticationConfirmed;
use Stia\AuthidPhp\Traits\Encoder;
use function Symfony\Component\Clock\now;

class IsTwoFactorAuthenticationValid
{
    use Encoder;
    /**
     * The two factor authentication provider.
     *
     * @var TwoFactorAuthenticationProvider
     */
    protected $provider;

    /**
     * Create a new action instance.
     *
     * @param TwoFactorAuthenticationProvider $provider
     * @return void
     */
    public function __construct(TwoFactorAuthenticationProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Confirm the two factor authentication configuration for the user.
     *
     * @param mixed $user
     * @param string $code
     * @return bool
     */
    public function __invoke($user, $code)
    {
        if (
            !empty($user->two_factor_secret) &&
            !empty($code) &&
            $this->provider->verify(
                $this->encode(
                    hash('sha256', decrypt($user->two_factor_secret))
                ),
                $code
            )
        ) {
            $user->forceFill([
                'two_factor_confirmed_at' => now(),
            ])->save();

            TwoFactorAuthenticationConfirmed::dispatch($user);

            return true;
        }

        return false;
    }
}
