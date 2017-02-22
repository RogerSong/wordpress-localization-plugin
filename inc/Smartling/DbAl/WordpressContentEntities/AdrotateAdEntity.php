<?php

namespace Smartling\DbAl\WordpressContentEntities;

use Smartling\Helpers\QueryBuilder\Condition\Condition;
use Smartling\Helpers\QueryBuilder\Condition\ConditionBlock;
use Smartling\Helpers\QueryBuilder\Condition\ConditionBuilder;
use Smartling\Helpers\QueryBuilder\QueryBuilder;

/**
 * Class AdrotateAdEntity
 * @package Smartling\DbAl\WordpressContentEntities
 */
class AdrotateAdEntity extends AdrotateBaseEntityAbstract
{
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
            'countries',
        ];
    }
    
    /**
     * @return array;
     */
    public function getMetadata()
    {
        $entity = new AdrotateLinkmetaEntity($this->getLogger(),
            $this->getDbal());
        $conditionBlock = new ConditionBlock(ConditionBuilder::CONDITION_BLOCK_LEVEL_OPERATOR_AND);
        $condition = Condition::getCondition(
            ConditionBuilder::CONDITION_SIGN_EQ,
            'ad',
            [$this->getId()]
        );
        $conditionBlock->addCondition($condition);
        
        return $entity->getByCondition($conditionBlock);
    }
    
    /**
     * @param string $tagName
     * @param string $tagValue
     * @param bool   $unique
     */
    public function setMetaTag($tagName, $tagValue, $unique = true)
    {
        // Check if already exists.
        /** @var AdrotateLinkmetaEntity $entity */
        $entity = new AdrotateLinkmetaEntity($this->getLogger(),
            $this->getDbal());
        $conditionBlock = new ConditionBlock(ConditionBuilder::CONDITION_BLOCK_LEVEL_OPERATOR_AND);
        
        // Check if values are not empty.
        if (is_array($tagValue) && !empty($tagValue)) {
            foreach ($tagValue as $fieldName => $fieldValue) {
                $condition = Condition::getCondition(
                    ConditionBuilder::CONDITION_SIGN_EQ,
                    $fieldName,
                    [$fieldValue]
                );
                $conditionBlock->addCondition($condition);
            }
            
            $fetchResult = $entity->getByCondition($conditionBlock);
    
            if (count($fetchResult) == 0) {
                // Store linkmeta entity in DB.
                $tableName = $this->getDbal()->completeTableName($entity::getTableName());
                $storeQuery = QueryBuilder::buildInsertQuery(
                    $tableName,
                    $tagValue
                );
                $this->logQuery($storeQuery);
                $this->getDbal()->query($storeQuery);
            } else {
                $this->getLogger()->debug(
                    sprintf(
                        'Row with these values %s already exists in adrotate_linkmeta table',
                        json_decode($tagValue)
                    )
                );
            }
        } else {
            $this->getLogger()->debug(
                sprintf('Field values %s are empty', json_decode($tagValue))
            );
        }
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
            'title'   => $this->getTitle(),
            'type'    => $this->getType(),
            'author'  => $this->author,
            'status'  => null,
            'locales' => null,
            'updated' => date('Y-m-d H:i:s', $this->updated),
        ];
    }
    
    /**
     * @return string
     */
    public function getPrimaryFieldName()
    {
        return 'id';
    }
    
    /**
     * Static functions
     */
    
    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * @return array
     */
    protected function getNonClonableFields()
    {
        return ['id'];
    }
}