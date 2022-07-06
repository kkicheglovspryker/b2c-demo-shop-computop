<?php

namespace Pyz\Zed\ComputopApi\Business;

use Pyz\Zed\ComputopApi\Business\Mapper\ComputopApiBusinessMapperFactory;
use SprykerEco\Zed\ComputopApi\Business\ComputopApiBusinessFactory as EcoComputopApiBusinessFactory;
use SprykerEco\Zed\ComputopApi\Business\Mapper\ComputopApiBusinessMapperFactoryInterface;
use SprykerEco\Zed\ComputopApi\Business\Request\PostPlace\PostPlaceRequestInterface;

class ComputopApiBusinessFactory extends EcoComputopApiBusinessFactory
{
    /**
     * @return \SprykerEco\Zed\ComputopApi\Business\Mapper\ComputopApiBusinessMapperFactoryInterface
     */
    public function createMapperFactory(): ComputopApiBusinessMapperFactoryInterface
    {
        return new ComputopApiBusinessMapperFactory();
    }

    /**
     * @return \SprykerEco\Zed\ComputopApi\Business\Request\PostPlace\PostPlaceRequestInterface
     */
    public function createInquirePaymentRequest(): PostPlaceRequestInterface
    {
        $paymentRequest =parent::createInquirePaymentRequest();

        $paymentRequest->registerMapper($this->createMapperFactory()->createInquirePayPalExpressMapper());

        return $paymentRequest;
    }
}
