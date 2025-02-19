<?php

namespace Stia\AuthidPhp;

class Features
{
    /**
     * Determine if the given feature is enabled.
     *
     * @param  string  $feature
     * @return bool
     */
    public static function enabled(string $feature)
    {
        return in_array($feature, config('authid.features', []));
    }

    /**
     * Determine if the feature is enabled and has a given option enabled.
     *
     * @param  string  $feature
     * @param  string  $option
     * @return bool
     */
    public static function optionEnabled(string $feature, string $option)
    {
        return static::enabled($feature) &&
            config("authid-options.{$feature}.{$option}") === true;
    }

    /**
     * Enable the two factor authentication feature.
     *
     * @param  array  $options
     * @return string
     */
    public static function twoFactorAuthentication(array $options = [])
    {
        if (! empty($options)) {
            config(['authid-options.two-factor-authentication' => $options]);
        }

        return 'two-factor-authentication';
    }
}
