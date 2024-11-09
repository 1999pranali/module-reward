<?php
/**
 * Coditron
 *
 * @category  Coditron
 * @package   Coditron_Reward
 * @author    Coditron
 * @copyright Copyright (c) Coditron (https://coditron.com)
 */

declare(strict_types=1);

namespace Coditron\Reward\Controller\UsePoint;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class Save extends \Magento\Framework\App\Action\Action
{
    protected $resultJsonFactory;
    protected $checkoutSession;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        JsonFactory $resultJsonFactory,
        \Magento\Checkout\Model\Session $checkoutSession
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->checkoutSession = $checkoutSession;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $rewardPoints = $this->getRequest()->getPost('customdata');
        $quote = $this->checkoutSession->getQuote();
        $id = $quote->getId();
        $quote->setData('reward_points_balance', $rewardPoints);
        $quote->save();
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData([
            'success' => true,
            'message' => 'Points have been used successfully!'
        ]);
    }
}
