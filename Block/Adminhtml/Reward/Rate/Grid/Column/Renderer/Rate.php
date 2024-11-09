<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Block\Adminhtml\Reward\Rate\Grid\Column\Renderer;

use Magento\Backend\Block\Context;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Rate Column
 */
class Rate extends AbstractRenderer
{
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Coditron\Reward\Model\Reward\Rate
     */
    protected $_rate;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param \Coditron\Reward\Model\Reward\Rate $rate
     * @param array $data
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        \Coditron\Reward\Model\Reward\Rate $rate,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        $this->_rate = $rate;
        parent::__construct($context, $data);
    }

    /**
     * Renders grid column
     *
     * @param DataObject $row
     *
     * @return string
     */
    public function render(DataObject $row)
    {
        $websiteId = $row->getWebsiteId();
        return $this->_rate->getRateText(
            $row->getDirection(),
            $row->getPoints(),
            $row->getCurrencyAmount(),
            $this->_storeManager->getWebsite($websiteId)->getBaseCurrencyCode()
        );
    }
}
