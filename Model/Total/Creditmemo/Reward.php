<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Model\Total\Creditmemo;

use Magento\Framework\App\ObjectManager;
use Coditron\Reward\Model\Reward as RewardModel;
use Coditron\Reward\Model\RewardFactory;
use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;

/**
 * Collect reward totals for credit memo.
 */
class Reward extends AbstractTotal
{
    /**
     * @var RewardFactory
     */
    private $rewardFactory;

    /**
     * @param RewardModel $reward
     * @param array $data
     * @param RewardFactory|null $rewardFactory
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(
        RewardModel $reward,
        array $data = [],
        ?RewardFactory $rewardFactory = null
    ) {
        parent::__construct($data);
        $this->rewardFactory = $rewardFactory ?? ObjectManager::getInstance()->get(RewardFactory::class);
    }

    /**
     * Collect reward totals for credit memo
     *
     * @param Creditmemo $creditmemo
     * @return $this
     */
    public function collect(Creditmemo $creditmemo)
    {
        $order = $creditmemo->getOrder();
        $rewardCurrencyAmountLeft = $order->getRwrdCurrencyAmountInvoiced() - $order->getRwrdCrrncyAmntRefunded();
        $baseRewardCurrencyAmountLeft = $order->getBaseRwrdCrrncyAmtInvoiced() -
            $order->getBaseRwrdCrrncyAmntRefnded();
        if ($order->getBaseRewardCurrencyAmount() && $baseRewardCurrencyAmountLeft > 0) {
            if ($baseRewardCurrencyAmountLeft >= $creditmemo->getBaseGrandTotal()) {
                $rewardCurrencyAmountLeft = $creditmemo->getGrandTotal();
                $baseRewardCurrencyAmountLeft = $creditmemo->getBaseGrandTotal();
                $creditmemo->setGrandTotal(0);
                $creditmemo->setBaseGrandTotal(0);
                $creditmemo->setAllowZeroGrandTotal(true);
            } else {
                $creditmemo->setGrandTotal($creditmemo->getGrandTotal() - $rewardCurrencyAmountLeft);
                $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() - $baseRewardCurrencyAmountLeft);
            }
            /** @var RewardModel $reward */
            $reward = $this->rewardFactory->create();
            $reward->setStoreId($order->getStoreId());
            $reward->setCustomerId($order->getCustomerId());
            $rewardPointsBalance = $reward->getPointsEquivalent($baseRewardCurrencyAmountLeft);
            $rewardPointsBalanceLeft = $order->getRewardPointsBalance() - $order->getRewardPointsBalanceRefunded();
            if ($rewardPointsBalance > $rewardPointsBalanceLeft) {
                $rewardPointsBalance = $rewardPointsBalanceLeft;
            }
            $creditmemo->setRewardPointsBalance(round($rewardPointsBalance));
            $creditmemo->setRewardCurrencyAmount($rewardCurrencyAmountLeft);
            $creditmemo->setBaseRewardCurrencyAmount($baseRewardCurrencyAmountLeft);
        }

        return $this;
    }
}
