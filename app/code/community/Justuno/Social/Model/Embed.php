<?php
class Justuno_Social_Model_Embed extends Mage_Core_Model_Config_Data
{
    public function save()
    {
		$fdata = array();
		foreach ($this->groups['account']['fields'] as $name=>$field) {
			$fdata[$name] = $field['value'];
		}
		if ($fdata['embed']) {
			$obj = json_decode($fdata['embed']);
			$fdata['embed'] = $obj->embed;
			$fdata['guid'] = $obj->guid;
		}
		include_once dirname(__FILE__) . '/JustunoAccess.php';
		$params = array('apiKey'=>JUSTUNO_KEY,'email'=>$fdata['email'],'domain'=>$fdata['domain'],'guid'=>$fdata['guid']);
		if($fdata['password'])
			$params['password'] = $fdata['password'];
		$jAccess = new JustunoAccess($params);
		try {
			$justuno = $jAccess->getWidgetConfig();
			$jusdata = array();
			$jusdata['dashboard'] = (string)$jAccess->getDashboardLink();
			$jusdata['guid'] = (string)$justuno['guid'];
			$jusdata['embed'] = (string)$justuno['embed'];
			$this->setValue((string)json_encode($jusdata));
		}
		catch(JustunoAccessException $e) {
			Mage::throwException($e->getMessage());
		}
		return parent::save();
	}
}
