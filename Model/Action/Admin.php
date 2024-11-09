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

class Admin extends \Coditron\Reward\Model\Action\AbstractAction
{
    /**
     * Check whether rewards can be added for action
     *
     * @return bool
     */
    public function canAddRewardPoints()
    {
        return true;
    }

    /**
     * Return action message for history log
     *
     * @param array $args Additional history data
     * @return \Magento\Framework\Phrase
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getHistoryMessage($args = [])
    {
        return __('Updated by moderator');
    }
}
