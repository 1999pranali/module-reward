<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Block\Tooltip;

class Checkout extends \Coditron\Reward\Block\Tooltip
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Coditron\Reward\Helper\Data $rewardHelper
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Coditron\Reward\Model\Reward $rewardInstance
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Coditron\Reward\Helper\Data $rewardHelper,
        \Magento\Customer\Model\Session $customerSession,
        \Coditron\Reward\Model\Reward $rewardInstance,
        \Magento\Checkout\Model\Session $checkoutSession,
        array $data = []
    ) {
        $this->_checkoutSession = $checkoutSession;
        parent::__construct($context, $rewardHelper, $customerSession, $rewardInstance, $data);
    }

    /**
     * @return $this|\Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->_actionInstance) {
            $this->_actionInstance->setQuote($this->_checkoutSession->getQuote());
        }
        return $this;
    }
}
