<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Cache\CacheItemPoolInterface;

use Interop\Container\ServiceProviderInterface;

use Cache\SessionHandler\Psr6SessionHandler;

use Ellipse\Container;
use Ellipse\Providers\ExtendedServiceProvider;
use Ellipse\Session\ExtendedSessionServiceProvider;
use Ellipse\Session\SetSessionHandlerMiddleware;
use Ellipse\Session\StartSessionMiddleware;
use Ellipse\Session\ValidateSessionMiddleware;
use Ellipse\Session\DefaultSessionCacheItemPool;
use Ellipse\Session\DefaultSessionOwnershipSignature;

describe('ExtendedSessionServiceProvider', function () {

    beforeEach(function () {

        $this->provider = new ExtendedSessionServiceProvider;

    });

    it('should implement ExtendedServiceProvider', function () {

        expect($this->provider)->toBeAnInstanceOf(ExtendedServiceProvider::class);

    });

    context('when consumed by a container', function () {

        beforeEach(function () {

            $this->container = new Container([$this->provider]);

        });

        it('should provide an implementation of Psr6SessionHandler for the SessionHandlerInterface::class alias', function () {

            $test = $this->container->get(SessionHandlerInterface::class);

            expect($test)->toBeAnInstanceOf(Psr6SessionHandler::class);

        });

        it('should provide an instance of StartSessionMiddleware for the StartSessionMiddleware::class alias', function () {

            $test = $this->container->get(StartSessionMiddleware::class);

            expect($test)->toBeAnInstanceOf(StartSessionMiddleware::class);

        });

        it('should provide an instance of ValidateSessionMiddleware for the ValidateSessionMiddleware::class alias', function () {

            $test = $this->container->get(ValidateSessionMiddleware::class);

            expect($test)->toBeAnInstanceOf(ValidateSessionMiddleware::class);

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

        it('should provide an instance of DefaultSessionOwnershipSignature for the ellipse.session.ownership.signature alias', function () {

            $test = $this->container->get('ellipse.session.ownership.signature');

            expect($test)->toBeAnInstanceOf(DefaultSessionOwnershipSignature::class);

        });

        context('when an extension is given for ellipse.session.id.prefix alias', function () {

            it('should return the value returned by the extension', function () {

                $provider = new ExtendedSessionServiceProvider([
                    'ellipse.session.id.prefix' => function ($container, string $prefix) {

                        return $prefix.= 'extended';

                    },
                ]);

                $container = new Container([$provider]);

                $test = $container->get('ellipse.session.id.prefix');

                expect($test)->toEqual('ellipse_extended');

            });

        });

        context('when an extension is given for ellipse.session.ttl alias', function () {

            it('should return the value returned by the extension', function () {

                $provider = new ExtendedSessionServiceProvider([
                    'ellipse.session.ttl' => function ($container, int $ttl) {

                        return $ttl+= 3600;

                    },
                ]);

                $container = new Container([$provider]);

                $test = $container->get('ellipse.session.ttl');

                expect($test)->toEqual(7200);

            });

        });

        context('when an extension is given for ellipse.session.cookie.options alias', function () {

            it('should return the value returned by the extension', function () {

                $provider = new ExtendedSessionServiceProvider([
                    'ellipse.session.cookie.options' => function ($container, array $options) {

                        return array_merge($options, ['key' => 'value']);

                    },
                ]);

                $container = new Container([$provider]);

                $test = $container->get('ellipse.session.cookie.options');

                expect($test)->toEqual(['key' => 'value']);

            });

        });

        context('when an extension is given for ellipse.session.cache alias', function () {

            it('should return the value returned by the extension', function () {

                $this->cache = mock(CacheItemPoolInterface::class)->get();

                $provider = new ExtendedSessionServiceProvider([
                    'ellipse.session.cache' => function ($container, CacheItemPoolInterface $cache) {

                        return $this->cache;

                    },
                ]);

                $container = new Container([$provider]);

                $test = $container->get('ellipse.session.cache');

                expect($test)->toBe($this->cache);

            });

        });

        context('when an extension is given for ellipse.session.ownership.attributes alias', function () {

            it('should return the value returned by the extension', function () {

                $provider = new ExtendedSessionServiceProvider([
                    'ellipse.session.ownership.attributes' => function ($container, array $options) {

                        return array_merge($options, ['key' => 'value']);

                    },
                ]);

                $container = new Container([$provider]);

                $test = $container->get('ellipse.session.ownership.attributes');

                expect($test)->toEqual(['key' => 'value']);

            });

        });

    });

});
