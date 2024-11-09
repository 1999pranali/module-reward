<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Controller\Adminhtml\Reward\Rate;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;

class Edit extends \Coditron\Reward\Controller\Adminhtml\Reward\Rate implements HttpGetActionInterface
{
    /**
     * Edit Action
     *
     * @return void
     */
    public function execute()
    {
        $rate = $this->_initRate();
        $this->_initAction();
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Reward Exchange Rates'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(
            $rate->getRateId() ? sprintf("#%s", $rate->getRateId()) : __('New Reward Exchange Rate')
        );
        $this->_view->renderLayout();
    }
}
