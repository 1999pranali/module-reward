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

class ScheduledPointsExpiration
{
    /**
     * Reward history factory
     *
     * @var \Coditron\Reward\Model\ResourceModel\Reward\HistoryFactory
     */
    protected $_historyItemFactory;

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
     * @param \Coditron\Reward\Model\ResourceModel\Reward\HistoryFactory $_historyItemFactory
     */
    public function __construct(
        \Coditron\Reward\Helper\Data $rewardData,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Coditron\Reward\Model\ResourceModel\Reward\HistoryFactory $_historyItemFactory
    ) {
        $this->_rewardData = $rewardData;
        $this->_storeManager = $storeManager;
        $this->_historyItemFactory = $_historyItemFactory;
    }

    /**
     * Make points expired
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
            $expiryType = $this->_rewardData->getGeneralConfig('expiry_calculation', $website->getId());
            $this->_historyItemFactory->create()->expirePoints($website->getId(), $expiryType, 100);
        }

        return $this;
    }
}
