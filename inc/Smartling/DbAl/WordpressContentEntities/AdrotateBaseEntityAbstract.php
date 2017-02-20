<?php

namespace Smartling\DbAl\WordpressContentEntities;

use Psr\Log\LoggerInterface;
use Smartling\DbAl\SmartlingToCMSDatabaseAccessWrapperInterface;
use Smartling\Helpers\ArrayHelper;
use Smartling\Helpers\QueryBuilder\Condition\Condition;
use Smartling\Helpers\QueryBuilder\Condition\ConditionBlock;
use Smartling\Helpers\QueryBuilder\Condition\ConditionBuilder;
use Smartling\Helpers\QueryBuilder\QueryBuilder;

/**
 * Class AdrotateBaseEntityAbstract
 * @package Smartling\DbAl\WordpressContentEntities
 */
abstract class AdrotateBaseEntityAbstract extends VirtualEntityAbstract
{
    /**
     * @var array
     */
    protected $stateFields = [];
    
    /**
     * @var array
     */
    protected $initialFields = [];
    
    /** @var array */
    protected $fields = [];
    
    /** @var SmartlingToCMSDatabaseAccessWrapperInterface */
    private $dbal;
    
    /**
     * AdrotateAdEntity constructor.
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        parent::__construct($logger);
        
        $this->fields = $this->getFields();
        
        foreach (static::getFieldDefinitions() as $fieldName) {
            $this->stateFields[$fieldName] = '';
        }
        
        $this->setEntityFields($this->fields);
    }
    
    /**
     * Stores entity to database
     *
     * @param EntityAbstract $entity
     *
     * @return mixed
     */
    public function set(EntityAbstract $entity = null)
    {
        $originalEntity = json_encode($entity->toArray(false));
        $this->getLogger()->debug(vsprintf('Starting saving adrotate entity: %s', [$originalEntity]));
        $is_insert = in_array($entity->id, [0, null], true);
        $fields = $entity->toArray();
        
        foreach ($fields as $field => $value) {
            if (null === $value) {
                unset($fields[$field]);
            }
        }
        
        if (0 === count($fields)) {
            $this->getLogger()->debug(vsprintf('No data has been modified since load. Skipping save', []));
            
            return $entity;
        }
        
        if (array_key_exists('id', $fields)) {
            unset ($fields['id']);
        }
        
        $tableName = $this->getDbal()->completeTableName(static::getTableName());
        
        if ($is_insert) {
            $storeQuery = QueryBuilder::buildInsertQuery($tableName, $fields);
        } else {
            // update
            $conditionBlock = ConditionBlock::getConditionBlock();
            $conditionBlock->addCondition(
                Condition::getCondition(ConditionBuilder::CONDITION_SIGN_EQ, 'id', [$entity->id])
            );
            $storeQuery = QueryBuilder::buildUpdateQuery($tableName, $fields, $conditionBlock, ['limit' => 1]);
        }
        
        // log store query before execution
        $this->logQuery($storeQuery);
        
        $result = $this->getDbal()->query($storeQuery);
        
        if (false === $result) {
            $message = vsprintf(
                'Failed saving adrotate entity to database with following error message: %s',
                [
                    $this->getDbal()->getLastErrorMessage(),
                ]
            );
            $this->getLogger()->error($message);
        }
        
        if (true === $is_insert && false !== $result) {
            $entityFields = $entity->toArray(false);
            $entityFields['id'] = $this->getDbal()->getLastInsertedId();
            // update reference to entity
            $entity = $this->resultToEntity($entityFields);
        }
        $this->getLogger()->debug(
            vsprintf('Finished saving adrotate entity: %s. id=%s', [$originalEntity, $entity->getId()])
        );
        
        return $entity;
    }
    
    
    /**
     * @return \Smartling\DbAl\SmartlingToCMSDatabaseAccessWrapperInterface
     */
    public function getDbal()
    {
        return $this->dbal;
    }
    
    /**
     * @param \Smartling\DbAl\SmartlingToCMSDatabaseAccessWrapperInterface $dbal
     */
    public function setDbal($dbal)
    {
        $this->dbal = $dbal;
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
     * Get all entities.
     *
     * @param string $limit
     * @param int    $offset
     * @param bool   $orderBy
     * @param bool   $order
     *
     * @return array Array of entities
     */
    public function getAll(
        $limit = '',
        $offset = 0,
        $orderBy = false,
        $order = false
    ) {
        $page = $limit && $offset > 0 ? $offset/$limit + 1 : 1;
        $pageOptions = '' === $limit ? null : ['limit' => $limit, 'page' => $page];
        $query = QueryBuilder::buildSelectQuery(
            $this->getDbal()->completeTableName(static::getTableName()),
            static::getFieldDefinitions(),
            null,
            // Sort options
            [],
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
        
        return $result;
    }
    
    /**
     * Get total count of entities.
     *
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
     * Get fields.
     *
     * @return mixed
     */
    public function getFields()
    {
        return static::getFieldDefinitions();
    }
    
    /**
     * Log query.
     *
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
                $entity->{$fieldName} = $arr[$fieldName];
            }
        }
        
        return $entity;
    }
}
