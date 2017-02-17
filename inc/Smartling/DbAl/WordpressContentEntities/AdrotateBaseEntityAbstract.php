<?php

namespace Smartling\DbAl\WordpressContentEntities;

use Psr\Log\LoggerInterface;
use Smartling\DbAl\SmartlingToCMSDatabaseAccessWrapperInterface;
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
        
        $this->setEntityFields($this->fields);
        
        foreach (static::getFieldDefinitions() as $fieldName) {
            $this->stateFields[$fieldName] = '';
        }
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
        
        $fields = static::getChangedFields($entity);
        
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
     * Get changed fields.
     *
     * @return array
     */
    public function getChangedFieldsHelper()
    {
        $curFields = $this->stateFields;
        $initialFields = $this->initialFields;
        $output = [];
        
        $fieldNames = array_keys($curFields);
        
        foreach ($fieldNames as $fieldName) {
            if (!array_key_exists($fieldName, $initialFields) ||
                (((string)$initialFields[$fieldName]) !== ((string)$curFields[$fieldName]))) {
                $output[$fieldName] = (string)$curFields[$fieldName];
            }
        }
        
        return $output;
    }
    
    /**
     * Static methods
     */
    
    /**
     * Get changed fields.
     *
     * @param \Smartling\DbAl\WordpressContentEntities\EntityAbstract $entity
     *
     * @return array
     */
    public static function getChangedFields(EntityAbstract $entity)
    {
        $id = $entity->id;
        
        $is_insert = in_array($id, [0, null], true);
        
        $fields = true === $is_insert
            ? $entity->toArray(false)
            : $entity->getChangedFieldsHelper();
        
        return $fields;
    }
    
}
