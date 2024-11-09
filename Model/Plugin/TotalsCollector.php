<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Model\Plugin;

use Magento\Quote\Model\Quote;

class TotalsCollector
{
    /**
     * Reset quote reward point amount
     *
     * @param \Magento\Quote\Model\Quote\TotalsCollector $subject
     * @param Quote $quote
     *
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeCollect(
        \Magento\Quote\Model\Quote\TotalsCollector $subject,
        Quote $quote
    ) {
        $quote->setRewardPointsBalance(0);
        $quote->setRewardCurrencyAmount(0);
        $quote->setBaseRewardCurrencyAmount(0);
    }
}
