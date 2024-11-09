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

class CheckRates implements ObserverInterface
{
    /**
     * Reward rate factory
     *
     * @var \Coditron\Reward\Model\Reward\RateFactory
     */
    protected $_rateFactory;

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
     * @param \Coditron\Reward\Model\Reward\RateFactory $rateFactory
     */
    public function __construct(
        \Coditron\Reward\Helper\Data $rewardData,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Coditron\Reward\Model\Reward\RateFactory $rateFactory
    ) {
        $this->_rewardData = $rewardData;
        $this->_storeManager = $storeManager;
        $this->_rateFactory = $rateFactory;
    }

    /**
     * If not all rates found, we should disable reward points on frontend
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_rewardData->isEnabledOnFront()) {
            return $this;
        }

        $groupId = $observer->getEvent()->getCustomerSession()->getCustomerGroupId();
        $websiteId = $this->_storeManager->getStore()->getWebsiteId();

        $rate = $this->_rateFactory->create();

        $hasRates = $rate->fetch(
            $groupId,
            $websiteId,
            \Coditron\Reward\Model\Reward\Rate::RATE_EXCHANGE_DIRECTION_TO_CURRENCY
        )->getId() && $rate->reset()->fetch(
            $groupId,
            $websiteId,
            \Coditron\Reward\Model\Reward\Rate::RATE_EXCHANGE_DIRECTION_TO_POINTS
        )->getId();
        $this->_rewardData->setHasRates($hasRates);

        return $this;
    }
}
