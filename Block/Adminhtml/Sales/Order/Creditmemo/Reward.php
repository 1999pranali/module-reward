<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Block\Adminhtml\Sales\Order\Creditmemo;

class Reward extends \Magento\Backend\Block\Template
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Coditron\Reward\Helper\Data
     */
    protected $rewardData;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Coditron\Reward\Helper\Data $rewardData
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Coditron\Reward\Helper\Data $rewardData,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->rewardData = $rewardData;
        parent::__construct($context, $data);
    }

    /**
     * Getter
     *
     * @return \Magento\Sales\Model\Order\Creditmemo
     */
    public function getCreditmemo()
    {
        return $this->_coreRegistry->registry('current_creditmemo');
    }

    /**
     * Check whether can refund reward points to customer
     *
     * @return bool
     */
    public function canRefundRewardPoints()
    {
        if ($this->getCreditmemo()->getOrder()->getCustomerIsGuest()) {
            return false;
        }
        if ($this->getCreditmemo()->getOrder()->getRewardCurrencyAmount() <= 0) {
            return false;
        }
        return true;
    }

    /**
     * Return maximum points balance to refund
     *
     * @return integer
     */
    public function getRefundRewardPointsBalance()
    {
        return (int)$this->getCreditmemo()->getOrder()->getRewardPointsBalance();
    }

    /**
     * Return automatic refund of reward points option value
     *
     * @return bool
     */
    public function isAutoRefundEnabled()
    {
        return $this->rewardData->isAutoRefundEnabled();
    }
}
