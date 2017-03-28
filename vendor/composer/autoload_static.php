<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0955228efefbf3524ad486e4a1dec14f
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Component\\Yaml\\' => 23,
            'Symfony\\Component\\Validator\\' => 28,
            'Symfony\\Component\\Translation\\' => 30,
            'Symfony\\Component\\Finder\\' => 25,
            'Symfony\\Component\\Filesystem\\' => 29,
            'Symfony\\Component\\Debug\\' => 24,
            'Symfony\\Component\\Console\\' => 26,
            'Symfony\\Component\\Config\\' => 25,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Component\\Yaml\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/yaml',
        ),
        'Symfony\\Component\\Validator\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/validator',
        ),
        'Symfony\\Component\\Translation\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/translation',
        ),
        'Symfony\\Component\\Finder\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/finder',
        ),
        'Symfony\\Component\\Filesystem\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/filesystem',
        ),
        'Symfony\\Component\\Debug\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/debug',
        ),
        'Symfony\\Component\\Console\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/console',
        ),
        'Symfony\\Component\\Config\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/config',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Propel' => 
            array (
                0 => __DIR__ . '/..' . '/propel/propel/src',
            ),
        ),
    );

    public static $classMap = array (
        'Base\\CacheMatchVariables' => __DIR__ . '/..' . '/bin/Base/CacheMatchVariables.php',
        'Base\\CacheMatchVariablesQuery' => __DIR__ . '/..' . '/bin/Base/CacheMatchVariablesQuery.php',
        'Base\\CacheRules' => __DIR__ . '/..' . '/bin/Base/CacheRules.php',
        'Base\\CacheRulesQuery' => __DIR__ . '/..' . '/bin/Base/CacheRulesQuery.php',
        'Base\\CachedRequests' => __DIR__ . '/..' . '/bin/Base/CachedRequests.php',
        'Base\\CachedRequestsQuery' => __DIR__ . '/..' . '/bin/Base/CachedRequestsQuery.php',
        'Base\\GetVariables' => __DIR__ . '/..' . '/bin/Base/GetVariables.php',
        'Base\\GetVariablesQuery' => __DIR__ . '/..' . '/bin/Base/GetVariablesQuery.php',
        'Base\\GlobalCacheMatchVariables' => __DIR__ . '/..' . '/bin/Base/GlobalCacheMatchVariables.php',
        'Base\\GlobalCacheMatchVariablesQuery' => __DIR__ . '/..' . '/bin/Base/GlobalCacheMatchVariablesQuery.php',
        'Base\\GlobalCacheRules' => __DIR__ . '/..' . '/bin/Base/GlobalCacheRules.php',
        'Base\\GlobalCacheRulesQuery' => __DIR__ . '/..' . '/bin/Base/GlobalCacheRulesQuery.php',
        'Base\\GlobalCachedRequests' => __DIR__ . '/..' . '/bin/Base/GlobalCachedRequests.php',
        'Base\\GlobalCachedRequestsQuery' => __DIR__ . '/..' . '/bin/Base/GlobalCachedRequestsQuery.php',
        'Base\\GlobalGetVariables' => __DIR__ . '/..' . '/bin/Base/GlobalGetVariables.php',
        'Base\\GlobalGetVariablesQuery' => __DIR__ . '/..' . '/bin/Base/GlobalGetVariablesQuery.php',
        'CacheMatchVariables' => __DIR__ . '/..' . '/bin/CacheMatchVariables.php',
        'CacheMatchVariablesQuery' => __DIR__ . '/..' . '/bin/CacheMatchVariablesQuery.php',
        'CacheRules' => __DIR__ . '/..' . '/bin/CacheRules.php',
        'CacheRulesQuery' => __DIR__ . '/..' . '/bin/CacheRulesQuery.php',
        'CachedRequests' => __DIR__ . '/..' . '/bin/CachedRequests.php',
        'CachedRequestsQuery' => __DIR__ . '/..' . '/bin/CachedRequestsQuery.php',
        'GetVariables' => __DIR__ . '/..' . '/bin/GetVariables.php',
        'GetVariablesQuery' => __DIR__ . '/..' . '/bin/GetVariablesQuery.php',
        'GlobalCacheMatchVariables' => __DIR__ . '/..' . '/bin/GlobalCacheMatchVariables.php',
        'GlobalCacheMatchVariablesQuery' => __DIR__ . '/..' . '/bin/GlobalCacheMatchVariablesQuery.php',
        'GlobalCacheRules' => __DIR__ . '/..' . '/bin/GlobalCacheRules.php',
        'GlobalCacheRulesQuery' => __DIR__ . '/..' . '/bin/GlobalCacheRulesQuery.php',
        'GlobalCachedRequests' => __DIR__ . '/..' . '/bin/GlobalCachedRequests.php',
        'GlobalCachedRequestsQuery' => __DIR__ . '/..' . '/bin/GlobalCachedRequestsQuery.php',
        'GlobalGetVariables' => __DIR__ . '/..' . '/bin/GlobalGetVariables.php',
        'GlobalGetVariablesQuery' => __DIR__ . '/..' . '/bin/GlobalGetVariablesQuery.php',
        'Map\\CacheMatchVariablesTableMap' => __DIR__ . '/..' . '/bin/Map/CacheMatchVariablesTableMap.php',
        'Map\\CacheRulesTableMap' => __DIR__ . '/..' . '/bin/Map/CacheRulesTableMap.php',
        'Map\\CachedRequestsTableMap' => __DIR__ . '/..' . '/bin/Map/CachedRequestsTableMap.php',
        'Map\\GetVariablesTableMap' => __DIR__ . '/..' . '/bin/Map/GetVariablesTableMap.php',
        'Map\\GlobalCacheMatchVariablesTableMap' => __DIR__ . '/..' . '/bin/Map/GlobalCacheMatchVariablesTableMap.php',
        'Map\\GlobalCacheRulesTableMap' => __DIR__ . '/..' . '/bin/Map/GlobalCacheRulesTableMap.php',
        'Map\\GlobalCachedRequestsTableMap' => __DIR__ . '/..' . '/bin/Map/GlobalCachedRequestsTableMap.php',
        'Map\\GlobalGetVariablesTableMap' => __DIR__ . '/..' . '/bin/Map/GlobalGetVariablesTableMap.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0955228efefbf3524ad486e4a1dec14f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0955228efefbf3524ad486e4a1dec14f::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit0955228efefbf3524ad486e4a1dec14f::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit0955228efefbf3524ad486e4a1dec14f::$classMap;

        }, null, ClassLoader::class);
    }
}
