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

class PaymentDataImport implements ObserverInterface
{
    /**
     * Reward helper
     *
     * @var \Coditron\Reward\Helper\Data
     */
    protected $_rewardData;

    /**
     * @var \Coditron\Reward\Model\PaymentDataImporter
     */
    protected $importer;

    /**
     * @param \Coditron\Reward\Helper\Data $rewardData
     * @param \Coditron\Reward\Model\PaymentDataImporter $importer
     */
    public function __construct(
        \Coditron\Reward\Helper\Data $rewardData,
        \Coditron\Reward\Model\PaymentDataImporter $importer
    ) {
        $this->_rewardData = $rewardData;
        $this->importer = $importer;
    }

    /**
     * Payment data import in checkout process
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->_rewardData->isEnabledOnFront()) {
            $event = $observer->getEvent();
            $input = $event->getInput();
            /* @var $quote \Magento\Quote\Model\Quote */
            $quote = $event->getPayment()->getQuote();
            $additionalData = (array)$input->getAdditionalData();
            if ($quote->getIsMultiShipping() && isset($additionalData['use_reward_points'])) {
                $this->importer->import($quote, $input, $additionalData['use_reward_points']);
            }
        }
        return $this;
    }
}
