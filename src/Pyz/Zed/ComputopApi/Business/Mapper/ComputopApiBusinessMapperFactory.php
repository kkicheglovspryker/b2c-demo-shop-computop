<?php

namespace Pyz\Zed\ComputopApi\Business\Mapper;

use Pyz\Zed\ComputopApi\Business\Mapper\PostPlace\PayPalExpress\InquirePayPalExpressMapper;
use SprykerEco\Zed\ComputopApi\Business\Mapper\ComputopApiBusinessMapperFactory as EcoComputopApiBusinessMapperFactory;

class ComputopApiBusinessMapperFactory extends EcoComputopApiBusinessMapperFactory
{
    /**
     * @return InquirePayPalExpressMapper
     */
    public function createInquirePayPalExpressMapper(): InquirePayPalExpressMapper
    {
        return new InquirePayPalExpressMapper(
        $this->getComputopApiService(),
            $this->getConfig(),
            $this->getStoreFacade(),
        );
    }
}
