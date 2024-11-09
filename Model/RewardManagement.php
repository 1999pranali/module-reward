<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Model;

class RewardManagement implements \Coditron\Reward\Api\RewardManagementInterface
{
    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * Reward helper
     *
     * @var \Coditron\Reward\Helper\Data
     */
    protected $rewardData;

    /**
     * @var \Coditron\Reward\Model\PaymentDataImporter
     */
    protected $importer;

    /**
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     * @param \Coditron\Reward\Helper\Data $rewardData
     * @param PaymentDataImporter $importer
     */
    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Coditron\Reward\Helper\Data $rewardData,
        \Coditron\Reward\Model\PaymentDataImporter $importer
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->rewardData = $rewardData;
        $this->importer = $importer;
    }

    /**
     * {@inheritdoc}
     */
    public function set($cartId)
    {
        if ($this->rewardData->isEnabledOnFront()) {
            /* @var $quote \Magento\Quote\Model\Quote */
            $quote = $this->quoteRepository->getActive($cartId);
            $this->importer->import($quote, $quote->getPayment(), true);
            $quote->collectTotals();
            $this->quoteRepository->save($quote);
            return true;
        }
        return false;
    }
}
