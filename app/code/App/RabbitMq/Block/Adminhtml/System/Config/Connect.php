<?php

namespace App\RabbitMq\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Connect
 *
 * @package App\RabbitMq\Block\Adminhtml\System\Config
 */
class Connect extends Field
{
    /**
     * @var string
     */
    protected $_template = 'App_RabbitMq::system/config/connect.phtml';

    /**
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
    public function getAjaxUrl()
    {
        return $this->getUrl('App_rabbitmq/system_config/connect');
    }

    /**
     * Generate connection test button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'id' => 'connect_button',
                'label' => __('Test connection'),
            ]
        );

        return $button->toHtml();
    }
}
