<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Observer\PlaceOrder;

/**
 * Interface \Coditron\Reward\Observer\PlaceOrder\RestrictionInterface
 *
 */
interface RestrictionInterface
{
    /**
     * Check if reward points operations is allowed
     *
     * @return bool
     */
    public function isAllowed();
}
