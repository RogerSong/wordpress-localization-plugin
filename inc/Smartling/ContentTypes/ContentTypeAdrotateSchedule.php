<?php

namespace Smartling\ContentTypes;

/**
 * Class ContentTypeAdrotateSchedule
 * @package Smartling\ContentTypes
 */
class ContentTypeAdrotateSchedule extends ContentTypeAdrotateBasic
{
    /**
     * The system name of Wordpress content type to make references safe.
     */
    const WP_CONTENT_TYPE = 'adrotate_schedule';
    
    /**
     * Display name of content type, e.g.: Post
     *
     * @return string
     */
    public function getLabel()
    {
        return __('AdRotate Schedule');
    }
    
    /**
     * @inheritdoc
     */
    public function getEntityClass()
    {
        return 'Smartling\DbAl\WordpressContentEntities\AdrotateScheduleEntity';
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
