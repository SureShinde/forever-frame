<?php
class Justuno_Social_Block_Domain extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
		return '<input id="advanced_account_domain" class=" input-text" type="text" value="' . $_SERVER['HTTP_HOST'] . '" name="groups[account][fields][domain][value]" readonly="true"></input>';
    }
}