<?php
/**
 * Created by PhpStorm.
 * User: Nakroma
 * Date: 05.03.2017
 * Time: 20:16
 */

namespace Keyboard\Providers;

use Keyboard\Key;
use Nakroma\Cereal;

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
        $key = new Key;
        $key->key_value = Cereal::generate();
        $key->save();

        return $key;
    }
}