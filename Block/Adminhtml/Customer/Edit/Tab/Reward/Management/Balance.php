<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Block\Adminhtml\Customer\Edit\Tab\Reward\Management;

class Balance extends \Magento\Backend\Block\Template
{
    /**
     * Reward balance management template
     *
     * @var string
     */
    protected $_template = 'customer/edit/management/balance.phtml';

    /**
     * Prepare layout.
     * Create balance grid block
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        if (!$this->_authorization->isAllowed(\Coditron\Reward\Helper\Data::XML_PATH_PERMISSION_BALANCE)) {
            // unset template to get empty output
        } else {
            $grid = $this->getLayout()->createBlock(
                \Coditron\Reward\Block\Adminhtml\Customer\Edit\Tab\Reward\Management\Balance\Grid::class
            );
            $this->setChild('grid', $grid);
        }
        return parent::_prepareLayout();
    }
}
