<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Block\Adminhtml\Reward\Rate;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Internal constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'rate_id';
        $this->_blockGroup = 'Coditron_Reward';
        $this->_controller = 'adminhtml_reward_rate';
        parent::_construct();
    }

    /**
     * Getter.
     * Return header text in order to create or edit rate
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('current_reward_rate')->getId()) {
            return __('Edit Reward Exchange Rate');
        } else {
            return __('New Reward Exchange Rate');
        }
    }

    /**
     * Get validation url
     *
     * @return string
     */
    public function getValidationUrl()
    {
        return $this->getUrl('adminhtml/*/validate', ['_current' => true]);
    }
}
