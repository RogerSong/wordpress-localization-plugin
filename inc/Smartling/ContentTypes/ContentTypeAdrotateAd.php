<?php

namespace Smartling\ContentTypes;

use Smartling\DbAl\WordpressContentEntities\AdrotateLinkmetaEntity;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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
     * ContentTypeAdrotateAd constructor.
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $di
     */
    public function __construct(ContainerBuilder $di)
    {
        parent::__construct($di);
        
        $this->registerFilters();
    }
    
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
    
    /**
     * Register filters.
     */
    public function registerFilters()
    {
        $filters = [
            'metaId'   => ContentTypeAdrotateLinkmeta::WP_CONTENT_TYPE,
            'schedule' => ContentTypeAdrotateSchedule::WP_CONTENT_TYPE,
            'group'    => ContentTypeAdrotateGroups::WP_CONTENT_TYPE
        ];
        
        foreach ($filters as $fieldName => $typeName) {
            $di = $this->getContainerBuilder();
            $wrapperId = 'referenced-content.adrotate_'.$fieldName;
            $definition = $di->register($wrapperId, 'Smartling\Helpers\MetaFieldProcessor\ReferencedContentProcessor');
            $definition
                ->addArgument($di->getDefinition('logger'))
                ->addArgument($di->getDefinition('translation.helper'))
                ->addArgument($fieldName)
                ->addArgument($typeName)
                ->addMethodCall('setContentHelper', [$di->getDefinition('content.helper')]);
    
            $di->get('meta-field.processor.manager')->registerProcessor($di->get($wrapperId));
        }
    }
}
