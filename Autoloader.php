<?php

/**
 * Simple autoloader for basket classes. If we'll use composer we have to use composer autoloader
 */
class Autoloader
{
    /**
     * Register php class (require it file)
     */
    public static function register()
    {
        spl_autoload_register(function ($class) {

            $file = __DIR__ . DIRECTORY_SEPARATOR .
                str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';

            if (file_exists($file)) {
                require $file;
                return true;
            }
            return false;
        });
    }
}

Autoloader::register();
