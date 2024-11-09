<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Cron;

class ScheduledBalanceExpireNotification
{
    /**
     * Reward history factory
     *
     * @var \Coditron\Reward\Model\ResourceModel\Reward\HistoryFactory
     */
    protected $_historyItemFactory;

    /**
     * Reward history collection
     *
     * @var \Coditron\Reward\Model\ResourceModel\Reward\History\CollectionFactory
     */
    protected $_historyCollectionFactory;

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
     * @param \Coditron\Reward\Model\ResourceModel\Reward\History\CollectionFactory $_historyCollectionFactory
     * @param \Coditron\Reward\Model\ResourceModel\Reward\HistoryFactory $_historyItemFactory
     */
    public function __construct(
        \Coditron\Reward\Helper\Data $rewardData,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Coditron\Reward\Model\RewardFactory $rewardFactory,
        \Coditron\Reward\Model\ResourceModel\Reward\History\CollectionFactory $_historyCollectionFactory,
        \Coditron\Reward\Model\ResourceModel\Reward\HistoryFactory $_historyItemFactory
    ) {
        $this->_rewardData = $rewardData;
        $this->_storeManager = $storeManager;
        $this->_rewardFactory = $rewardFactory;
        $this->_historyCollectionFactory = $_historyCollectionFactory;
        $this->_historyItemFactory = $_historyItemFactory;
    }

    /**
     * Send scheduled low balance warning notifications
     *
     * @return $this
     */
    public function execute()
    {
        if (!$this->_rewardData->isEnabled()) {
            return $this;
        }

        foreach ($this->_storeManager->getWebsites() as $website) {
            if (!$this->_rewardData->isEnabledOnFront($website->getId())) {
                continue;
            }
            $inDays = (int)$this->_rewardData->getNotificationConfig('expiry_day_before');
            if (!$inDays) {
                continue;
            }
            $collection = $this->_historyCollectionFactory->create()->setExpiryConfig(
                $this->_rewardData->getExpiryConfig()
            )->loadExpiredSoonPoints(
                $website->getId(),
                true
            )->addNotificationSentFlag(
                false
            )->addCustomerInfo()->setPageSize(
                20
            )->setCurPage(
                1
            )->load();

            foreach ($collection as $item) {
                $this->_rewardFactory->create()->sendBalanceWarningNotification($item, $website->getId());
            }

            // mark records as sent
            $historyIds = $collection->getExpiredSoonIds();
            $this->_historyItemFactory->create()->markAsNotified($historyIds);
        }

        return $this;
    }
}
