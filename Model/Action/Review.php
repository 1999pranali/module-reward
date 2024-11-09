<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Model\Action;

class Review extends \Coditron\Reward\Model\Action\AbstractAction
{
    /**
     * Reward data
     *
     * @var \Coditron\Reward\Helper\Data
     */
    protected $_rewardData = null;

    /**
     * Constructor
     *
     * By default is looking for first argument as array and assigns it as object
     * attributes This behavior may change in child classes
     *
     * @param \Coditron\Reward\Helper\Data $rewardData
     * @param array $data
     */
    public function __construct(\Coditron\Reward\Helper\Data $rewardData, array $data = [])
    {
        $this->_rewardData = $rewardData;
        parent::__construct($data);
    }

    /**
     * Retrieve points delta for action
     *
     * @param int $websiteId
     * @return int
     */
    public function getPoints($websiteId)
    {
        return (int)$this->_rewardData->getPointsConfig('review', $websiteId);
    }

    /**
     * Return pre-configured limit of rewards for action
     *
     * @return int|string
     * @codeCoverageIgnore
     */
    public function getRewardLimit()
    {
        return $this->_rewardData->getPointsConfig('review_limit', $this->getReward()->getWebsiteId());
    }

    /**
     * Return action message for history log
     *
     * @param array $args Additional history data
     * @return \Magento\Framework\Phrase
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getHistoryMessage($args = [])
    {
        return __('For submitting a product review');
    }
}
