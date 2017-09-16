<?php

namespace LoganStellway\FishPigBedrock\Helper;

/**
 * Theme Helper
 */
class Theme extends \FishPig\WordPress\Helper\Theme
{
    /**
     * @
    **/
    public function getTargetDir()
    {
        if ($this->_config->getStoreConfigValue('wordpress/setup/bedrock_enabled')) {
            return $this->_config->getStoreConfigValue('wordpress/setup/bedrock_path') . self::DS . 'web' . self::DS . 'app' . self::DS . 'themes' . self::DS . self::THEME_NAME;
        }
        return parent::getTargetDir();
    }
}
