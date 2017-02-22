<?php

namespace Smartling\ContentTypes;

/**
 * Class ContentTypeAdrotateLinkmeta
 * @package Smartling\ContentTypes
 */
class ContentTypeAdrotateLinkmeta extends ContentTypeAdrotateBasic
{
    /**
     * The system name of Wordpress content type to make references safe.
     */
    const WP_CONTENT_TYPE = 'adrotate_linkmeta';
    
    /**
     * Display name of content type, e.g.: Post
     *
     * @return string
     */
    public function getLabel()
    {
        return __('AdRotate Linkmeta');
    }
    
    /**
     * @inheritdoc
     */
    public function getEntityClass()
    {
        return 'Smartling\DbAl\WordpressContentEntities\AdrotateLinkmetaEntity';
    }
    
    /**
     * @inheritdoc
     */
    public function getVisibility()
    {
        return [
            'submissionBoard' => false,
            'bulkSubmit'      => false,
        ];
    }
}
