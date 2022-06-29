<?php

namespace Pyz\Yves\Computop\Plugin\Router;

use Spryker\Yves\Router\Route\RouteCollection;
use SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin as EcoComputopRouteProviderPlugin;

class ComputopRouteProviderPlugin extends EcoComputopRouteProviderPlugin
{
    /**
     * @var string
     */
    public const PAYPAL_PREPARE = 'computop-paypal-prepare';

    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = parent::addRoutes($routeCollection);

        $routeCollection = $this->addPayPalPrepare($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPayPalPrepare(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            '/computop/paypal-prepare',
            'Computop',
            'PayPalPrepare',
            'prepareAction',
        );
        $routeCollection->add(static::PAYPAL_PREPARE, $route);

        return $routeCollection;
    }
}
