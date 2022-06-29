<?php

namespace Pyz\Yves\Computop;

use Pyz\Yves\Computop\Mapper\PayPalMapper;
use SprykerEco\Yves\Computop\ComputopFactory as EcoComputopFactory;
use SprykerEco\Yves\Computop\Mapper\Init\MapperInterface;

class ComputopFactory extends EcoComputopFactory
{
    /**
     * @return \SprykerEco\Yves\Computop\Mapper\Init\MapperInterface
     */
    public function createOrderPayPalMapper(): MapperInterface
    {
        return new PayPalMapper(
            $this->getComputopApiService(),
            $this->getRouter(),
            $this->getStore(),
            $this->getConfig(),
            $this->getRequestStack()->getCurrentRequest(),
            $this->getUtilEncodingService(),
            $this->getCountryClient(),
        );
    }
}
