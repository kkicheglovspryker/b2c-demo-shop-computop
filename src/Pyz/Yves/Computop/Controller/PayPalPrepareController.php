<?php

namespace Pyz\Yves\Computop\Controller;

use Generated\Shared\Transfer\ComputopPayPalExpressPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerEco\Client\ComputopApi\ComputopApiClient;

class PayPalPrepareController extends AbstractController
{
    public function prepareAction()
    {
        /** @var QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();

        $ppExpress = (new ComputopPayPalExpressPaymentTransfer())
            ->setLen($quoteTransfer->getPayment()->getComputopPayPal()->getLen())
            ->setData($quoteTransfer->getPayment()->getComputopPayPal()->getData());

        $quoteTransfer->getPayment()->setComputopPayPalExpress($ppExpress);
        $quoteTransfer = (new ComputopApiClient())->sendPayPalExpressPrepareRequest($quoteTransfer);

        return $this->redirectResponseExternal($quoteTransfer->getPayment()->getComputopPayPalExpress()->getPayPalExpressPrepareResponse()->getPaypalurl());
    }
}
