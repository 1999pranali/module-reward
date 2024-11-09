<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Block\Adminhtml\Customer\Edit\Tab\Reward;

class Management extends \Magento\Backend\Block\Template
{
    /**
     * Reward management template
     *
     * @var string
     */
    protected $_template = 'customer/edit/management.phtml';

    /**
     * Prepare layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $total = $this->getLayout()->createBlock(
            \Coditron\Reward\Block\Adminhtml\Customer\Edit\Tab\Reward\Management\Balance::class
        );

        $this->setChild('balance', $total);

        $update = $this->getLayout()->createBlock(
            \Coditron\Reward\Block\Adminhtml\Customer\Edit\Tab\Reward\Management\Update::class,
            '',
            [
                'data' => [
                    'target_form' => $this->getData('target_form'),
                ]
            ]
        );

        $this->setChild('update', $update);

        return parent::_prepareLayout();
    }
}
