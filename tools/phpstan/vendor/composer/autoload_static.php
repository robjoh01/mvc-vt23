<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5b09b67d23773a185ae783c8a7cd423d
{
    public static $files = array (
        '9b38cf48e83f5d8f60375221cd213eee' => __DIR__ . '/..' . '/phpstan/phpstan/bootstrap.php',
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit5b09b67d23773a185ae783c8a7cd423d::$classMap;

        }, null, ClassLoader::class);
    }
}