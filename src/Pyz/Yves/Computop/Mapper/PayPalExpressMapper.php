<?php

namespace Pyz\Yves\Computop\Mapper;

use Generated\Shared\Transfer\ComputopPayPalExpressPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Router\Router\Router;
use SprykerEco\Yves\Computop\Mapper\Init\PostPlace\PayPalExpressMapper as EcoPayPalExpressMapperAlias;
use SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin;

class PayPalExpressMapper extends EcoPayPalExpressMapperAlias
{
    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return ComputopPayPalExpressPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(QuoteTransfer $quoteTransfer): ComputopPayPalExpressPaymentTransfer
    {
        $computopPaymentTransfer = parent::createTransferWithUnencryptedValues($quoteTransfer);

        $computopPaymentTransfer->setUrlFailure(
            $this->router->generate(ComputopRouteProviderPlugin::ROUTE_NAME_PAY_PAL_EXPRESS_PLACE_ORDER, [], Router::ABSOLUTE_URL),
        );

        return $computopPaymentTransfer;
    }
}
