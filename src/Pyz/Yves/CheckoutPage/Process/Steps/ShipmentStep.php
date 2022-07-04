<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use SprykerEco\Client\Computop\ComputopClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface;
use SprykerShop\Yves\CheckoutPage\GiftCard\GiftCardItemsCheckerInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\ShipmentStep as SprykerShipmentStep;
use Symfony\Component\HttpFoundation\Request;

class ShipmentStep extends SprykerShipmentStep
{
    /**
     * @var \SprykerEco\Client\Computop\ComputopClientInterface
     */
    protected $computopClient;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface $calculationClient
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection $shipmentPlugins
     * @param \SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface $postConditionChecker
     * @param \SprykerShop\Yves\CheckoutPage\GiftCard\GiftCardItemsCheckerInterface $giftCardItemsChecker
     * @param string $stepRoute
     * @param string|null $escapeRoute
     * @param \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutShipmentStepEnterPreCheckPluginInterface[] $checkoutShipmentStepEnterPreCheckPlugins
     * @param \SprykerEco\Client\Computop\ComputopClientInterface $computopClient
     */
    public function __construct(
        CheckoutPageToCalculationClientInterface $calculationClient,
        StepHandlerPluginCollection $shipmentPlugins,
        PostConditionCheckerInterface $postConditionChecker,
        GiftCardItemsCheckerInterface $giftCardItemsChecker,
                                                 $stepRoute,
                                                 $escapeRoute,
        array $checkoutShipmentStepEnterPreCheckPlugins,
        ComputopClientInterface $computopClient
    ) {
        parent::__construct(
            $calculationClient,
            $shipmentPlugins,
            $postConditionChecker,
            $giftCardItemsChecker,
            $stepRoute,
            $escapeRoute,
            $checkoutShipmentStepEnterPreCheckPlugins
        );

        $this->computopClient = $computopClient;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemEnabled(AbstractTransfer $quoteTransfer): bool
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        return !$quoteTransfer->getDefaultShipmentSelected();
    }

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
        $quoteTransfer->setDefaultShipmentSelected(false);

        return $this->computopClient->performCrifApiCall($quoteTransfer);
    }
}
