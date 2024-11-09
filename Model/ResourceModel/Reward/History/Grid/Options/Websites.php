<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Model\ResourceModel\Reward\History\Grid\Options;

use Coditron\Reward\Model\Source\Website;

/**
 * @codeCoverageIgnore
 */
class Websites implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * System Store Model
     *
     * @var \Coditron\Reward\Model\Source\Website
     */
    protected $_systemStore;

    /**
     * @param Website $systemStore
     */
    public function __construct(Website $systemStore)
    {
        $this->_systemStore = $systemStore;
    }

    /**
     * Return websites array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_systemStore->toOptionArray(false);
    }
}
