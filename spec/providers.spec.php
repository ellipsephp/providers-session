<?php

use Psr\Cache\CacheItemPoolInterface;

use Ellipse\Container;
use Ellipse\Session\SetSessionHandlerMiddleware;
use Ellipse\Session\StartSessionMiddleware;
use Ellipse\Session\ValidateSessionMiddleware;
use Ellipse\Session\DefaultSessionOwnershipSignature;

describe('providers.php', function () {

    beforeEach(function () {

        $this->providers = require __DIR__ . '/../providers.php';

    });

    context('when consumed by a container', function () {

        beforeEach(function () {

            $this->container = new Container($this->providers);

        });

        it('should provide an implementation of SessionHandlerInterface for the SessionHandlerInterface::class id', function () {

            $test = $this->container->get(SessionHandlerInterface::class);

            expect($test)->toBeAnInstanceOf(SessionHandlerInterface::class);

        });

        it('should provide an instance of SetSessionHandlerMiddleware for the SetSessionHandlerMiddleware::class id', function () {

            $test = $this->container->get(SetSessionHandlerMiddleware::class);

            expect($test)->toBeAnInstanceOf(SetSessionHandlerMiddleware::class);

        });

        it('should provide an instance of StartSessionMiddleware for the StartSessionMiddleware::class id', function () {

            $test = $this->container->get(StartSessionMiddleware::class);

            expect($test)->toBeAnInstanceOf(StartSessionMiddleware::class);

        });

        it('should provide an instance of ValidateSessionMiddleware for the ValidateSessionMiddleware::class id', function () {

            $test = $this->container->get(ValidateSessionMiddleware::class);

            expect($test)->toBeAnInstanceOf(ValidateSessionMiddleware::class);

        });

        it('should provide a string for the ellipse.session.id.prefix id', function () {

            $test = $this->container->get('ellipse.session.id.prefix');

            expect($test)->toBeA('string');

        });

        it('should provide an integer for the ellipse.session.ttl id', function () {

            $test = $this->container->get('ellipse.session.ttl');

            expect($test)->toBeAn('integer');

        });

        it('should provide an array for the ellipse.session.cookie.options id', function () {

            $test = $this->container->get('ellipse.session.cookie.options');

            expect($test)->toBeAn('array');

        });

        it('should provide an instance of CacheItemPoolInterface for the ellipse.session.cache id', function () {

            $test = $this->container->get('ellipse.session.cache');

            expect($test)->toBeAnInstanceOf(CacheItemPoolInterface::class);

        });

        it('should provide an array for the ellipse.session.ownership.attributes id', function () {

            $test = $this->container->get('ellipse.session.ownership.attributes');

            expect($test)->toBeAn('array');

        });

        it('should provide an instance of DefaultSessionOwnershipSignature for the ellipse.session.ownership.signature id', function () {

            $test = $this->container->get('ellipse.session.ownership.signature');

            expect($test)->toBeAnInstanceOf(DefaultSessionOwnershipSignature::class);

        });

    });

});
