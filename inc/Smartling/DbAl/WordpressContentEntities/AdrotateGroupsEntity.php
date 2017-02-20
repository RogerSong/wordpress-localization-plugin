<?php

namespace Smartling\DbAl\WordpressContentEntities;

/**
 * Class AdrotateGroupsEntity
 * @package Smartling\DbAl\WordpressContentEntities
 */
class AdrotateGroupsEntity extends AdrotateBaseEntityAbstract
{
    /**
     * @return array;
     */
    public function getMetadata()
    {
        return [];
    }
    
    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->name;
    }
    
    /**
     * @param string $tagName
     * @param string $tagValue
     * @param bool   $unique
     */
    public function setMetaTag($tagName, $tagValue, $unique = true)
    {
        
    }
  
    /**
     * @return array
     */
    protected function getNonClonableFields()
    {
        return ['id'];
    }
    
    /**
     * @return string
     */
    public function getPrimaryFieldName()
    {
        return 'id';
    }
    
    /**
     * Converts instance of EntityAbstract to array to be used for BulkSubmit screen
     *
     * @return array
     */
    public function toBulkSubmitScreenRow()
    {
        return [
            'id'         => (int)$this->{$this->getPrimaryFieldName()},
            'title'      => $this->getTitle(),
            'type'       => $this->getType(),
            'author'     => null,
            'status'     => null,
            'locales'    => null,
            'updated'    => null,
        ];
    }
    
    /**
     * Static functions
     */
    
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'adrotate_groups';
    }
    
    /**
     * @return array
     */
    public static function getFieldDefinitions()
    {
        // add all fields.
        return [
            'id',
            'name',
            'modus',
            'fallback',
            'cat',
            'cat_loc',
            'cat_par',
            'page',
            'page_loc',
            'page_par',
            'mobile',
            'geo',
            'wrapper_before',
            'wrapper_after',
            'align',
            'gridrows',
            'gridcolumns',
            'admargin',
            'admargin_bottom',
            'admargin_left',
            'admargin_right',
            'adwidth',
            'adheight',
            'adspeed'
        ];
    }
}