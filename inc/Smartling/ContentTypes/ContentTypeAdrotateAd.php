<?php

namespace Smartling\ContentTypes;

use Smartling\Bootstrap;
use Smartling\DbAl\WordpressContentEntities\AdrotateAdEntity;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ContentTypeAdrotateAd
 * @package Smartling\ContentTypes
 */
class ContentTypeAdrotateAd extends ContentTypeAbstract
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
        
        $this->registerIOWrapper();
    }
    
    /**
     * Display name of content type, e.g.: Post
     * @return string
     */
    public function getLabel()
    {
        return __('AdRotate');
    }
    
    /**
     * @return array [
     *  'submissionBoard'   => true|false,
     *  'bulkSubmit'        => true|false
     * ]
     */
    public function getVisibility()
    {
        return [
            'submissionBoard' => true,
            'bulkSubmit'      => true,
        ];
    }
    
    /**
     * @return string
     */
    public function getSystemName()
    {
        return static::WP_CONTENT_TYPE;
    }
    
    /**
     * Handler to register IO Wrapper
     * @return void
     */
    public function registerIOWrapper()
    {
        $di = $this->getContainerBuilder();
        $wrapperId = 'wrapper.entity.' . $this->getSystemName();
        $definition = $di->register($wrapperId, '\Smartling\DbAl\WordpressContentEntities\AdrotateAdEntity');
        $definition
            ->addArgument($di->getDefinition('logger'))
            ->addMethodCall('setDbal', [$di->get('site.db')]);
        
        $di->get('factory.contentIO')->registerHandler($this->getSystemName(), $di->get($wrapperId));
    }
    
    /**
     * Handler to register Widget (Edit Screen)
     * @return void
     */
    public function registerWidgetHandler()
    {
    }
    
    /**
     * @return void
     */
    public function registerFilters()
    {
        // see example in ContentTypePage
    }
    
    /**
     * Base type can be 'post' or 'term' used for Multilingual Press plugin.
     * @return string
     */
    public function getBaseType()
    {
        return 'virtual';
    }
    
    public function isVirtual()
    {
        return true;
    }
}
