<?php
namespace Smartling\Tests\QueryBuilder;

use Smartling\Helpers\QueryBuilder\Condition\Condition;
use Smartling\Helpers\QueryBuilder\Condition\ConditionBuilder;

/**
 * Class ConditionTest
 */
class ConditionTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testConditionValidation()
	{
		$conditionType = ConditionBuilder::CONDITION_SIGN_EQ;

		$field = 'foo';

		Condition::getCondition($conditionType, $field, [])->__toString();
	}

	public function testEqCondition()
	{
		$conditionType = ConditionBuilder::CONDITION_SIGN_EQ;

		$field = 'foo';

		$value = 'bar';

		$expectedResult = "`{$field}` = '{$value}'";

		$condition = Condition::getCondition($conditionType, $field, [$value]);
		$actualResult = $condition->__toString();

		self::assertTrue($actualResult === $expectedResult);
	}

	public function testAutomaticValueEscapingSlash()
	{
		$conditionType = ConditionBuilder::CONDITION_SIGN_EQ;

		$field = 'foo';

		$value = 'b\\ar';

		$expectedValue = 'b\\\\ar';

		$expectedResult = "`{$field}` = '{$expectedValue}'";

		$condition = Condition::getCondition($conditionType, $field, [$value]);
		$actualResult = $condition->__toString();

		self::assertTrue($actualResult === $expectedResult);
	}

	public function testAutomaticValueEscapingSingleQuote()
	{
		$conditionType = ConditionBuilder::CONDITION_SIGN_EQ;

		$field = 'foo';

		$value = 'b\'ar';

		$expectedValue = 'b\\\'ar';

		$expectedResult = "`{$field}` = '{$expectedValue}'";

		$condition = Condition::getCondition($conditionType, $field, [$value]);
		$actualResult = $condition->__toString();

		self::assertTrue($actualResult === $expectedResult);
	}

	public function testAutomaticValueEscapingDoubleQuote()
	{
		$conditionType = ConditionBuilder::CONDITION_SIGN_EQ;

		$field = 'foo';

		$value = 'b"ar';

		$expectedValue = 'b\\"ar';

		$expectedResult = "`{$field}` = '{$expectedValue}'";

		$condition = Condition::getCondition($conditionType, $field, [$value]);
		$actualResult = $condition->__toString();

		self::assertTrue($actualResult === $expectedResult);
	}

	public function testMoreCondition()
	{
		$conditionType = ConditionBuilder::CONDITION_SIGN_MORE;

		$field = 'foo';

		$value = 'bar';

		$expectedResult = "`{$field}` > '{$value}'";

		$condition = Condition::getCondition($conditionType, $field, [$value]);
		$actualResult = $condition->__toString();

		self::assertTrue($actualResult === $expectedResult);
	}

	public function testMoreOrEqCondition()
	{
		$conditionType = ConditionBuilder::CONDITION_SIGN_MORE_OR_EQ;

		$field = 'foo';

		$value = 'bar';

		$expectedResult = "`{$field}` >= '{$value}'";

		$condition = Condition::getCondition($conditionType, $field, [$value]);
		$actualResult = $condition->__toString();

		self::assertTrue($actualResult === $expectedResult);
	}

	public function testLessCondition()
	{
		$conditionType = ConditionBuilder::CONDITION_SIGN_LESS;

		$field = 'foo';

		$value = 'bar';

		$expectedResult = "`{$field}` < '{$value}'";

		$condition = Condition::getCondition($conditionType, $field, [$value]);
		$actualResult = $condition->__toString();

		self::assertTrue($actualResult === $expectedResult);
	}

	public function testLessOrEqCondition()
	{
		$conditionType = ConditionBuilder::CONDITION_SIGN_LESS_OR_EQ;

		$field = 'foo';

		$value = 'bar';

		$expectedResult = "`{$field}` <= '{$value}'";

		$condition = Condition::getCondition($conditionType, $field, [$value]);
		$actualResult = $condition->__toString();

		self::assertTrue($actualResult === $expectedResult);
	}

	public function testNotEqCondition()
	{
		$conditionType = ConditionBuilder::CONDITION_SIGN_NOT_EQ;

		$field = 'foo';

		$value = 'bar';

		$expectedResult = "`{$field}` <> '{$value}'";

		$condition = Condition::getCondition($conditionType, $field, [$value]);
		$actualResult = $condition->__toString();

		self::assertTrue($actualResult === $expectedResult);
	}

	public function testLikeCondition()
	{
		$conditionType = ConditionBuilder::CONDITION_SIGN_LIKE;

		$field = 'foo';

		$value = 'bar';

		$expectedResult = "`{$field}` LIKE '{$value}'";

		$condition = Condition::getCondition($conditionType, $field, [$value]);
		$actualResult = $condition->__toString();

		self::assertTrue($actualResult === $expectedResult);
	}

	public function testBetweenCondition()
	{
		$conditionType = ConditionBuilder::CONDITION_SIGN_BETWEEN;

		$field = 'foo';

		$value = 'bar';

		$anotherValue = 'sar';

		$expectedResult = "`{$field}` BETWEEN '{$value}' AND '{$anotherValue}'";

		$condition = Condition::getCondition($conditionType, $field, [$value, $anotherValue]);
		$actualResult = $condition->__toString();

		self::assertTrue($actualResult === $expectedResult);
	}

	public function testInCondition()
	{
		$conditionType = ConditionBuilder::CONDITION_SIGN_IN;

		$field = 'foo';

		$values = ['bar', 'baz'];

		$expectedResult = vsprintf('`%s` IN(\'%s\', \'%s\')', [$field, reset($values), end($values)]);

		$condition = Condition::getCondition($conditionType, $field, $values);
		$actualResult = $condition->__toString();
		self::assertTrue($actualResult === $expectedResult);
	}

	public function testNotInCondition()
	{
		$conditionType = ConditionBuilder::CONDITION_SIGN_NOT_IN;

		$field = 'foo';

		$values = ['bar', 'baz'];

		$expectedResult = vsprintf('`%s` NOT IN(\'%s\', \'%s\')', [$field, reset($values), end($values)]);

		$condition = Condition::getCondition($conditionType, $field, $values);
		$actualResult = $condition->__toString();
		self::assertTrue($actualResult === $expectedResult);
	}
}