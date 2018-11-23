<?php

namespace App\Sales\Block\Order;

use DOMDocument;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Sales\Block\Order\Info as MagentoInfo;
use Magento\Sales\Model\Order\Address\Renderer as AddressRenderer;
use SimpleXMLElement;
use XSLTProcessor;
use Magento\Framework\HTTP\ClientFactory;

/**
 * Class Info
 */
class Info extends MagentoInfo
{
    /**
     * @var string
     */
    protected $_template = 'App_Sales::order/info.phtml';

    /**
     * @var ClientFactory
     */
    private $clientFactory;

    /**
     * Info constructor.
     *
     * @param ClientFactory $clientFactory
     * @param TemplateContext $context
     * @param Registry $registry
     * @param PaymentHelper $paymentHelper
     * @param AddressRenderer $addressRenderer
     * @param array $data
     */
    public function __construct(
        ClientFactory $clientFactory,
        TemplateContext $context,
        Registry $registry,
        PaymentHelper $paymentHelper,
        AddressRenderer $addressRenderer,
        array $data = []
    ) {
        parent::__construct($context, $registry, $paymentHelper, $addressRenderer, $data);
        $this->clientFactory = $clientFactory;
    }
//
//    /**
//     * Convert array to XML
//     *
//     * @param SimpleXMLElement $object
//     * @param array $data
//     */
//    public function to_xml(SimpleXMLElement $object, array $data)
//    {
//        foreach ($data as $key => $value) {
//            if (is_array($value)) {
//                $new_object = $object->addChild($key);
//                $this->to_xml($new_object, $value);
//            } else {
//                if (is_int($key)) {
//                    $key = 'item';
//                }
//
//                $object->addChild($key, $value);
//            }
//        }
//    }

    /**
     * Transform Order data via XSLT
     *
     * @return string
     */
    public function getShippingInformationXSLT()
    {
//        $order = $this->getOrder();
//
//        $testOrder = [
//            'order' => ['status' => $order->getStatus()],
//            'customer' => [
//                'firstName' => $order->getCustomerFirstname(),
//                'lastName' => $order->getCustomerLastname(),
//                'street' => $order->getShippingAddress()->getStreetLine(1),
//                'building' => $order->getShippingAddress()->getStreetLine(2),
//                'city' => $order->getShippingAddress()->getCity(),
//                'postCode' => $order->getShippingAddress()->getPostcode(),
//            ],
//        ];
//
/*        $xml = new SimpleXMLElement('<?xml version="1.0"?><summary></summary>');*/
//        $this->to_xml($xml, $testOrder);
//
//        foreach ($order->getAllItems() as $item) {
//            if ('simple' === $item->getProductType()) {
//                $data = [
//                    'item' => [
//                        'sku' => $item->getSku(),
//                        'name' => $item->getName(),
//                        'count' => $item->getQtyOrdered(),
//                        'price' => $item->getProduct()->getPrice(),
//                    ],
//                ];
//
//                $this->to_xml($xml, ['package' => $data]);
//            }
//        }

        $client = $this->clientFactory->create();
        $client->get('http://192.168.56.234:1880/orders/' . $this->getOrder()->getEntityId());

        $xml = new DOMDocument();
        $xml->loadXML($client->getBody());

        try {
            $xsl = new DOMDocument;
            $xsl->load(__DIR__ . '../../../fixtures/template.xsl');
            $processor = new XSLTProcessor;
            $processor->importStyleSheet($xsl);

            return $processor->transformToXML($xml);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}