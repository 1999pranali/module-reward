<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Model\Source\Customer;

class Groups implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Customer collection
     *
     * @var \Magento\Customer\Model\ResourceModel\Group\CollectionFactory
     */
    protected $_groupsFactory;

    /**
     * @param \Magento\Customer\Model\ResourceModel\Group\CollectionFactory $groupsFactory
     */
    public function __construct(\Magento\Customer\Model\ResourceModel\Group\CollectionFactory $groupsFactory)
    {
        $this->_groupsFactory = $groupsFactory;
    }

    /**
     * Retrieve option array of customer groups
     *
     * @return array
     */
    public function toOptionArray()
    {
        $groups = $this->_groupsFactory->create()->addFieldToFilter(
            'customer_group_id',
            ['gt' => 0]
        )->load()->toOptionHash();
        $groups = [0 => __('All Customer Groups')] + $groups;
        return $groups;
    }
}
