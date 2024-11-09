<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Model\Reward;

use Magento\Framework\App\ObjectManager;
use Coditron\Reward\Model\SalesRule\RewardPointCounter;

class Reverter
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
     * @var \Coditron\Reward\Model\ResourceModel\RewardFactory
     * @deprecated 101.0.0 since it is not used in the class anymore
     */
    protected $rewardResourceFactory;

    /**
     * @var RewardPointCounter
     */
    private $rewardPointCounter;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Coditron\Reward\Model\RewardFactory $rewardFactory
     * @param \Coditron\Reward\Model\ResourceModel\RewardFactory $rewardResourceFactory
     * @param RewardPointCounter|null $rewardPointCounter
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Coditron\Reward\Model\RewardFactory $rewardFactory,
        \Coditron\Reward\Model\ResourceModel\RewardFactory $rewardResourceFactory,
        RewardPointCounter $rewardPointCounter = null
    ) {
        $this->_storeManager = $storeManager;
        $this->_rewardFactory = $rewardFactory;
        $this->rewardResourceFactory = $rewardResourceFactory;
        $this->rewardPointCounter = $rewardPointCounter ?: ObjectManager::getInstance()->get(RewardPointCounter::class);
    }

    /**
     * Revert authorized reward points amount for order
     *
     * @param \Magento\Sales\Model\Order $order
     * @return $this
     */
    public function revertRewardPointsForOrder(\Magento\Sales\Model\Order $order)
    {
        if (!$order->getCustomerId()) {
            return $this;
        }
        $this->_rewardFactory->create()->setCustomerId(
            $order->getCustomerId()
        )->setWebsiteId(
            $this->_storeManager->getStore($order->getStoreId())->getWebsiteId()
        )->setPointsDelta(
            $order->getRewardPointsBalance()
        )->setAction(
            \Coditron\Reward\Model\Reward::REWARD_ACTION_REVERT
        )->setActionEntity(
            $order
        )->updateRewardPoints();

        return $this;
    }

    /**
     * Revert sales rule earned reward points for order.
     *
     * @param \Magento\Sales\Model\Order $order
     * @return $this
     */
    public function revertEarnedRewardPointsForOrder(\Magento\Sales\Model\Order $order)
    {
        $appliedRuleIds = array_unique(explode(',', $order->getAppliedRuleIds()));
        $pointsDelta = $this->rewardPointCounter->getPointsForRules($appliedRuleIds);

        if ($pointsDelta && !$order->getCustomerIsGuest()) {
            $reward = $this->_rewardFactory->create();
            $reward->setCustomerId(
                $order->getCustomerId()
            )->setWebsiteId(
                $this->_storeManager->getStore($order->getStoreId())->getWebsiteId()
            )->setPointsDelta(
                -$pointsDelta
            )->setAction(
                \Coditron\Reward\Model\Reward::REWARD_ACTION_REVERT
            )->setActionEntity(
                $order
            )->updateRewardPoints();
        }

        return $this;
    }
}
