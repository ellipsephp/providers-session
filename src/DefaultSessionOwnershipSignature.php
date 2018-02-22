<?php declare(strict_types=1);

namespace Ellipse\Session;

use Psr\Http\Message\ServerRequestInterface;

class DefaultSessionOwnershipSignature
{
    /**
     * The request attribute names.
     *
     * @var array
     */
    private $attributes;

    /**
     * Set up a default session ownership signature with the given array of
     * request attribute names.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * Return the attribute values of the given request.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return array
     */
    public function __invoke(ServerRequestInterface $request): array
    {
        return array_reduce($this->attributes, function (array $map, string $key) use ($request) {

            $map[$key] = $request->getAttribute($key);

            return $map;

        }, []);
    }
}
