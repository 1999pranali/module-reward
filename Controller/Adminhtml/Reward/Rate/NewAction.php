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

class NewAction extends \Coditron\Reward\Controller\Adminhtml\Reward\Rate
{
    /**
     * New Action.
     * Forward to Edit Action
     *
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
