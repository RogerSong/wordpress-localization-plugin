<?php

namespace Smartling\ContentTypes;

/**
 * Class ContentTypeAdrotateGroups
 * @package Smartling\ContentTypes
 */
class ContentTypeAdrotateGroups extends ContentTypeAdrotateBasic
{
    /**
     * The system name of Wordpress content type to make references safe.
     */
    const WP_CONTENT_TYPE = 'adrotate_groups';
    
    /**
     * Display name of content type, e.g.: Post
     *
     * @return string
     */
    public function getLabel()
    {
        return __('AdRotate Groups');
    }
    
    /**
     * @inheritdoc
     */
    public function getEntityClass()
    {
        return 'Smartling\DbAl\WordpressContentEntities\AdrotateGroupsEntity';
    }
}
