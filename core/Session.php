<?php

class Session {

    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {

            ini_set('session.sid_length', 64);
            ini_set('session.sid_bits_per_character', 6);
            ini_set('session.hash_function', 'sha256');

            self::setSecureCookieParams();
            session_start();
        }
    }

    private static function setSecureCookieParams() {
        $params = session_get_cookie_params();
        session_set_cookie_params([
            'lifetime' => $params['lifetime'],
            'path' => $params['path'],
            'domain' => $params['domain'],
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
    }

    public static function destroy() {
        session_unset();
        session_destroy();
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function exists($key) {
        return isset($_SESSION[$key]);
    }

    public static function delete($key) {
        if (self::exists($key)) {
            unset($_SESSION[$key]);
        }
    }

    public static function flash($key, $value = '') {
        if (self::exists($key)) {
            $session = self::get($key);
            self::delete($key);
            return $session;
        } else {
            self::set($key, $value);
        }
    }
}
