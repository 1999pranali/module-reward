<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Model\Plugin;

use Magento\CustomerImportExport\Model\Import\Customer;

class ImportCustomer
{
    /**
     * Customer fields in file
     */
    protected $customerFields = [
        'reward_update_notification',
        'reward_warning_notification',
    ];

    /**
     * @param Customer $importCustomer
     * @param array $validColumnNames
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetValidColumnNames(Customer $importCustomer, array $validColumnNames)
    {
        return array_merge($validColumnNames, $this->customerFields);
    }
}
