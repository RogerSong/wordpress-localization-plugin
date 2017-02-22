<?php

namespace Smartling\DbAl\WordpressContentEntities;

/**
 * Class AdrotateLinkmetaEntity
 * @package Smartling\DbAl\WordpressContentEntities
 */
class AdrotateLinkmetaEntity extends AdrotateBaseEntityAbstract
{
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'adrotate_linkmeta';
    }
    
    /**
     * @return array
     */
    public static function getFieldDefinitions()
    {
        // add all fields.
        return [
            'id',
            'ad',
            'group',
            'user',
            'schedule',
        ];
    }
    
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
        return '';
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
     * Converts instance of EntityAbstract to array to be used for BulkSubmit screen
     *
     * @return array
     */
    public function toBulkSubmitScreenRow()
    {
        return [
            'id'      => (int)$this->{$this->getPrimaryFieldName()},
            'title'   => null,
            'type'    => null,
            'author'  => null,
            'status'  => null,
            'locales' => null,
            'updated' => null,
        ];
    }
    
    /**
     * Static functions
     */
    
    /**
     * @return string
     */
    public function getPrimaryFieldName()
    {
        return 'id';
    }
    
    /**
     * @return array
     */
    protected function getNonClonableFields()
    {
        return ['id'];
    }
}