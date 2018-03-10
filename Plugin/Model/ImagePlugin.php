<?php

namespace LoganStellway\FishPigBedrock\Plugin\Model;

/**
 * Image Plugin
 */
class ImagePlugin
{
    /**
     * After get file upload URL
     */
    public function afterGetFileUploadUrl(\FishPig\WordPress\Model\Image $subject, $result)
    {
        return str_replace('wp/wp-content', 'app', $result);
    }
}
