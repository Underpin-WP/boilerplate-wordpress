<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc0dca9a0b9e2c9daa072423873b7cd36
{
    public static $files = array (
        'b33e3d135e5d9e47d845c576147bda89' => __DIR__ . '/..' . '/php-di/php-di/src/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'U' => 
        array (
            'Underpin\\WordPress\\' => 19,
            'Underpin\\' => 9,
        ),
        'P' => 
        array (
            'Psr\\Container\\' => 14,
            'Plugin_Name_Replace_Me\\' => 23,
            'PhpDocReader\\' => 13,
        ),
        'L' => 
        array (
            'Laravel\\SerializableClosure\\' => 28,
        ),
        'I' => 
        array (
            'Invoker\\' => 8,
        ),
        'D' => 
        array (
            'DI\\' => 3,
        ),
        'C' => 
        array (
            'Composer\\Installers\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Underpin\\WordPress\\' => 
        array (
            0 => __DIR__ . '/..' . '/underpin/wordpress-integration/lib',
        ),
        'Underpin\\' => 
        array (
            0 => __DIR__ . '/..' . '/underpin/underpin/lib',
        ),
        'Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/container/src',
        ),
        'Plugin_Name_Replace_Me\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib',
        ),
        'PhpDocReader\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-di/phpdoc-reader/src/PhpDocReader',
        ),
        'Laravel\\SerializableClosure\\' => 
        array (
            0 => __DIR__ . '/..' . '/laravel/serializable-closure/src',
        ),
        'Invoker\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-di/invoker/src',
        ),
        'DI\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-di/php-di/src',
        ),
        'Composer\\Installers\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc0dca9a0b9e2c9daa072423873b7cd36::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc0dca9a0b9e2c9daa072423873b7cd36::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc0dca9a0b9e2c9daa072423873b7cd36::$classMap;

        }, null, ClassLoader::class);
    }
}
