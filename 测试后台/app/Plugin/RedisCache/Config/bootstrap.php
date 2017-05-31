<?php
/**
 * Config settings to connect to the Redis server.
 * There are different configs for servers performing
 * different tasks to allow each server to be configured
 * to it's specific task
 */
class RedisConfig {

    static public $default = array(
        'cache' => array(
            'host' => '101.201.234.5'
            , 'port' => 6379
            , 'password' => 'Redis',
            'database'=>5
        )
        , 'session' => array(
            'host' => '101.201.234.5'
            , 'port' => 6379
            , 'password' => 'Redis',
            'database'=>5
        )
    );
}

// Redis plugin depends on Predis
// @link https://github.com/nrk/predis
App::import('Lib', 'RedisCache.Predis\Autoloader');
Predis\Autoloader::register();
