<?php

namespace Smartling\DbAl\WordpressContentEntities;

/**
 * Class AdrotateAdEntity
 * @package Smartling\DbAl\WordpressContentEntities
 */
class AdrotateAdEntity extends AdrotateBaseEntityAbstract
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
        return $this->title;
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
            'author'     => $this->author,
            'status'     => null,
            'locales'    => null,
            'updated'    => date('Y-m-d H:i:s', $this->updated),
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
        return 'adrotate';
    }
    
    /**
     * @return array
     */
    public static function getFieldDefinitions()
    {
        // add all fields.
        return [
            'id',
            'title',
            'bannercode',
            'thetime',
            'updated',
            'author',
            'imagetype',
            'image',
            'paid',
            'tracker',
            'desktop',
            'mobile',
            'tablet',
            'os_ios',
            'os_android',
            'os_other',
            'responsive',
            'type',
            'weight',
            'budget',
            'crate',
            'irate',
            'cities',
            'countries'
        ];
    }
}