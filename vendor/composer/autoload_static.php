<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit867d8359a17f8c3dfad491f4803c918f
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit867d8359a17f8c3dfad491f4803c918f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit867d8359a17f8c3dfad491f4803c918f::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit867d8359a17f8c3dfad491f4803c918f::$classMap;

        }, null, ClassLoader::class);
    }
}