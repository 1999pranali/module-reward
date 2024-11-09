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

class Backend implements \Coditron\Reward\Observer\PlaceOrder\RestrictionInterface
{
    /**
     * Reward data
     * @var \Coditron\Reward\Helper\Data
     */
    protected $_helper;

    /**
     * Authoriztion interface
     *
     * @var \Magento\Framework\AuthorizationInterface
     */
    protected $_authorization;

    /**
     * @param \Coditron\Reward\Helper\Data $helper
     * @param \Magento\Framework\AuthorizationInterface $authorization
     */
    public function __construct(
        \Coditron\Reward\Helper\Data $helper,
        \Magento\Framework\AuthorizationInterface $authorization
    ) {
        $this->_helper = $helper;
        $this->_authorization = $authorization;
    }

    /**
     * Check if reward points operations is allowed
     *
     * @return bool
     */
    public function isAllowed()
    {
        return $this->_helper->isEnabledOnFront() && $this->_authorization->isAllowed(
            \Coditron\Reward\Helper\Data::XML_PATH_PERMISSION_AFFECT
        );
    }
}
