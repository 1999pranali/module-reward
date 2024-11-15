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

class SaveSettings extends \Coditron\Reward\Controller\Customer
{
    /**
     * Save settings
     *
     * @return void
     */
    public function execute()
    {
        if (!$this->_formKeyValidator->validate($this->getRequest())) {
            return $this->_redirect('*/*/info');
        }

        $customer = $this->_getCustomer();
        if ($customer->getId()) {
            $customer->setRewardUpdateNotification(
                $this->getRequest()->getParam('subscribe_updates')
            )->setRewardWarningNotification(
                $this->getRequest()->getParam('subscribe_warnings')
            );
            $customer->getResource()->saveAttribute($customer, 'reward_update_notification');
            $customer->getResource()->saveAttribute($customer, 'reward_warning_notification');

            $this->messageManager->addSuccess(__('You saved the settings.'));
        }
        $this->_redirect('*/*/info');
    }
}
