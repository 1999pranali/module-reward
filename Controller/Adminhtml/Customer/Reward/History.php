<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Controller\Adminhtml\Customer\Reward;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Coditron\Reward\Controller\Adminhtml\Customer\Reward as RewardAction;

class History extends RewardAction implements HttpPostActionInterface
{
    /**
     * History Ajax Action
     *
     * @return void
     */
    public function execute()
    {
        $this->_view->loadLayout(false);
        $this->_view->renderLayout();
    }
}
