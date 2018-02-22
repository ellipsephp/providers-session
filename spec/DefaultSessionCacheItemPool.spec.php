<?php

use Psr\Cache\CacheItemPoolInterface;

use Ellipse\Session\DefaultSessionCacheItemPool;

describe('DefaultSessionCacheItemPool', function () {

    beforeEach(function () {

        $this->cache = new DefaultSessionCacheItemPool;

    });

    it('should implement CacheItemPoolInterface', function () {

        expect($this->cache)->toBeAnInstanceOf(CacheItemPoolInterface::class);

    });

});
