<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Model\Reward\Balance;

use Magento\Sales\Model\Order;
use Magento\Framework\Exception\PaymentException;

class Validator
{
    /**
     * Reward factory
     *
     * @var \Coditron\Reward\Model\RewardFactory
     */
    protected $_modelFactory;

    /**
     * Core model store manager interface
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Checkout session model
     *
     * @var \Magento\Checkout\Model\Session
     */
    protected $_session;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Coditron\Reward\Model\RewardFactory $modelFactory
     * @param \Magento\Checkout\Model\Session $session
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Coditron\Reward\Model\RewardFactory $modelFactory,
        \Magento\Checkout\Model\Session $session
    ) {
        $this->_storeManager = $storeManager;
        $this->_modelFactory = $modelFactory;
        $this->_session = $session;
    }

    /**
     * Check reward points balance
     *
     * @param Order $order
     * @return void
     * @throws \Magento\Framework\Exception\PaymentException
     */
    public function validate(Order $order)
    {
        if ($order->getRewardPointsBalance() > 0) {
            $websiteId = $this->_storeManager->getStore($order->getStoreId())->getWebsiteId();
            /* @var $reward \Coditron\Reward\Model\Reward */
            $reward = $this->_modelFactory->create();
            $reward->setCustomerId($order->getCustomerId());
            $reward->setWebsiteId($websiteId);
            $reward->loadByCustomer();

            if ($order->getRewardPointsBalance() - $reward->getPointsBalance() >= 0.0001) {
                $this->_session->setUpdateSection('payment-method');
                $this->_session->setGotoSection('payment');
                throw new PaymentException(__('You don\'t have enough reward points to pay for this purchase.'));
            }
        }
    }
}
