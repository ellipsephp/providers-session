<?php

use function Eloquent\Phony\Kahlan\stub;
use function Eloquent\Phony\Kahlan\mock;

use Psr\Cache\CacheItemPoolInterface;

use Interop\Container\ServiceProviderInterface;

use Cache\SessionHandler\Psr6SessionHandler;

use Ellipse\Container;
use Ellipse\Session\SessionServiceProvider;
use Ellipse\Session\SetSessionHandlerMiddleware;
use Ellipse\Session\StartSessionMiddleware;
use Ellipse\Session\ValidateSessionMiddleware;
use Ellipse\Session\DefaultSessionCacheItemPool;
use Ellipse\Session\DefaultSessionOwnershipSignature;

describe('SessionServiceProvider', function () {

    beforeEach(function () {

        $this->provider = new SessionServiceProvider;

    });

    it('should implement ServiceProviderInterface', function () {

        expect($this->provider)->toBeAnInstanceOf(ServiceProviderInterface::class);

    });

    context('when consumed by a container', function () {

        beforeEach(function () {

            $this->container = new Container([$this->provider]);

        });

        it('should provide an instance of Psr6SessionHandler wrapped around the cache implementation for the SessionHandlerInterface::class alias', function () {

            $cache = mock(CacheItemPoolInterface::class)->get();

            allow($this->container)->toReceive('get')
                ->with('ellipse.session.cache')
                ->andReturn($cache);

            allow($this->container)->toReceive('get')
                ->with('ellipse.session.id.prefix')
                ->andReturn('prefix');

            allow($this->container)->toReceive('get')
                ->with('ellipse.session.ttl')
                ->andReturn(3600);

            $test = $this->container->get(SessionHandlerInterface::class);

            $handler = new Psr6SessionHandler($cache, [
                'prefix' => 'prefix',
                'ttl' => 3600,
            ]);

            expect($test)->toEqual($handler);

        });

        it('should provide an instance of SetSessionHandlerMiddleware using the session handler for the StartSessionMiddleware::class alias', function () {

            $handler = mock(SessionHandlerInterface::class)->get();

            allow($this->container)->toReceive('get')
                ->with(SessionHandlerInterface::class)
                ->andReturn($handler);

            $test = $this->container->get(SetSessionHandlerMiddleware::class);

            $middleware = new SetSessionHandlerMiddleware($handler);

            expect($test)->toEqual($middleware);

        });

        it('should provide an instance of StartSessionMiddleware using the cookie options for the StartSessionMiddleware::class alias', function () {

            allow($this->container)->toReceive('get')
                ->with('ellipse.session.cookie.options')
                ->andReturn(['options']);

            $test = $this->container->get(StartSessionMiddleware::class);

            $middleware = new StartSessionMiddleware(['options']);

            expect($test)->toEqual($middleware);

        });

        it('should provide an instance of ValidateSessionMiddleware using the ownership signature for the ValidateSessionMiddleware::class alias', function () {

            $signature = stub();

            allow($this->container)->toReceive('get')
                ->with('ellipse.session.ownership.signature')
                ->andReturn($signature);

            $test = $this->container->get(ValidateSessionMiddleware::class);

            $middleware = new ValidateSessionMiddleware($signature);

            expect($test)->toEqual($middleware);

        });

        it('should provide ellipse_ for the ellipse.session.id.prefix alias', function () {

            $test = $this->container->get('ellipse.session.id.prefix');

            expect($test)->toEqual('ellipse_');

        });

        it('should provide 3600 for the ellipse.session.ttl alias', function () {

            $test = $this->container->get('ellipse.session.ttl');

            expect($test)->toEqual(3600);

        });

        it('should provide an empty array for the ellipse.session.cookie.options alias', function () {

            $test = $this->container->get('ellipse.session.cookie.options');

            expect($test)->toEqual([]);

        });

        it('should provide an instance of DefaultSessionCacheItemPool for the ellipse.session.cache alias', function () {

            $test = $this->container->get('ellipse.session.cache');

            expect($test)->toBeAnInstanceOf(DefaultSessionCacheItemPool::class);

        });

        it('should provide an empty array for the ellipse.session.ownership.attributes alias', function () {

            $test = $this->container->get('ellipse.session.ownership.attributes');

            expect($test)->toEqual([]);

        });

        it('should provide an instance of DefaultSessionOwnershipSignature using the ownership attributes for the ellipse.session.ownership.signature alias', function () {

            allow($this->container)->toReceive('get')
                ->with('ellipse.session.ownership.attributes')
                ->andReturn(['attribute']);

            $test = $this->container->get('ellipse.session.ownership.signature');

            $signature = new DefaultSessionOwnershipSignature(['attribute']);

            expect($test)->toEqual($signature);

        });

    });

});
