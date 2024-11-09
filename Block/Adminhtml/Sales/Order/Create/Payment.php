<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Block\Adminhtml\Sales\Order\Create;

class Payment extends \Magento\Backend\Block\Template
{
    /**
     * Reward data
     *
     * @var \Coditron\Reward\Helper\Data
     */
    protected $_rewardData = null;

    /**
     * @var \Magento\Sales\Model\AdminOrder\Create
     */
    protected $_orderCreate;

    /**
     * @var \Coditron\Reward\Model\RewardFactory
     */
    protected $_rewardFactory;

    /**
     * @var \Magento\Framework\Api\ExtensibleDataObjectConverter
     */
    protected $extensibleDataObjectConverter;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Coditron\Reward\Helper\Data $rewardData
     * @param \Magento\Sales\Model\AdminOrder\Create $orderCreate
     * @param \Coditron\Reward\Model\RewardFactory $rewardFactory
     * @param \Magento\Framework\Api\ExtensibleDataObjectConverter $extensibleDataObjectConverter
     * @param array $data
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Coditron\Reward\Helper\Data $rewardData,
        \Magento\Sales\Model\AdminOrder\Create $orderCreate,
        \Coditron\Reward\Model\RewardFactory $rewardFactory,
        \Magento\Framework\Api\ExtensibleDataObjectConverter $extensibleDataObjectConverter,
        array $data = []
    ) {
        $this->_rewardData = $rewardData;
        $this->_orderCreate = $orderCreate;
        $this->_rewardFactory = $rewardFactory;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
        parent::__construct($context, $data);
    }

    /**
     * Getter
     *
     * @return \Magento\Quote\Model\Quote
     * @codeCoverageIgnore
     */
    public function getQuote()
    {
        return $this->_orderCreate->getQuote();
    }

    /**
     * Check whether can use customer reward points
     *
     * @return bool
     */
    public function canUseRewardPoints()
    {
        $websiteId = $this->_storeManager->getStore($this->getQuote()->getStoreId())->getWebsiteId();
        $minPointsBalance = (int)$this->_scopeConfig->getValue(
            \Coditron\Reward\Model\Reward::XML_PATH_MIN_POINTS_BALANCE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getQuote()->getStoreId()
        );

        return $this->getReward()->getPointsBalance() >= $minPointsBalance
        && $this->_rewardData->isEnabledOnFront(
            $websiteId
        )
        && $this->_authorization->isAllowed(
            \Coditron\Reward\Helper\Data::XML_PATH_PERMISSION_AFFECT
        )
        && (double)$this->getCurrencyAmount()
        && $this->getQuote()->getBaseGrandTotal() + $this->getQuote()->getBaseRewardCurrencyAmount() > 0;
    }

    /**
     * Getter.
     * Retrieve reward points model
     *
     * @return \Coditron\Reward\Model\Reward
     */
    public function getReward()
    {
        if (!$this->_getData('reward')) {
            $customer = $this->getQuote()->getCustomer();
            /* @var $reward \Coditron\Reward\Model\Reward */
            $reward = $this->_rewardFactory->create()->setCustomer($customer);
            $reward->setStore($this->getQuote()->getStore());
            $reward->loadByCustomer();
            $this->setData('reward', $reward);
        }
        return $this->_getData('reward');
    }

    /**
     * Prepare some template data
     *
     * @return string
     * @codeCoverageIgnore
     */
    protected function _toHtml()
    {
        $points = $this->getReward()->getPointsBalance();
        $amount = $this->getReward()->getCurrencyAmount();
        $rewardFormatted = $this->_rewardData->formatReward($points, $amount, $this->getQuote()->getStore()->getId());
        $this->setPointsBalance(
            $points
        )->setCurrencyAmount(
            $amount
        )->setUseLabel(
            __('Use your reward points; %1 are available.', $rewardFormatted)
        );
        return parent::_toHtml();
    }

    /**
     * Check if reward points applied in quote
     *
     * @return bool
     * @codeCoverageIgnore
     */
    public function useRewardPoints()
    {
        return (bool)$this->_orderCreate->getQuote()->getUseRewardPoints();
    }
}
