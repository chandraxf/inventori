<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('hash_id')) {
    function hash_id($id) {
        $CI =& get_instance();
        $CI->load->library('encryption');
        return rtrim(strtr(base64_encode($CI->encryption->encrypt($id)), '+/', '-_'), '=');
    }
}

if (!function_exists('unhash_id')) {
    function unhash_id($hash) {
        $CI =& get_instance();
        $CI->load->library('encryption');
        $encrypted_data = base64_decode(strtr($hash, '-_', '+/'));
        return $CI->encryption->decrypt($encrypted_data);
    }
}
