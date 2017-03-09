<?php
/**
 * Created by PhpStorm.
 * User: Nakroma
 * Date: 05.03.2017
 * Time: 20:16
 */

namespace laravelTest\Providers;

use laravelTest\Key;

class KeyGenerationService
{
    /**
     * Returns a random key.
     *
     * @return Key
     */
    public static function generateKey()
    {
        // Generate key
        $raw_key = md5(microtime().rand());
        $key = new Key;
        $key->key_value = $raw_key;
        $key->save();

        return $key;
    }
}