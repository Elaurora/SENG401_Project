<?php

namespace Base;

use \CacheMatchVariables as ChildCacheMatchVariables;
use \CacheMatchVariablesQuery as ChildCacheMatchVariablesQuery;
use \Exception;
use \PDO;
use Map\CacheMatchVariablesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'cache_match_variables' table.
 *
 * 
 *
 * @method     ChildCacheMatchVariablesQuery orderByRuleId($order = Criteria::ASC) Order by the rule_id column
 * @method     ChildCacheMatchVariablesQuery orderByVariableName($order = Criteria::ASC) Order by the variable_name column
 * @method     ChildCacheMatchVariablesQuery orderByVariableValue($order = Criteria::ASC) Order by the variable_value column
 *
 * @method     ChildCacheMatchVariablesQuery groupByRuleId() Group by the rule_id column
 * @method     ChildCacheMatchVariablesQuery groupByVariableName() Group by the variable_name column
 * @method     ChildCacheMatchVariablesQuery groupByVariableValue() Group by the variable_value column
 *
 * @method     ChildCacheMatchVariablesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCacheMatchVariablesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCacheMatchVariablesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCacheMatchVariablesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCacheMatchVariablesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCacheMatchVariablesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCacheMatchVariablesQuery leftJoinCacheRules($relationAlias = null) Adds a LEFT JOIN clause to the query using the CacheRules relation
 * @method     ChildCacheMatchVariablesQuery rightJoinCacheRules($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CacheRules relation
 * @method     ChildCacheMatchVariablesQuery innerJoinCacheRules($relationAlias = null) Adds a INNER JOIN clause to the query using the CacheRules relation
 *
 * @method     ChildCacheMatchVariablesQuery joinWithCacheRules($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CacheRules relation
 *
 * @method     ChildCacheMatchVariablesQuery leftJoinWithCacheRules() Adds a LEFT JOIN clause and with to the query using the CacheRules relation
 * @method     ChildCacheMatchVariablesQuery rightJoinWithCacheRules() Adds a RIGHT JOIN clause and with to the query using the CacheRules relation
 * @method     ChildCacheMatchVariablesQuery innerJoinWithCacheRules() Adds a INNER JOIN clause and with to the query using the CacheRules relation
 *
 * @method     \CacheRulesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildCacheMatchVariables findOne(ConnectionInterface $con = null) Return the first ChildCacheMatchVariables matching the query
 * @method     ChildCacheMatchVariables findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCacheMatchVariables matching the query, or a new ChildCacheMatchVariables object populated from the query conditions when no match is found
 *
 * @method     ChildCacheMatchVariables findOneByRuleId(int $rule_id) Return the first ChildCacheMatchVariables filtered by the rule_id column
 * @method     ChildCacheMatchVariables findOneByVariableName(string $variable_name) Return the first ChildCacheMatchVariables filtered by the variable_name column
 * @method     ChildCacheMatchVariables findOneByVariableValue(string $variable_value) Return the first ChildCacheMatchVariables filtered by the variable_value column *

 * @method     ChildCacheMatchVariables requirePk($key, ConnectionInterface $con = null) Return the ChildCacheMatchVariables by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCacheMatchVariables requireOne(ConnectionInterface $con = null) Return the first ChildCacheMatchVariables matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCacheMatchVariables requireOneByRuleId(int $rule_id) Return the first ChildCacheMatchVariables filtered by the rule_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCacheMatchVariables requireOneByVariableName(string $variable_name) Return the first ChildCacheMatchVariables filtered by the variable_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCacheMatchVariables requireOneByVariableValue(string $variable_value) Return the first ChildCacheMatchVariables filtered by the variable_value column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCacheMatchVariables[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCacheMatchVariables objects based on current ModelCriteria
 * @method     ChildCacheMatchVariables[]|ObjectCollection findByRuleId(int $rule_id) Return ChildCacheMatchVariables objects filtered by the rule_id column
 * @method     ChildCacheMatchVariables[]|ObjectCollection findByVariableName(string $variable_name) Return ChildCacheMatchVariables objects filtered by the variable_name column
 * @method     ChildCacheMatchVariables[]|ObjectCollection findByVariableValue(string $variable_value) Return ChildCacheMatchVariables objects filtered by the variable_value column
 * @method     ChildCacheMatchVariables[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CacheMatchVariablesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\CacheMatchVariablesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\CacheMatchVariables', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCacheMatchVariablesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCacheMatchVariablesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCacheMatchVariablesQuery) {
            return $criteria;
        }
        $query = new ChildCacheMatchVariablesQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj = $c->findPk(array(12, 34, 56), $con);
     * </code>
     *
     * @param array[$rule_id, $variable_name, $variable_value] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildCacheMatchVariables|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CacheMatchVariablesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CacheMatchVariablesTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]))))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCacheMatchVariables A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT rule_id, variable_name, variable_value FROM cache_match_variables WHERE rule_id = :p0 AND variable_name = :p1 AND variable_value = :p2';
        try {
            $stmt = $con->prepare($sql);            
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);            
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_STR);            
            $stmt->bindValue(':p2', $key[2], PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildCacheMatchVariables $obj */
            $obj = new ChildCacheMatchVariables();
            $obj->hydrate($row);
            CacheMatchVariablesTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]));
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildCacheMatchVariables|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildCacheMatchVariablesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(CacheMatchVariablesTableMap::COL_RULE_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(CacheMatchVariablesTableMap::COL_VARIABLE_NAME, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(CacheMatchVariablesTableMap::COL_VARIABLE_VALUE, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCacheMatchVariablesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(CacheMatchVariablesTableMap::COL_RULE_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(CacheMatchVariablesTableMap::COL_VARIABLE_NAME, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(CacheMatchVariablesTableMap::COL_VARIABLE_VALUE, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the rule_id column
     *
     * Example usage:
     * <code>
     * $query->filterByRuleId(1234); // WHERE rule_id = 1234
     * $query->filterByRuleId(array(12, 34)); // WHERE rule_id IN (12, 34)
     * $query->filterByRuleId(array('min' => 12)); // WHERE rule_id > 12
     * </code>
     *
     * @see       filterByCacheRules()
     *
     * @param     mixed $ruleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCacheMatchVariablesQuery The current query, for fluid interface
     */
    public function filterByRuleId($ruleId = null, $comparison = null)
    {
        if (is_array($ruleId)) {
            $useMinMax = false;
            if (isset($ruleId['min'])) {
                $this->addUsingAlias(CacheMatchVariablesTableMap::COL_RULE_ID, $ruleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ruleId['max'])) {
                $this->addUsingAlias(CacheMatchVariablesTableMap::COL_RULE_ID, $ruleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CacheMatchVariablesTableMap::COL_RULE_ID, $ruleId, $comparison);
    }

    /**
     * Filter the query on the variable_name column
     *
     * Example usage:
     * <code>
     * $query->filterByVariableName('fooValue');   // WHERE variable_name = 'fooValue'
     * $query->filterByVariableName('%fooValue%', Criteria::LIKE); // WHERE variable_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $variableName The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCacheMatchVariablesQuery The current query, for fluid interface
     */
    public function filterByVariableName($variableName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($variableName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CacheMatchVariablesTableMap::COL_VARIABLE_NAME, $variableName, $comparison);
    }

    /**
     * Filter the query on the variable_value column
     *
     * Example usage:
     * <code>
     * $query->filterByVariableValue('fooValue');   // WHERE variable_value = 'fooValue'
     * $query->filterByVariableValue('%fooValue%', Criteria::LIKE); // WHERE variable_value LIKE '%fooValue%'
     * </code>
     *
     * @param     string $variableValue The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCacheMatchVariablesQuery The current query, for fluid interface
     */
    public function filterByVariableValue($variableValue = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($variableValue)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CacheMatchVariablesTableMap::COL_VARIABLE_VALUE, $variableValue, $comparison);
    }

    /**
     * Filter the query by a related \CacheRules object
     *
     * @param \CacheRules|ObjectCollection $cacheRules The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCacheMatchVariablesQuery The current query, for fluid interface
     */
    public function filterByCacheRules($cacheRules, $comparison = null)
    {
        if ($cacheRules instanceof \CacheRules) {
            return $this
                ->addUsingAlias(CacheMatchVariablesTableMap::COL_RULE_ID, $cacheRules->getRuleId(), $comparison);
        } elseif ($cacheRules instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CacheMatchVariablesTableMap::COL_RULE_ID, $cacheRules->toKeyValue('PrimaryKey', 'RuleId'), $comparison);
        } else {
            throw new PropelException('filterByCacheRules() only accepts arguments of type \CacheRules or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CacheRules relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCacheMatchVariablesQuery The current query, for fluid interface
     */
    public function joinCacheRules($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CacheRules');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'CacheRules');
        }

        return $this;
    }

    /**
     * Use the CacheRules relation CacheRules object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \CacheRulesQuery A secondary query class using the current class as primary query
     */
    public function useCacheRulesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCacheRules($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CacheRules', '\CacheRulesQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCacheMatchVariables $cacheMatchVariables Object to remove from the list of results
     *
     * @return $this|ChildCacheMatchVariablesQuery The current query, for fluid interface
     */
    public function prune($cacheMatchVariables = null)
    {
        if ($cacheMatchVariables) {
            $this->addCond('pruneCond0', $this->getAliasedColName(CacheMatchVariablesTableMap::COL_RULE_ID), $cacheMatchVariables->getRuleId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(CacheMatchVariablesTableMap::COL_VARIABLE_NAME), $cacheMatchVariables->getVariableName(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(CacheMatchVariablesTableMap::COL_VARIABLE_VALUE), $cacheMatchVariables->getVariableValue(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the cache_match_variables table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CacheMatchVariablesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CacheMatchVariablesTableMap::clearInstancePool();
            CacheMatchVariablesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CacheMatchVariablesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CacheMatchVariablesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            CacheMatchVariablesTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            CacheMatchVariablesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // CacheMatchVariablesQuery
