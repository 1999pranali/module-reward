<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Model\Source\Points;

class ExpiryCalculation implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Expiry calculation options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'static', 'label' => __('Static')],
            ['value' => 'dynamic', 'label' => __('Dynamic')]
        ];
    }
}
