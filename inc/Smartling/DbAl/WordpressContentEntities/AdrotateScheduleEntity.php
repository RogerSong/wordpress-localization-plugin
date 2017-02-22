<?php

namespace Smartling\DbAl\WordpressContentEntities;

/**
 * Class AdrotateScheduleEntity
 * @package Smartling\DbAl\WordpressContentEntities
 */
class AdrotateScheduleEntity extends AdrotateBaseEntityAbstract
{
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'adrotate_schedule';
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
            'starttime',
            'stoptime',
            'maxclicks',
            'maximpressions',
            'spread',
            'daystarttime',
            'daystoptime',
            'day_mon',
            'day_tue',
            'day_wed',
            'day_thu',
            'day_fri',
            'day_sat',
            'day_sun',
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
     * Converts instance of EntityAbstract to array to be used for BulkSubmit screen
     *
     * @return array
     */
    public function toBulkSubmitScreenRow()
    {
        return [
            'id'      => (int)$this->{$this->getPrimaryFieldName()},
            'title'   => $this->name,
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