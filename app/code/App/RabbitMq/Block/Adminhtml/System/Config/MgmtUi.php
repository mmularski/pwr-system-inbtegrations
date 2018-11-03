<?php

namespace App\RabbitMq\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class MgmtUi
 *
 * @package App\RabbitMq\Block\Adminhtml\System\Config
 */
class MgmtUi extends Field
{
    /**
     * RabbitMq Management Ui port Nnmber
     */
    const RABBITMQ_MANAGEMENTUI_PORT = '15672';

    /**
     * @var string
     */
    protected $_template = 'App_RabbitMq::system/config/mgmt_ui.phtml';

    /**
     * @var StoreManagerInterface $storeManager
     */
    protected $storeManager;

    /**
     * MgmtUi constructor.
     *
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * Remove scope label
     *
     * @param  AbstractElement $element
     *
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();

        return parent::render($element);
    }

    /**
     * Return element html
     *
     * @param  AbstractElement $element
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @codingStandardsIgnoreStart
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        // @codingStandardsIgnoreEnd
        return $this->_toHtml();
    }

    /**
     * Return ajax url for collect button
     *
     * @return string
     */
    public function getManagementUiUrl()
    {
        $baseUrl = $this->getBaseUrl();

        $trailingChar = substr(
            $baseUrl,
            -1
        );
        if ($trailingChar == '/') {
            // remove trailing slash from the url
            $baseUrl = substr(
                $baseUrl,
                0,
                -1
            );
        }

        return $baseUrl . ':' . self::RABBITMQ_MANAGEMENTUI_PORT . '/';
    }

    /**
     * Generate Management Ui button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'id' => 'management_ui_button',
                'label' => __('Go to Management Ui'),
                'onclick' => 'window.open(\'' . $this->getManagementUiUrl() . '\', \'_blank\')',
            ]
        );

        return $button->toHtml();
    }
}
