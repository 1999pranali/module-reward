<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Observer;

use Magento\Framework\Event\ObserverInterface;

class InvoiceRegister implements ObserverInterface
{
    /**
     * Set invoiced reward amount to order after invoice register
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /* @var $invoice \Magento\Sales\Model\Order\Invoice */
        $invoice = $observer->getEvent()->getInvoice();
        if ($invoice->getBaseRewardCurrencyAmount()) {
            $order = $invoice->getOrder();
            $order->setRwrdCurrencyAmountInvoiced(
                $order->getRwrdCurrencyAmountInvoiced() + $invoice->getRewardCurrencyAmount()
            );
            $order->setBaseRwrdCrrncyAmtInvoiced(
                $order->getBaseRwrdCrrncyAmtInvoiced() + $invoice->getBaseRewardCurrencyAmount()
            );
        }

        return $this;
    }
}
