<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Observer\PlaceOrder\Restriction;

class Frontend implements \Coditron\Reward\Observer\PlaceOrder\RestrictionInterface
{
    /**
     * Reward data
     *
     * @var \Coditron\Reward\Helper\Data
     */
    protected $_helper;

    /**
     * @param \Coditron\Reward\Helper\Data $helper
     */
    public function __construct(\Coditron\Reward\Helper\Data $helper)
    {
        $this->_helper = $helper;
    }

    /**
     * Check if reward points operations is allowed
     *
     * @return bool
     */
    public function isAllowed()
    {
        return $this->_helper->isEnabledOnFront();
    }
}
