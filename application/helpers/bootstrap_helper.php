<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('load_bootstrap_css')) {
    function load_bootstrap_css() {
        return '<link rel="stylesheet" href="' . base_url('vendor/twbs/bootstrap/dist/css/bootstrap.min.css') . '">';
    }
}

if (!function_exists('load_bootstrap_js')) {
    function load_bootstrap_js() {
        return '<script src="' . base_url('vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js') . '"></script>';
    }
}

if (!function_exists('bootstrap_alert')) {
    function bootstrap_alert($message, $type = 'primary') {
        return '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
                    ' . $message . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>';
    }
}

if (!function_exists('bootstrap_button')) {
    function bootstrap_button($text, $url = '#', $class = 'btn-primary') {
        return '<a href="' . $url . '" class="btn ' . $class . '">' . $text . '</a>';
    }
}
?>