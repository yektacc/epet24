<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 4/20/19
 * Time: 9:56 PM
 */

namespace App\Helper;

use App\Jobs\SendSMS;

class AppConfig
{
    public static function generate_random(): int {
        $make_seed = function(){
            list($usec, $sec) = explode(' ', microtime());
                return $sec + $usec * 1000000;
        };
        srand($make_seed());
        return random_int(LOW,HIGH);
    }

    public static function generate_product_code(): string {
        return PRODUCT_PREFIX.'-'.self::generate_random();
    }

    public static function generate_variant_code(): string {
        return VARIANT_PREFIX.'-'.self::generate_random();
    }

    public static function generate_seller_code(): string {
        return SELLER_PREFIX.'-'.self::generate_random();
    }

    public static function generate_order_code(): string {
        return ORDER_PREFIX.'-'.self::generate_random();
    }

    public static function generate_coupon_code(): string {
        return COUPON_PREFIX.'-'.self::generate_random();
    }
    public static function generate_otp_code($length = 6): string {
        $pool = '0123456789';
        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }

    public static function sendSMS($phone, $data) {
        dispatch(new SendSMS($phone, $data));
    }
}
