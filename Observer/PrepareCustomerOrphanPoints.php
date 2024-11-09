<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Observer;

use Magento\Framework\Event\ObserverInterface;

class PrepareCustomerOrphanPoints implements ObserverInterface
{
    /**
     * Reward factory
     *
     * @var \Coditron\Reward\Model\RewardFactory
     */
    protected $_rewardFactory;

    /**
     * @param \Coditron\Reward\Model\RewardFactory $rewardFactory
     */
    public function __construct(
        \Coditron\Reward\Model\RewardFactory $rewardFactory
    ) {
        $this->_rewardFactory = $rewardFactory;
    }

    /**
     * Prepare orphan points of customers after website was deleted
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /* @var $website \Magento\Store\Model\Website */
        $website = $observer->getEvent()->getWebsite();
        $this->_rewardFactory->create()->prepareOrphanPoints($website->getId(), $website->getBaseCurrencyCode());
        return $this;
    }
}
