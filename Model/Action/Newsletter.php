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

class Newsletter extends \Coditron\Reward\Model\Action\AbstractAction
{
    /**
     * Reward data
     *
     * @var \Coditron\Reward\Helper\Data
     */
    protected $_rewardData = null;

    /**
     * Newsletter model resource subscriber collection
     *
     * @var \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory
     */
    protected $_subscribersFactory;

    /**
     * Constructor
     *
     * By default is looking for first argument as array and assigns it as object
     * attributes This behavior may change in child classes
     *
     * @param \Coditron\Reward\Helper\Data $rewardData
     * @param \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory $subscribersFactory
     * @param array $data
     */
    public function __construct(
        \Coditron\Reward\Helper\Data $rewardData,
        \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory $subscribersFactory,
        array $data = []
    ) {
        $this->_rewardData = $rewardData;
        $this->_subscribersFactory = $subscribersFactory;
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
        return (int)$this->_rewardData->getPointsConfig('newsletter', $websiteId);
    }

    /**
     * Check whether rewards can be added for action
     *
     * @return bool
     */
    public function canAddRewardPoints()
    {
        $subscriber = $this->getEntity();
        $subscriberStatuses = [
            \Magento\Newsletter\Model\Subscriber::STATUS_SUBSCRIBED,
            \Magento\Newsletter\Model\Subscriber::STATUS_UNCONFIRMED,
        ];
        if (!in_array($subscriber->getData('subscriber_status'), $subscriberStatuses)) {
            return false;
        }

        /* @var $subscribers \Magento\Newsletter\Model\ResourceModel\Subscriber\Collection */
        $subscribers = $this->_subscribersFactory->create()->addFieldToFilter(
            'customer_id',
            $subscriber->getCustomerId()
        )->load();
        // check for existing customer subscribtions
        $found = false;
        foreach ($subscribers as $item) {
            if ($subscriber->getSubscriberId() != $item->getSubscriberId()) {
                $found = true;
                break;
            }
        }
        $exceeded = $this->isRewardLimitExceeded();
        return !$found && !$exceeded;
    }

    /**
     * Return action message for history log
     *
     * @param array $args Additional history data
     * @return \Magento\Framework\Phrase
     */
    public function getHistoryMessage($args = [])
    {
        $email = isset($args['email']) ? $args['email'] : '';
        return __('Signed up for newsletter with email %1', $email);
    }

    /**
     * Setter for $_entity and add some extra data to history
     *
     * @param \Magento\Framework\DataObject $entity
     * @return $this
     * @codeCoverageIgnore
     */
    public function setEntity($entity)
    {
        parent::setEntity($entity);
        $this->getHistory()->addAdditionalData(['email' => $this->getEntity()->getEmail()]);
        return $this;
    }
}
