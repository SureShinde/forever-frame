<?php
/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@mageworx.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 * or send an email to sales@mageworx.com
 *
 * @category   MageWorx
 * @package    MageWorx_MageBox
 * @copyright  Copyright (c) 2010 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * MageBox extension
 *
 * @category   MageWorx
 * @package    MageWorx_MageBox
 * @author     MageWorx Dev Team <dev@mageworx.com>
 */

class MageWorx_MageBox_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_ENABLED = 'mageworx_tweaks/magebox/enabled';
    const XML_IMAGE_SIZE = 'mageworx_tweaks/magebox/image_size';
    const XML_THUMB_SIZE = 'mageworx_tweaks/magebox/thumb_size';
    const XML_SPEED = 'mageworx_tweaks/magebox/speed';
    const XML_CLOSE_SPEED = 'mageworx_tweaks/magebox/close_speed';
    const XML_BG_ALPHA = 'mageworx_tweaks/magebox/bg_alpha';
    const XML_SLIDE_SHOW = 'mageworx_tweaks/magebox/slide_show';
    const XML_SLIDE_SPEED = 'mageworx_tweaks/magebox/slide_speed';

    public function isEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_ENABLED);
    }

    public function getConfig()
    {
        $image = preg_split('/[^\d]+/', Mage::getStoreConfig(self::XML_IMAGE_SIZE), 2);
        $image[0] = isset($image[0]) ? $image[0] : 265;
        $image[1] = isset($image[1]) ? $image[1] : 265;
        $thumb = preg_split('/[^\d]+/', Mage::getStoreConfig(self::XML_THUMB_SIZE), 2);
        $thumb[0] = isset($thumb[0]) ? $thumb[0] : 56;
        $thumb[1] = isset($thumb[1]) ? $thumb[1] : 56;
        return new Varien_Object(
            array(
                'image_width' => $image[0],
                'image_height' => $image[1],
                'thumb_width' => $thumb[0],
                'thumb_height' => $thumb[1],
                'speed' => (int) Mage::getStoreConfig(self::XML_SPEED),
                'close_speed' => (int) Mage::getStoreConfig(self::XML_CLOSE_SPEED),
                'bg_alpha' => (float) Mage::getStoreConfig(self::XML_BG_ALPHA),
                'slide_show' => Mage::getStoreConfigFlag(self::XML_SLIDE_SHOW) ? 'true' : 'false',
                'slide_speed' => (int) Mage::getStoreConfig(self::XML_SLIDE_SPEED),
            ));
    }
}