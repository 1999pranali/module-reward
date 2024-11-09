<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Model\Total\Quote;

use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;

class Reward extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    /**
     * Reward data
     *
     * @var \Coditron\Reward\Helper\Data
     */
    protected $_rewardData = null;

    /**
     * Reward factory
     *
     * @var \Coditron\Reward\Model\RewardFactory
     */
    protected $_rewardFactory;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * @var \Magento\Framework\Api\ExtensibleDataObjectConverter
     */
    protected $extensibleDataObjectConverter;

    /**
     * @param \Coditron\Reward\Helper\Data $rewardData
     * @param \Coditron\Reward\Model\RewardFactory $rewardFactory
     * @param PriceCurrencyInterface $priceCurrency
     * @param \Magento\Framework\Api\ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        \Coditron\Reward\Helper\Data $rewardData,
        \Coditron\Reward\Model\RewardFactory $rewardFactory,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Framework\Api\ExtensibleDataObjectConverter $extensibleDataObjectConverter,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \Coditron\Reward\Model\ResourceModel\Reward\Rate\CollectionFactory $rewardRateCollectionFactory,
        \Magento\Quote\Model\ResourceModel\Quote\CollectionFactory $quoteCollectionFactory,
    ) {
        $this->priceCurrency = $priceCurrency;
        $this->_rewardData = $rewardData;
        $this->_rewardFactory = $rewardFactory;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
        $this->_customerSession = $customerSession;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        $this->rewardRateCollectionFactory = $rewardRateCollectionFactory;
        $this->quoteCollectionFactory = $quoteCollectionFactory;
        $this->setCode('reward');
    }

    /**
     * Collect reward totals
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param ShippingAssignmentInterface $shippingAssignment
     * @param Address\Total $total
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        $customerId = $quote->getCustomerId();
        $latestQuote = $this->getLatestQuoteForCustomer($customerId);
        if ($this->_customerSession->isLoggedIn()) {
            $customerId = $this->_customerSession->getCustomer()->getId();
            $customer = $this->_customerRepositoryInterface->getById($customerId);
        }
        if (!$this->_rewardData->isEnabledOnFront($quote->getStore()->getWebsiteId())) {
            return $this;
        }
        $total->setRewardPointsBalance(0)->setRewardCurrencyAmount(0)->setBaseRewardCurrencyAmount(0);
        $grandTotal = $total->getBaseGrandTotal();       
        if ($total->getBaseGrandTotal() >= 0 && $quote->getCustomer()->getId() && $quote->getUseRewardPoints()) {
            /* @var $reward \Magento\Reward\Model\Reward */
            $reward = $quote->getRewardInstance();
            if (!$reward || !$reward->getId()) {
                $customer = $quote->getCustomer();
                $reward = $this->_rewardFactory->create()->setCustomer($customer);
                $reward->setCustomerId($quote->getCustomer()->getId());
                $reward->setWebsiteId($quote->getStore()->getWebsiteId());
                $reward->loadByCustomer();
            }
            $pointsLeft = $reward->getPointsBalance();

            $rewardCurrencyAmountLeft = $this->priceCurrency->convert(
                $reward->getCurrencyAmount(),
                $quote->getStore()
            ) - $quote->getRewardCurrencyAmount();
            $rate = null;
            $collection = $this->rewardRateCollectionFactory->create();
            $item = $collection->addFieldToFilter('direction', 1)
                            ->getFirstItem();

            if ($item && $item->getId()) {
                $rate = $item->getCurrencyAmount(); 
            }
            if($latestQuote > 0){
                $baseRewardCurrencyAmountLeft = $latestQuote * $rate;
            }else{
                $baseRewardCurrencyAmountLeft = $reward->getCurrencyAmount() - $quote->getBaseRewardCurrencyAmount();
            }
            
            if ($baseRewardCurrencyAmountLeft >= $total->getBaseGrandTotal()) {
                $pointsBalanceUsed = $reward->getPointsEquivalent($total->getBaseGrandTotal());
                $pointsCurrencyAmountUsed = $total->getGrandTotal();
                $basePointsCurrencyAmountUsed = $total->getBaseGrandTotal();
                $total->setGrandTotal(0);
                $total->setBaseGrandTotal(0);
            } else {
                $pointsBalanceUsed = $reward->getPointsEquivalent($baseRewardCurrencyAmountLeft);
                if ($pointsBalanceUsed > $pointsLeft) {
                    $pointsBalanceUsed = $pointsLeft;
                }
                if($latestQuote > 0) {
                    $pointsCurrencyAmountUsed = $baseRewardCurrencyAmountLeft;
                }else{
                    $pointsCurrencyAmountUsed = $rewardCurrencyAmountLeft;
                }
                $basePointsCurrencyAmountUsed = $baseRewardCurrencyAmountLeft;
                $total->setGrandTotal($total->getGrandTotal() - $pointsCurrencyAmountUsed);
                $total->setBaseGrandTotal($total->getBaseGrandTotal() - $basePointsCurrencyAmountUsed);
            }
            $quote->setRewardPointsBalance($latestQuote);
            $quote->setRewardCurrencyAmount($quote->getRewardCurrencyAmount() + $pointsCurrencyAmountUsed);
            $quote->setBaseRewardCurrencyAmount($quote->getBaseRewardCurrencyAmount() + $basePointsCurrencyAmountUsed);
            
            $total->setRewardPointsBalance(round($latestQuote));
            $total->setRewardCurrencyAmount($pointsCurrencyAmountUsed);
            $total->setBaseRewardCurrencyAmount($basePointsCurrencyAmountUsed);
        }
        return $this;
    }

    /**
     * Retrieve reward total data and set it to quote address
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param Address|Address\Total $total
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        if (!$this->_rewardData->isEnabledOnFront()) {
            return null;
        }
        if ($total->getRewardCurrencyAmount()) {
            return [
                'code' => $this->getCode(),
                'title' => $this->_rewardData->formatReward($total->getRewardPointsBalance()),
                'value' => -$total->getRewardCurrencyAmount(),
            ];
        }
        return null;
    }

    /**
     * Get the latest quote for the customer
     *
     * @param int $customerId
     * @return \Magento\Quote\Model\Quote|null
     */
    public function getLatestQuoteForCustomer($customerId)
    {
        $quoteCollection = $this->quoteCollectionFactory->create()
            ->addFieldToFilter('customer_id', $customerId)
            ->addOrder('created_at', 'DESC') 
            ->setPageSize(1); 

        $quote = $quoteCollection->getFirstItem();

        if ($quote && $quote->getId()) {
            return $quote->getRewardPointsBalance();
        }
        return null;
    }

}
