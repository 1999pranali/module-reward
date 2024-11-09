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

class RevertRewardPointsForAllOrders implements ObserverInterface
{
    /**
     * @var \Coditron\Reward\Model\Reward\Reverter
     */
    protected $rewardReverter;

    /**
     * @param \Coditron\Reward\Model\Reward\Reverter $reverter
     */
    public function __construct(\Coditron\Reward\Model\Reward\Reverter $reverter)
    {
        $this->rewardReverter = $reverter;
    }

    /**
     * Revert authorized reward points amounts for all orders
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $orders = $observer->getEvent()->getOrders();

        foreach ($orders as $order) {
            $this->rewardReverter->revertRewardPointsForOrder($order);
        }

        return $this;
    }
}
