<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Model\Action\Creditmemo;

class VoidAction extends \Coditron\Reward\Model\Action\Creditmemo
{
    /**
     * Return action message for history log
     *
     * @param array $args additional history data
     * @return \Magento\Framework\Phrase
     */
    public function getHistoryMessage($args = [])
    {
        $incrementId = isset($args['increment_id']) ? $args['increment_id'] : '';
        return __('Points voided at order #%1 refund.', $incrementId);
    }
}
