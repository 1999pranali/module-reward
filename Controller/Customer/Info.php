<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Controller\Customer;

class Info extends \Coditron\Reward\Controller\Customer
{
    /**
     * Load reward by customer
     *
     * @return \Coditron\Reward\Model\Reward
     */
    protected function _getReward()
    {
        $reward = $this->_objectManager->create(
            \Coditron\Reward\Model\Reward::class
        )->setCustomer(
            $this->_getCustomer()
        )->setWebsiteId(
            $this->_objectManager->get(\Magento\Store\Model\StoreManagerInterface::class)->getStore()->getWebsiteId()
        )->loadByCustomer();
        return $reward;
    }

    /**
     * Info Action
     *
     * @return void
     */
    public function execute()
    {
        $this->_coreRegistry->register('current_reward', $this->_getReward());
        $this->_view->loadLayout();
        $this->_view->getPage()->getConfig()->getTitle()->set(__('Reward Points'));
        $this->_view->renderLayout();
    }
}
