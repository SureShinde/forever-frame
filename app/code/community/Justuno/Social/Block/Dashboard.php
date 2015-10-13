<?php
class Justuno_Social_Block_Dashboard extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
		$email = Mage::getStoreConfig('advanced/account/email');
		$domain = Mage::getStoreConfig('advanced/account/domain');
		$jusdata = Mage::getStoreConfig('advanced/account/embed');
		if ($email && $jusdata) {
			$jusdata = json_decode($jusdata);
			include_once realpath(dirname(__FILE__) . '/../Model/JustunoAccess.php');
			$jAccess = new JustunoAccess(array('apiKey'=>JUSTUNO_KEY,'email'=>$email, 'guid'=>$jusdata->guid, 'domain'=>$domain));
			$url = $jAccess->getDashboardLink();
		}
		else {
			$url = 'https://www.justuno.com/dashboard.html?loggedin=true';
		}
		return '<button title="Justuno Dashboard" class="scalable" onClick="window.open(\'' . $url . '\',\'_blank\'); return false;">Justuno Dashboard</button>';
    }
}
