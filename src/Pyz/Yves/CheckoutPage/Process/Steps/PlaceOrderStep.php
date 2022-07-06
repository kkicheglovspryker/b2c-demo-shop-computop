<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PlaceOrderStep as SprykerShopPlaceOrderStep;
use Symfony\Component\HttpFoundation\Request;

class PlaceOrderStep extends SprykerShopPlaceOrderStep
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer = parent::execute($request, $quoteTransfer);
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        if ($quoteTransfer->getPayment()->getPaymentSelection() !== ComputopConfig::PAYMENT_METHOD_PAY_NOW) {
            return $quoteTransfer;
        }

        $computopPaymentTransfer = $quoteTransfer->getPayment()->getComputopPayNow();
        $computopPaymentTransfer
            ->setData($this->checkoutResponseTransfer->getComputopInitPayment()->getData())
            ->setLen($this->checkoutResponseTransfer->getComputopInitPayment()->getLen());
        $quoteTransfer->getPayment()->setComputopPayNow($computopPaymentTransfer);

        return $quoteTransfer;
    }
}
