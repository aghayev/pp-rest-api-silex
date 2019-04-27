<?php

namespace Pleasepay;

class TokenHelper {

public static function generateToken($length) {

return bin2hex(openssl_random_pseudo_bytes($length*2));

}

public static function generateRandomString($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

}

