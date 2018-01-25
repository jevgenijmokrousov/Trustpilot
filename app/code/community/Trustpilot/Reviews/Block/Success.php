<?php

class Trustpilot_Reviews_Block_Success extends Mage_Checkout_Block_Onepage_Success
{
   
    protected $_version;
    
    public function __construct()
    {
        $this->_version = '1.0.20';
        parent::__construct();
    }

    public function renderScript()
    {
        $data = $this->getOrder();
        return '
        <script type="text/javascript">
            document.addEventListener(\'DOMContentLoaded\', function() {
                tp(\'createInvitation\', '.json_encode($data, JSON_HEX_APOS).');
            });
        </script>';
    }

    public function getOrder()
    {
        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        $order = Mage::getModel('sales/order')->load($orderId);
        $items = $order->getAllVisibleItems();
        $products = array();
        
        foreach ($items as $i) {
            $product = Mage::getModel('catalog/product')->load($i->getProductId());
            $brand = $product->getAttributeText('manufacturer');
            array_push(
                $products,
                array(
                    'productUrl' => $product->getProductUrl(),
                    'name' => $product->getName(),
                    'brand' => $brand ? $brand : '',
                    'sku' => $product->getSku(),
                    //'gtin' => $gtin,
                    //'mpn' => $mpn,
                    'imageUrl' => Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'catalog/product' . $product->getImage()
                ));
        }

        return $data = array(
            'recipientEmail' => $order->getCustomerEmail(),
            'recipientName' => $order->getCustomerName(),
            'referenceId' => $order->getIncrementId(),
            'productSkus' => $this->getSkus($products),
            'source' => 'Magento-'.Mage::getVersion(),
            'pluginVersion' => $this->_version,
            'products' => $products,
          );
    }

    private function getSkus($products)
    {
        $skus = array();
        foreach ($products as $product) {
            array_push($skus, $product['sku']);
        }
        return $skus;
    }
}
