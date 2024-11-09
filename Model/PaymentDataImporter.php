<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Model;

class PaymentDataImporter
{
    /**
     * Core model store configuration
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Reward factory
     *
     * @var \Coditron\Reward\Model\RewardFactory
     */
    protected $_rewardFactory;

    /**
     * @var \Magento\Framework\Api\ExtensibleDataObjectConverter
     */
    protected $extensibleDataObjectConverter;

    /**
     * @param RewardFactory $rewardFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Api\ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        \Coditron\Reward\Model\RewardFactory $rewardFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Api\ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_rewardFactory = $rewardFactory;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * Prepare and set to quote reward balance instance,
     * set zero subtotal checkout payment if need
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Framework\DataObject $payment
     * @param bool $useRewardPoints
     * @return $this
     */
    public function import($quote, $payment, $useRewardPoints)
    {
        if (!$quote ||
            !$quote->getCustomerId() ||
            $quote->getBaseGrandTotal() + $quote->getBaseRewardCurrencyAmount() <= 0
        ) {
            return $this;
        }
        $quote->setUseRewardPoints((bool)$useRewardPoints);
        if ($quote->getUseRewardPoints()) {
            $customer = $quote->getCustomer();
            /* @var $reward \Coditron\Reward\Model\Reward */
            $reward = $this->_rewardFactory->create()->setCustomer($customer);
            $reward->setWebsiteId($quote->getStore()->getWebsiteId());
            $reward->loadByCustomer();
            $minPointsBalance = (int)$this->_scopeConfig->getValue(
                \Coditron\Reward\Model\Reward::XML_PATH_MIN_POINTS_BALANCE,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $quote->getStoreId()
            );

            if ($reward->getId() && $reward->getPointsBalance() >= $minPointsBalance) {
                $quote->setRewardInstance($reward);
                if (!$payment->getMethod()) {
                    $payment->setMethod('free');
                }
            } else {
                $quote->setUseRewardPoints(false);
            }
        }
        return $this;
    }
}
