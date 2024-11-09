<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Model\ResourceModel\Reward;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Internal constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Coditron\Reward\Model\Reward::class, \Coditron\Reward\Model\ResourceModel\Reward::class);
    }

    /**
     * Add filter by website id
     *
     * @param int|array $websiteId
     * @return $this
     */
    public function addWebsiteFilter($websiteId)
    {
        $this->getSelect()->where(
            is_array($websiteId) ? 'main_table.website_id IN (?)' : 'main_table.website_id = ?',
            $websiteId
        );
        return $this;
    }
}
