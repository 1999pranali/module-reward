<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */


namespace Coditron\Reward\Model\Source;

class Website implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Core system store model
     *
     * @var \Magento\Store\Model\System\Store
     */
    protected $_store;

    /**
     * @param \Magento\Store\Model\System\Store $store
     */
    public function __construct(\Magento\Store\Model\System\Store $store)
    {
        $this->_store = $store;
    }

    /**
     * Prepare and return array of website ids and their names
     *
     * @param bool $withAll Whether to prepend "All websites" option on not
     * @return array
     */
    public function toOptionArray($withAll = true)
    {
        $websites = $this->_store->getWebsiteOptionHash();
        if ($withAll) {
            $websites = [0 => __('All Websites')] + $websites;
        }
        return $websites;
    }
}
