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
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class CustomerSubscribed implements ObserverInterface
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
        \Coditron\Reward\Model\RewardFactory $rewardFactory,
        ManagerInterface $messageManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_rewardData = $rewardData;
        $this->_storeManager = $storeManager;
        $this->_rewardFactory = $rewardFactory;
        $this->_messageManager = $messageManager;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Update points balance after first successful subscribtion
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /* @var $subscriber \Magento\Newsletter\Model\Subscriber */
        $subscriber = $observer->getEvent()->getSubscriber();
        // reward only new subscribtions
        if (!$subscriber->isObjectNew() || !$subscriber->getCustomerId()) {
            return $this;
        }
        $websiteId = $this->_storeManager->getStore($subscriber->getStoreId())->getWebsiteId();
        if (!$this->_rewardData->isEnabledOnFront($websiteId)) {
            return $this;
        }

        $reward = $this->_rewardFactory->create()->setCustomerId(
            $subscriber->getCustomerId()
        )->setStore(
            $subscriber->getStoreId()
        )->setAction(
            \Coditron\Reward\Model\Reward::REWARD_ACTION_NEWSLETTER
        )->setActionEntity(
            $subscriber
        )->updateRewardPoints();

        $pointsEarned = $this->scopeConfig->getValue('coditron_reward/points/newsletter', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->_messageManager->addSuccessMessage(
            __('Congratulations! You have earned %1 reward points for subscribing to our newsletter.', $pointsEarned)
        );
        return $this;
    }
}
