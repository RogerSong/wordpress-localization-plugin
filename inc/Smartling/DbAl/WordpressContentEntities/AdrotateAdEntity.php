<?php

namespace Smartling\DbAl\WordpressContentEntities;

use Psr\Log\LoggerInterface;
use Smartling\Bootstrap;
use Smartling\DbAl\SmartlingToCMSDatabaseAccessWrapperInterface;
use Smartling\Helpers\ArrayHelper;
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
     * Loads the entity from database
     *
     * @param $guid
     *
     * @return EntityAbstract
     */
    public function get($guid)
    {
        $block = new ConditionBlock(ConditionBuilder::CONDITION_BLOCK_LEVEL_OPERATOR_AND);
        $condition = Condition::getCondition(ConditionBuilder::CONDITION_SIGN_EQ, 'id', [$guid]);
        $block->addCondition($condition);
        $query = QueryBuilder::buildSelectQuery(
            $this->getDbal()->completeTableName(static::getTableName()),
            static::getFieldDefinitions(),
            $block
        );
    
        $queryResult = $this->getDbal()->fetch($query, \ARRAY_A);
        $result = [];
    
        if (is_array($queryResult) && 1 === count($queryResult)) {
            foreach ($queryResult as $row) {
                $result[] = $this->resultToEntity($row);
            }
        }
    
        return ArrayHelper::first($result);
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
     * @param string $limit
     * @param int    $offset
     * @param bool   $orderBy
     * @param bool   $order
     *
     * @return mixed
     */
    public function getAll(
        $limit = '',
        $offset = 0,
        $orderBy = false,
        $order = false
    ) {
        // todo: check offset
        $pageOptions = '' === $limit ? null : ['limit' => $limit, 'page' => 1];
        $sortOptions = [
//            'orderBy' => $orderBy,
//            'order'   => $order
        ];
        $query = QueryBuilder::buildSelectQuery(
            $this->getDbal()->completeTableName(static::getTableName()),
            static::getFieldDefinitions(),
            null,
            // Sort options
            array_filter($sortOptions),
            // Page options
            $pageOptions
        );
    
        $queryResult = $this->getDbal()->fetch($query, \ARRAY_A);
        
        $result = [];
    
        if (is_array($queryResult)) {
            foreach ($queryResult as $row) {
                $result[] = $this->resultToEntity($row);
            }
        }
    
//        Bootstrap::DebugPrint($queryResult, true);
        return $result;
    }
    
    /**
     * @return int
     */
    public function getTotal()
    {
        $count = 0;
        $query = QueryBuilder::buildSelectQuery(
            $this->getDbal()->completeTableName(static::getTableName()),
            [['COUNT(*)' => 'cnt']]
        );
        
        $queryResult = $this->getDbal()->fetch($query, \ARRAY_A);
        
        if (is_array($queryResult)) {
            $firstEl = ArrayHelper::first($queryResult);
            $count = $firstEl['cnt'];
        }
        
        return $count;
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
            'title'      => $this->title,
            'type'       => null,
            'author'     => $this->author,
            'status'     => null,
            'locales'    => null,
            'updated'    => date('F d, Y g:i a', $this->updated),
        ];
    }
    
    public function getFields()
    {
        return static::getFieldDefinitions();
    }
    
    /**
     * @param string $query
     */
    public function logQuery($query)
    {
        if (true === $this->getDbal()->needRawSqlLog()) {
            $this->getLogger()->debug($query);
        }
    }
    
    /**
     * Converts object into EntityAbstract child
     *
     * @param array          $arr
     * @param EntityAbstract $entity
     *
     * @return EntityAbstract
     */
    protected function resultToEntity(array $arr)
    {
        $className = get_class($this);
        $entity = new $className($this->getLogger());
        
        foreach ($this->fields as $fieldName) {
            if (array_key_exists($fieldName, $arr)) {
                $entity->$fieldName = $arr[$fieldName];
            }
        }
        $entity->hash = '';
        
        Bootstrap::DebugPrint($entity, true);
        return $entity;
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