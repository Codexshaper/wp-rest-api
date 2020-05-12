<?php

namespace Codexshaper\WP;

use Codexshaper\WP\Client;
use Codexshaper\WP\Helpers\Config;
use Codexshaper\WP\Traits\RequestTrait;
use Codexshaper\WP\Models\BaseModel;

class WpApi extends BaseModel
{
    use RequestTrait;

    /**
     *@var \Automattic\WooCommerce\Client
     */
    protected static $client;

    /**
     *@var array
     */
    protected static $headers = [];

    /**
     * Build Woocommerce connection.
     *
     * @return void
     */

    protected $properties = [];

    /**
     * Get  Inaccessible Property.
     *
     * @param string $name
     *
     * @return int|string|array|object|null
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * Set Option.
     *
     * @param string $name
     * @param string $value
     *
     * @return void
     */
    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }

    public function __call($method, $parameters)
    {
        if (!method_exists($this, $method)) {
            preg_match_all('/((?:^|[A-Z])[a-z]+)/', $method, $partials);
            $method = array_shift($partials[0]);
            if (!method_exists($this, $method)) {
                throw new \Exception('Sorry! you are calling wrong method');
            }
            array_unshift($parameters, strtolower(implode('_', $partials[0])));
        }

        return $this->$method(...$parameters);
    }

    public static function __callStatic($method, $parameters)
    {
        return (new static() )->$method(...$parameters);
    }
    
    private function instance()
    {
        try {
            static::$headers = [
                'header_total'       => Config::get('woocommerce.header_total'),
                'header_total_pages' => Config::get('woocommerce.header_total_pages'),
            ];

            static::$client = new Client(
                Config::get('woocommerce.store_url'),
                Config::get('woocommerce.key'),
                Config::get('woocommerce.secret'),
                [
                    'version'           => 'wp/'.Config::get('woocommerce.api_version'),
                    'wp_api'            => Config::get('woocommerce.wp_api'),
                    'verify_ssl'        => Config::get('woocommerce.verify_ssl'),
                    'query_string_auth' => Config::get('woocommerce.query_string_auth'),
                    'timeout'           => Config::get('woocommerce.timeout'),
                ]
            );
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 1);
        }
    }
}
