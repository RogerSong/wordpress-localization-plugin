<?php

namespace Smartling\ContentTypes;

/**
 * Class ContentTypeAdrotateAd
 * @package Smartling\ContentTypes
 */
class ContentTypeAdrotateAd extends ContentTypeAdrotateBasic
{
    /**
     * The system name of Wordpress content type to make references safe.
     */
    const WP_CONTENT_TYPE = 'adrotate_ad';
    
    /**
     * Display name of content type, e.g.: Post
     *
     * @return string
     */
    public function getLabel()
    {
        return __('AdRotate');
    }
    
    /**
     * @inheritdoc
     */
    public function getEntityClass()
    {
        return 'Smartling\DbAl\WordpressContentEntities\AdrotateAdEntity';
    }
}
