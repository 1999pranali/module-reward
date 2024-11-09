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

class HistoryGrid extends \Coditron\Reward\Controller\Adminhtml\Customer\Reward
{
    /**
     * History Grid Ajax Action
     *
     * @return void
     *
     */
    public function execute()
    {
        $this->_view->loadLayout(false);
        $this->_view->renderLayout();
    }
}
