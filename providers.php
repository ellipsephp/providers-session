<?php declare(strict_types=1);

use Psr\Cache\CacheItemPoolInterface;

use Ellipse\Session\SessionServiceProvider;

return [
    new SessionServiceProvider([

        /**
         * Return the session id prefix. Default to 'ellipse_'.
         */
        'id.prefix' => function ($container, string $prefix): string {

            return $prefix;

        },

        /**
         * Return the session time to live in second. Default to 3600.
         */
        'ttl' => function ($container, int $ttl): int {

            return $ttl;

        },

        /**
         * Return the session cookie options array overwriting the default ones.
         * Default to empty array.
         */
        'cookie.options' => function ($container, array $options): array {

            return $options;

        },

        /**
         * Return the cache pool used to store session data. Default to a cache
         * pool emulating php default session storage.
         */
        'cache' => function ($container, CacheItemPoolInterface $cache): CacheItemPoolInterface {

            return $cache;

        },

        /**
         * Return the names of the request attributes used to validate session
         * ownership. Default to empty array.
         */
        'ownership.attributes' => function ($container, array $attributes): array {

            return $attributes;

        },

    ]),
];
