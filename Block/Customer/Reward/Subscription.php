<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Block\Customer\Reward;

class Subscription extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param array $data
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
        $this->_customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    /**
     * Getter for RewardUpdateNotification
     *
     * @return bool
     */
    public function isSubscribedForUpdates()
    {
        return $this->_getCustomer() ? (bool)$this->_getCustomer()->getRewardUpdateNotification() : false;
    }

    /**
     * Getter for RewardWarningNotification
     *
     * @return bool
     */
    public function isSubscribedForWarnings()
    {
        return $this->_getCustomer() ? (bool)$this->_getCustomer()->getRewardWarningNotification() : false;
    }

    /**
     * Retrieve customer model
     *
     * @return \Magento\Customer\Model\Customer
     * @codeCoverageIgnore
     */
    protected function _getCustomer()
    {
        return $this->_customerSession->getCustomer();
    }
}
