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

class ReturnRewardPoints implements ObserverInterface
{
    /**
     * Reward factory
     *
     * @var \Coditron\Reward\Model\RewardFactory
     */
    protected $_rewardFactory;

    /**
     * Core model store manager interface
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Coditron\Reward\Model\RewardFactory $rewardFactory
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Coditron\Reward\Model\RewardFactory $rewardFactory
    ) {
        $this->_storeManager = $storeManager;
        $this->_rewardFactory = $rewardFactory;
    }

    /**
     * Return reward points
     *
     * @param   \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getOrder();

        if ($order->getRewardPointsBalance() > 0 && $order->getCustomerId() !== null) {
            $this->_rewardFactory->create()->setCustomerId(
                $order->getCustomerId()
            )->setWebsiteId(
                $this->_storeManager->getStore($order->getStoreId())->getWebsiteId()
            )->setAction(
                \Coditron\Reward\Model\Reward::REWARD_ACTION_REVERT
            )->setPointsDelta(
                $order->getRewardPointsBalance()
            )->setActionEntity(
                $order
            )->updateRewardPoints();
        }
        return $this;
    }
}
