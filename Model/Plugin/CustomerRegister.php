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

use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class CustomerRegister
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

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
     * Customer registry
     *
     * @var \Magento\Customer\Model\CustomerRegistry
     */
    private $customerRegistry;

    /**
     * @param \Coditron\Reward\Helper\Data $rewardData
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Coditron\Reward\Model\RewardFactory $rewardFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Customer\Model\CustomerRegistry $customerRegistry
     */
    public function __construct(
        \Coditron\Reward\Helper\Data $rewardData,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Coditron\Reward\Model\RewardFactory $rewardFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Customer\Model\CustomerRegistry $customerRegistry,
        ManagerInterface $messageManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_rewardData = $rewardData;
        $this->_storeManager = $storeManager;
        $this->_rewardFactory = $rewardFactory;
        $this->_logger = $logger;
        $this->customerRegistry = $customerRegistry;
        $this->_messageManager = $messageManager;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Save reward notification attributes and reward after customer account create
     *
     * @param \Magento\Customer\Model\AccountManagement $subject
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterCreateAccountWithPasswordHash(
        \Magento\Customer\Model\AccountManagement $subject,
        \Magento\Customer\Api\Data\CustomerInterface $customer
    ) {
        if (!$this->_rewardData->isEnabledOnFront()) {
            return $customer;
        }

        $subscribeByDefault = $this->_rewardData->getNotificationConfig(
            'subscribe_by_default',
            $this->_storeManager->getStore()->getWebsiteId()
        );

        try {
            $customerModel = $this->customerRegistry
                ->retrieveByEmail($customer->getEmail());
            $customerModel->setRewardUpdateNotification($subscribeByDefault);
            $customerModel->setRewardWarningNotification($subscribeByDefault);
            $customerModel->getResource()
                ->saveAttribute($customerModel, 'reward_update_notification');
            $customerModel->getResource()
                ->saveAttribute($customerModel, 'reward_warning_notification');

            $this->_rewardFactory->create()->setCustomer(
                $customer
            )->setActionEntity(
                $customer
            )->setStore(
                $this->_storeManager->getStore()->getId()
            )->setAction(
                \Coditron\Reward\Model\Reward::REWARD_ACTION_REGISTER
            )->updateRewardPoints();
            $pointsEarned = $this->scopeConfig->getValue('coditron_reward/points/register', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $this->_messageManager->addSuccessMessage(
                __('Congratulations! You have earned %1 reward points for registration.', $pointsEarned)
            );

        } catch (\Exception $e) {
            //save exception if something went wrong during saving reward
            //and allow to register customer
            $this->_logger->critical($e);
        }

        return $customer;
    }
}
