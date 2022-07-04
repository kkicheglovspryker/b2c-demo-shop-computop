<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\CheckoutPage\Process\Steps\SummaryStep as SprykerShopSummaryStep;

class SummaryStep extends SprykerShopSummaryStep
{
    /**
     * @var \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getTemplateVariables(AbstractTransfer $quoteTransfer): array
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $shipmentGroups = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());
        $isPlaceableOrderResponseTransfer = $this->checkoutClient->isPlaceableOrder($quoteTransfer);

        return [
            'quoteTransfer' => $quoteTransfer,
            'cartItems' => $this->productBundleClient->getGroupedBundleItems(
                $quoteTransfer->getItems(),
                $quoteTransfer->getBundleItems()
            ),
            'shipmentGroups' => $this->expandShipmentGroupsWithCartItems($shipmentGroups, $quoteTransfer),
            'totalCosts' => $this->getShipmentTotalCosts($shipmentGroups, $quoteTransfer),
            'isPlaceableOrder' => $isPlaceableOrderResponseTransfer->getIsSuccess(),
            'isPlaceableOrderErrors' => $isPlaceableOrderResponseTransfer->getErrors(),
            'shipmentExpenses' => $this->getShipmentExpenses($quoteTransfer),
            'acceptTermsFieldName' => QuoteTransfer::ACCEPT_TERMS_AND_CONDITIONS,
            'additionalData' => $this->getAdditionalData($quoteTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getAdditionalData(QuoteTransfer $quoteTransfer): array
    {
        if ($quoteTransfer->getPayment()->getPaymentSelection() !== PaymentTransfer::COMPUTOP_EASY_CREDIT) {
            return [];
        }

        $easyCreditStatusResponse = $quoteTransfer->getPayment()
            ->getComputopEasyCredit()
            ->getEasyCreditStatusResponse();

        $financing = $easyCreditStatusResponse->getFinancingData();
        $process = $easyCreditStatusResponse->getProcessData();

        return [
            'installmentPlanMoney' => [
                'Kaufbetrag' => $this->moneyFacade->convertDecimalToInteger($financing['finanzierung']['bestellwert']),
                '+ Zinsen' => $this->moneyFacade->convertDecimalToInteger($financing['ratenplan']['zinsen']['anfallendeZinsen']),
                '= Gesamtbetrag' => $this->moneyFacade->convertDecimalToInteger($financing['ratenplan']['gesamtsumme']),
                'Ihre monatliche Rate' => $this->moneyFacade->convertDecimalToInteger($financing['ratenplan']['zahlungsplan']['betragRate']),
                'letzte Rate' => $this->moneyFacade->convertDecimalToInteger($financing['ratenplan']['zahlungsplan']['betragLetzteRate']),
            ],
            'installmentPlanTax' => [
                'Sollzinssatz p.a. fest für die gesamte Laufzeit' => $financing['ratenplan']['zinsen']['nominalzins'],
                'effektiver Jahreszins' => $financing['ratenplan']['zinsen']['effektivzins'],
            ],
            'installmentText' => $financing['tilgungsplanText'],
            'installmentLink' => $process['allgemeineVorgangsdaten']['urlVorvertraglicheInformationen'],
        ];
    }
}
