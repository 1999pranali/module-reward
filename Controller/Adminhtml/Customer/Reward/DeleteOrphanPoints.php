<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

namespace Coditron\Reward\Controller\Adminhtml\Customer\Reward;

use Magento\Framework\App\Action\HttpPostActionInterface;

/**
 * Remove points of Deleted Website
 */
class DeleteOrphanPoints extends \Coditron\Reward\Controller\Adminhtml\Customer\Reward implements HttpPostActionInterface
{
    /**
     *  Delete orphan points Action
     *
     * @return void
     */
    public function execute()
    {
        $customerId = $this->getRequest()->getParam('id', 0);
        if ($customerId) {
            try {
                $this->_objectManager->create(
                    \Coditron\Reward\Model\Reward::class
                )->deleteOrphanPointsByCustomer(
                    $customerId
                );
                $this->messageManager->addSuccess(__('You removed the orphan points.'));
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $this->_redirect('customer/index/edit', ['_current' => true]);
    }
}
