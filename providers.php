<?php declare(strict_types=1);

use Psr\Cache\CacheItemPoolInterface;

use Ellipse\Session\ExtendedSessionServiceProvider;

return [
    new ExtendedSessionServiceProvider([

        /**
         * Return the session id prefix. Default to 'ellipse_'.
         */
        'ellipse.session.id.prefix' => function ($container, string $prefix): string {

            return $prefix;

        },

        /**
         * Return the session time to live in second. Default to 3600.
         */
        'ellipse.session.ttl' => function ($container, int $ttl): int {

            return $ttl;

        },

        /**
         * Return the session cookie options array overwriting the default ones.
         * Default to empty array.
         */
        'ellipse.session.cookie.options' => function ($container, array $options): array {

            return $options;

        },

        /**
         * Return the cache pool used to store session data. Default to a cache
         * pool emulating php default session storage.
         */
        'ellipse.session.cache' => function ($container, CacheItemPoolInterface $cache): CacheItemPoolInterface {

            return $cache;

        },

        /**
         * Return the names of the request attributes used to validate session
         * ownership. Default to empty array.
         */
        'ellipse.session.ownership.attributes' => function ($container, array $attributes): array {

            return $attributes;

        },

    ]),
];
