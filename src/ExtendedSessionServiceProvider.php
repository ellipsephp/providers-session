<?php declare(strict_types=1);

namespace Ellipse\Session;

use Ellipse\Providers\ExtendedServiceProvider;

class ExtendedSessionServiceProvider extends ExtendedServiceProvider
{
    public function __construct(array $extensions = [])
    {
        parent::__construct(new SessionServiceProvider, $extensions);
    }
}
