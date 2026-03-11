<?php

if (!function_exists('setting')) {
    /**
     * Get or set application settings
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    function setting(?string $key = null, $default = null)
    {
        if (is_null($key)) {
            return app(\App\Models\Setting::class);
        }

        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('set_setting')) {
    /**
     * Set an application setting
     *
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @param string $group
     * @param string|null $description
     * @return \App\Models\Setting
     */
    function set_setting(string $key, $value, string $type = 'string', string $group = 'general', ?string $description = null)
    {
        return \App\Models\Setting::set($key, $value, $type, $group, $description);
    }
}
