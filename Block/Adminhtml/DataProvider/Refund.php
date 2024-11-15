<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

declare(strict_types=1);

namespace Coditron\Reward\Block\Adminhtml\DataProvider;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Sales\Api\Data\CreditmemoInterface;

class Refund implements ArgumentInterface
{
    /**
     * Checks if refund Reward Points is checked.
     *
     * @param CreditmemoInterface $creditmemo
     * @return bool
     */
    public function isRefundRewardBalanceChecked(CreditmemoInterface $creditmemo): bool
    {
        return (bool) $creditmemo->getRewardPointsBalanceRefundFlag();
    }
}
