<?php

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

    });

});
