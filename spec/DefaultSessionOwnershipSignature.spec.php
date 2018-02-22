<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;

use Ellipse\Session\DefaultSessionOwnershipSignature;

describe('DefaultSessionOwnershipSignature', function () {

    beforeEach(function () {

        $this->signature = new DefaultSessionOwnershipSignature(['key1', 'key2']);

    });

    describe('->__invoke()', function () {

        beforeEach(function () {

            $this->request = mock(ServerRequestInterface::class);

        });

        it('should return the request attribute values associated to the attribute names', function () {

            $this->request->getAttribute->with('key1')->returns('value1');
            $this->request->getAttribute->with('key2')->returns('value2');

            $test = ($this->signature)($this->request->get());

            expect($test)->toEqual(['key1' => 'value1', 'key2' => 'value2']);

        });

    });

});
