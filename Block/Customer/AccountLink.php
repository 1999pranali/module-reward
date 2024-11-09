<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Block\Customer;

use Magento\Customer\Block\Account\SortLinkInterface;

class AccountLink extends \Magento\Framework\View\Element\Html\Link\Current implements SortLinkInterface
{
    /**
     * @var \Coditron\Reward\Helper\Data
     */
    protected $_rewardHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\DefaultPathInterface $defaultPath
     * @param \Coditron\Reward\Helper\Data $rewardHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\DefaultPathInterface $defaultPath,
        \Coditron\Reward\Helper\Data $rewardHelper,
        array $data = []
    ) {
        parent::__construct($context, $defaultPath, $data);
        $this->_rewardHelper = $rewardHelper;
    }

    /**
     * Render block HTML
     *
     * @inheritdoc
     * @return string
     */
    protected function _toHtml()
    {
        return $this->_rewardHelper->isEnabledOnFront() ? parent::_toHtml() : '';
    }

    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }
}
