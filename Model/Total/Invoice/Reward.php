<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Model\Total\Invoice;

use Magento\Sales\Model\Order\Invoice;

class Reward extends \Magento\Sales\Model\Order\Invoice\Total\AbstractTotal
{
    /**
     * Collect reward total for invoice
     *
     * @param Invoice $invoice
     * @return $this
     */
    public function collect(Invoice $invoice)
    {
        $order = $invoice->getOrder();
        $rewardCurrencyAmountLeft = $order->getRewardCurrencyAmount() - $order->getRwrdCurrencyAmountInvoiced();
        $baseRewardCurrencyAmountLeft = $order->getBaseRewardCurrencyAmount() - $order->getBaseRwrdCrrncyAmtInvoiced();
        if ($order->getBaseRewardCurrencyAmount() && $baseRewardCurrencyAmountLeft > 0) {
            if ($baseRewardCurrencyAmountLeft < $invoice->getBaseGrandTotal()) {
                $invoice->setGrandTotal($invoice->getGrandTotal() - $rewardCurrencyAmountLeft);
                $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() - $baseRewardCurrencyAmountLeft);
            } else {
                $rewardCurrencyAmountLeft = $invoice->getGrandTotal();
                $baseRewardCurrencyAmountLeft = $invoice->getBaseGrandTotal();

                $invoice->setGrandTotal(0);
                $invoice->setBaseGrandTotal(0);
            }
            $pointValue = $order->getRewardPointsBalance() / $order->getBaseRewardCurrencyAmount();
            $rewardPointsBalance = $baseRewardCurrencyAmountLeft * ceil($pointValue);
            $rewardPointsBalanceLeft = $order->getRewardPointsBalance() - $order->getRewardPointsBalanceInvoiced();
            if ($rewardPointsBalance > $rewardPointsBalanceLeft) {
                $rewardPointsBalance = $rewardPointsBalanceLeft;
            }
            $invoice->setRewardPointsBalance(round($rewardPointsBalance));
            $invoice->setRewardCurrencyAmount($rewardCurrencyAmountLeft);
            $invoice->setBaseRewardCurrencyAmount($baseRewardCurrencyAmountLeft);
        }
        return $this;
    }
}
