<?php

spl_autoload_register( 'just_autoloader' );

/**
 * Autoloader for Just.
 *
 * @param string $class The class name to load.
 */
function just_autoloader($class) {
    $class_path = str_replace('\\', '/', $class);
    $file =  __DIR__ . '/../' . $class_path . '.php';
    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
}
