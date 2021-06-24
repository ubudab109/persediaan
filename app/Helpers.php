<?php

use Illuminate\Support\Facades\Route;

/**
 * Membuat active link sesuai route yang sedang dikunjungi
 * @param [uri] $uri URI saat ini
 * @param string output Nama kelas active
 */
if (!function_exists('set_active')) {
    function set_active($uri, $output = 'active')
    {
        if (is_array($uri)) {
            foreach ($uri as $u) {
                if (Route::is($u)) {
                    return $output;
                }
            }
        } else {
            if (Route::is($uri)) {
                return $output;
            }
        }
    }
}

/**
 * Membuat format rupiah
 * @param [number] $number angka numeric
 * @return number_format Rupiah
 */
if (!function_exists('rupiah')) {
    function rupiah($numeric)
    {
        $result = number_format($numeric, 0, ',', '.');
        return 'Rp. ' . $result;
    }
}