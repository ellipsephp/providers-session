<?php declare(strict_types=1);

namespace Ellipse\Session;

use SessionHandlerInterface;

use Psr\Container\ContainerInterface;

use Psr\Cache\CacheItemPoolInterface;

use Interop\Container\ServiceProviderInterface;

use Cache\SessionHandler\Psr6SessionHandler;

use Ellipse\Session\SetSessionHandlerMiddleware;
use Ellipse\Session\StartSessionMiddleware;
use Ellipse\Session\ValidateSessionMiddleware;

class SessionServiceProvider implements ServiceProviderInterface
{
    /**
     * The user defined service extensions.
     *
     * @var array
     */
    private $extensions;

    /**
     * Set up a session service provider with the given extensions.
     *
     * @param array $extensions
     */
    public function __construct(array $extensions = [])
    {
        $this->extensions = $extensions;
    }

    /**
     * Return the prefixed version of the given id.
     *
     * @param string $id
     * @return string
     */
    private function prefixed(string $id): string
    {
        return sprintf('ellipse.http.%s', $id);
    }

    /**
     * @inheritdoc
     */
    public function getFactories()
    {
        return [
            SessionHandlerInterface::class => [$this, 'getSessionHandler'],
            SetSessionHandlerMiddleware::class => [$this, 'getSetSessionHandlerMiddleware'],
            StartSessionMiddleware::class => [$this, 'getStartSessionMiddleware'],
            ValidateSessionMiddleware::class => [$this, 'getValidateSessionMiddleware'],
            'ellipse.session.id.prefix' => [$this, 'getSessionIdPrefix'],
            'ellipse.session.ttl' => [$this, 'getSessionTtl'],
            'ellipse.session.cookie.options' => [$this, 'getSessionCookieOptions'],
            'ellipse.session.cache' => [$this, 'getSessionCache'],
            'ellipse.session.ownership.attributes' => [$this, 'getSessionOwnershipAttributes'],
            'ellipse.session.ownership.signature' => [$this, 'getSessionOwnershipSignature'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getExtensions()
    {
        $ids = array_keys($this->extensions);
        $callables = array_values($this->extensions);

        $prefixed = array_map([$this, 'prefixed'], $ids);

        return array_combine($prefixed, $callables);
    }

    /**
     * Return a session handler based on the cache item pool.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return \SessionHandlerInterface
     */
    public function getSessionHandler(ContainerInterface $container): SessionHandlerInterface
    {
        $prefix = $container->get('ellipse.session.id.prefix');
        $ttl = $container->get('ellipse.session.ttl');
        $cache = $container->get('ellipse.session.cache');

        return new Psr6SessionHandler($cache, ['prefix' => $prefix, 'ttl' => $ttl]);
    }

    /**
     * Return a set session handler middleware.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return \Ellipse\Session\SetSessionHandlerMiddleware
     */
    public function getSetSessionHandlerMiddleware(ContainerInterface $container): SetSessionHandlerMiddleware
    {
        $handler = $container->get(SessionHandlerInterface::class);

        return new SetSessionHandlerMiddleware($handler);
    }

    /**
     * Return a start session middleware.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return \Ellipse\Session\StartSessionMiddleware
     */
    public function getStartSessionMiddleware(ContainerInterface $container): StartSessionMiddleware
    {
        $cookie = $container->get('ellipse.session.cookie.options');

        return new StartSessionMiddleware($cookie);
    }

    /**
     * Return a validate session middleware.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return \Ellipse\Session\ValidateSessionMiddleware
     */
    public function getValidateSessionMiddleware(ContainerInterface $container): ValidateSessionMiddleware
    {
        $signature = $container->get('ellipse.session.ownership.signature');

        return new ValidateSessionMiddleware($signature);
    }

    /**
     * Return 'ellipse_' as session id prefix.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return string
     */
    public function getSessionIdPrefix(ContainerInterface $container): string
    {
        return 'ellipse_';
    }

    /**
     * Return 3600 as session time to live.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return int
     */
    public function getSessionTtl(ContainerInterface $container): int
    {
        return 3600;
    }

    /**
     * Return an empty array as default session cookie options overwriting the
     * default ones.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return array
     */
    public function getSessionCookieOptions(ContainerInterface $container): array
    {
        return [];
    }

    /**
     * Return a cache pool emulating php default session storage as default
     * session data storage.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return \Psr\Cache\CacheItemPoolInterface
     */
    public function getSessionCache(ContainerInterface $container): CacheItemPoolInterface
    {
        return new DefaultSessionCacheItemPool;
    }

    /**
     * Return an empty array as default session ownership attributes.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return array
     */
    public function getSessionOwnershipAttributes(ContainerInterface $container): array
    {
        return [];
    }

    /**
     * Return a default session ownership signature based on the session
     * ownership attributes.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return callable
     */
    public function getSessionOwnershipSignature(ContainerInterface $container): callable
    {
        $attributes = $container->get('ellipse.session.ownership.attributes');

        return new DefaultSessionOwnershipSignature($attributes);
    }
}
