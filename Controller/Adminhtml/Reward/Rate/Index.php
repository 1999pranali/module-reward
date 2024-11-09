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

/**
 * @codeCoverageIgnore
 */
class Index extends \Coditron\Reward\Controller\Adminhtml\Reward\Rate implements HttpGetActionInterface
{
    /**
     * Index Action
     *
     * @return void
     */
    public function execute()
    {
        $this->_initAction();
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Reward Exchange Rates'));
        $this->_view->renderLayout();
    }
}
