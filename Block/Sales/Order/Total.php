<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Block\Sales\Order;

class Total extends \Magento\Framework\View\Element\Template
{
    /**
     * Reward data
     *
     * @var \Coditron\Reward\Helper\Data
     */
    protected $_rewardData = null;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Coditron\Reward\Helper\Data $rewardData
     * @param array $data
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Coditron\Reward\Helper\Data $rewardData,
        array $data = []
    ) {
        $this->_rewardData = $rewardData;
        parent::__construct($context, $data);
    }

    /**
     * Get label cell tag properties
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getLabelProperties()
    {
        return $this->getParentBlock()->getLabelProperties();
    }

    /**
     * Get order store object
     *
     * @return \Magento\Sales\Model\Order
     * @codeCoverageIgnore
     */
    public function getOrder()
    {
        return $this->getParentBlock()->getOrder();
    }

    /**
     * Get totals source object
     *
     * @return \Magento\Sales\Model\Order
     * @codeCoverageIgnore
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    /**
     * Get value cell tag properties
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getValueProperties()
    {
        return $this->getParentBlock()->getValueProperties();
    }

    /**
     * Initialize reward points totals
     *
     * @return $this
     */
    public function initTotals()
    {
        if ((double)$this->getOrder()->getBaseRewardCurrencyAmount()) {
            $source = $this->getSource();
            $value = -$source->getRewardCurrencyAmount();

            $this->getParentBlock()->addTotal(
                new \Magento\Framework\DataObject(
                    [
                        'code' => 'reward_points',
                        'strong' => false,
                        'label' => $this->_rewardData->formatReward($source->getRewardPointsBalance()),
                        'value' => $source instanceof \Magento\Sales\Model\Order\Creditmemo ? -$value : $value,
                    ]
                )
            );
        }

        return $this;
    }
}
