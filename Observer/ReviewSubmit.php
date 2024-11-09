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

class ReviewSubmit implements ObserverInterface
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
     * Reward helper
     *
     * @var \Coditron\Reward\Helper\Data
     */
    protected $_rewardData;

    /**
     * @param \Coditron\Reward\Helper\Data $rewardData
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Coditron\Reward\Model\RewardFactory $rewardFactory
     */
    public function __construct(
        \Coditron\Reward\Helper\Data $rewardData,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Coditron\Reward\Model\RewardFactory $rewardFactory
    ) {
        $this->_rewardData = $rewardData;
        $this->_storeManager = $storeManager;
        $this->_rewardFactory = $rewardFactory;
    }

    /**
     * Update points balance after review submit
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /* @var $review \Magento\Review\Model\Review */
        $review = $observer->getEvent()->getObject();
        $storeId = $review->getStoreId() ?: $this->_storeManager->getStore()->getId();
        $websiteId = $storeId ? $this->_storeManager->getStore($storeId)->getWebsiteId()
            : $this->_storeManager->getStore()->getWebsiteId();
        if (!$this->_rewardData->isEnabledOnFront($websiteId)) {
            return $this;
        }
        if ($review->isApproved() && $review->getCustomerId()) {
            /* @var $reward \Coditron\Reward\Model\Reward */
            $reward = $this->_rewardFactory->create()->setCustomerId(
                $review->getCustomerId()
            )->setStore(
                $storeId
            )->setAction(
                \Coditron\Reward\Model\Reward::REWARD_ACTION_REVIEW
            )->setActionEntity(
                $review
            )->updateRewardPoints();
        }
        return $this;
    }
}
