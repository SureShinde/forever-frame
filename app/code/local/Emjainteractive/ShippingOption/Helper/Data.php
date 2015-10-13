<?php
/**
 * EmJa Interactive, LLC.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.emjainteractive.com/LICENSE.txt
 *
 * @category   EmJaInteractive
 * @package    EmJaInteractive_ShippingOption
 * @copyright  Copyright (c) 2010 EmJa Interactive, LLC. (http://www.emjainteractive.com)
 * @license    http://www.emjainteractive.com/LICENSE.txt
 */

class Emjainteractive_ShippingOption_Helper_Data extends Mage_Core_Helper_Abstract 
{
    protected $_collection;

    public function isShippingMethodAvailable()
    {
        $result = true;
        $carrierConfig = Mage::getStoreConfig('carriers/umosaco');

        if (!isset($carrierConfig['customergroups']) || !$carrierConfig['customergroups']) {
            $result = false;
        } else {
            $allowedCustomerGroups = (strpos($carrierConfig['customergroups'], ',') !== false) ?
                explode(',', $carrierConfig['customergroups']) :
                array($carrierConfig['customergroups']);

            $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
            if (!in_array($customerGroupId, $allowedCustomerGroups)) {
                $result = false;
            }
        }

        return $result;
    }

    public function getCarrierOptions($carrier)
    {
        $options = array();
        foreach( $this->_getOptionsCollection()->getItems() as $_option ) {
            $applyTo = explode(',', $_option->getApplyTo());
            if( in_array($carrier, $applyTo) ) {
                $optionHtml = $this->getLayout()->createBlock('emjainteractive_shippingoption/renderer_' . $_option->getType())
                                        ->setData('option', $_option)
                                        ->setData('carrier', $carrier)
                                        ->toHtml();
                $options[$_option->getCode()] = $optionHtml; 
            }
        }
        
        return $options;
    }
    
    protected function _getOptionsCollection()
    {
        if( !$this->_collection ) {
            $this->_collection = Mage::getModel('emjainteractive_shippingoption/option')
                 ->getCollection()
                 ->applySortOrder()
                 ->load();
        }
        
        return $this->_collection;
    }
    
    public function getOrderOptionsHtml($order)
    {
        if( !$order || !$order->getId() ) {
            return false;
        }

        return $this->getLayout()->createBlock('emjainteractive_shippingoption/adminhtml_sales_order_options')
                   ->setOrder($order)
                   ->toHtml();
    }

    public function getEmjainteractiveShippingoption($object)
    {
        $_options = array();
        $options  = unserialize($object->getEmjainteractiveShippingoption());
        if (isset($options['umosaco'])) {
            $model = Mage::getSingleton('emjainteractive_shippingoption/option');
            foreach ($options['umosaco'] as $_code => $_value) {
                $model->load($_code, 'code');
                if ($model->getId()) {
                    $_options[$model->getLabel()] = $_value;
                }
            }
        }
        return $_options;
    }
}
