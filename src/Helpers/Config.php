<?php

namespace Codexshaper\WooCommerce\PHP\Helpers;

class Config
{
    public function get($config, $default = null)
    {
        $keys     = explode('.', $config);
        $filename = array_shift($keys);

        $data = include __DIR__ . "/../../../../../config/{$filename}.php";

        foreach ($keys as $key) {
            if (is_array($data) && array_key_exists($key, $data)) {
                $data = $data[$key];
            } else {
                $data = null;
            }
        }

        if (!$data) {
            $data = $default;
        }

        return $data;
    }
}
