<?php

namespace Base;

use \CacheRules as ChildCacheRules;
use \CacheRulesQuery as ChildCacheRulesQuery;
use \Exception;
use \PDO;
use Map\CacheRulesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'cache_rules' table.
 *
 * 
 *
 * @method     ChildCacheRulesQuery orderByRuleId($order = Criteria::ASC) Order by the rule_id column
 * @method     ChildCacheRulesQuery orderByLocalTtl($order = Criteria::ASC) Order by the local_ttl column
 * @method     ChildCacheRulesQuery orderByGlobalTtl($order = Criteria::ASC) Order by the global_ttl column
 *
 * @method     ChildCacheRulesQuery groupByRuleId() Group by the rule_id column
 * @method     ChildCacheRulesQuery groupByLocalTtl() Group by the local_ttl column
 * @method     ChildCacheRulesQuery groupByGlobalTtl() Group by the global_ttl column
 *
 * @method     ChildCacheRulesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCacheRulesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCacheRulesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCacheRulesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCacheRulesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCacheRulesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCacheRulesQuery leftJoinCacheMatchVariables($relationAlias = null) Adds a LEFT JOIN clause to the query using the CacheMatchVariables relation
 * @method     ChildCacheRulesQuery rightJoinCacheMatchVariables($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CacheMatchVariables relation
 * @method     ChildCacheRulesQuery innerJoinCacheMatchVariables($relationAlias = null) Adds a INNER JOIN clause to the query using the CacheMatchVariables relation
 *
 * @method     ChildCacheRulesQuery joinWithCacheMatchVariables($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CacheMatchVariables relation
 *
 * @method     ChildCacheRulesQuery leftJoinWithCacheMatchVariables() Adds a LEFT JOIN clause and with to the query using the CacheMatchVariables relation
 * @method     ChildCacheRulesQuery rightJoinWithCacheMatchVariables() Adds a RIGHT JOIN clause and with to the query using the CacheMatchVariables relation
 * @method     ChildCacheRulesQuery innerJoinWithCacheMatchVariables() Adds a INNER JOIN clause and with to the query using the CacheMatchVariables relation
 *
 * @method     \CacheMatchVariablesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildCacheRules findOne(ConnectionInterface $con = null) Return the first ChildCacheRules matching the query
 * @method     ChildCacheRules findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCacheRules matching the query, or a new ChildCacheRules object populated from the query conditions when no match is found
 *
 * @method     ChildCacheRules findOneByRuleId(string $rule_id) Return the first ChildCacheRules filtered by the rule_id column
 * @method     ChildCacheRules findOneByLocalTtl(int $local_ttl) Return the first ChildCacheRules filtered by the local_ttl column
 * @method     ChildCacheRules findOneByGlobalTtl(int $global_ttl) Return the first ChildCacheRules filtered by the global_ttl column *

 * @method     ChildCacheRules requirePk($key, ConnectionInterface $con = null) Return the ChildCacheRules by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCacheRules requireOne(ConnectionInterface $con = null) Return the first ChildCacheRules matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCacheRules requireOneByRuleId(string $rule_id) Return the first ChildCacheRules filtered by the rule_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCacheRules requireOneByLocalTtl(int $local_ttl) Return the first ChildCacheRules filtered by the local_ttl column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCacheRules requireOneByGlobalTtl(int $global_ttl) Return the first ChildCacheRules filtered by the global_ttl column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCacheRules[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCacheRules objects based on current ModelCriteria
 * @method     ChildCacheRules[]|ObjectCollection findByRuleId(string $rule_id) Return ChildCacheRules objects filtered by the rule_id column
 * @method     ChildCacheRules[]|ObjectCollection findByLocalTtl(int $local_ttl) Return ChildCacheRules objects filtered by the local_ttl column
 * @method     ChildCacheRules[]|ObjectCollection findByGlobalTtl(int $global_ttl) Return ChildCacheRules objects filtered by the global_ttl column
 * @method     ChildCacheRules[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CacheRulesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\CacheRulesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\CacheRules', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCacheRulesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCacheRulesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCacheRulesQuery) {
            return $criteria;
        }
        $query = new ChildCacheRulesQuery();
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
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildCacheRules|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CacheRulesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CacheRulesTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildCacheRules A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT rule_id, local_ttl, global_ttl FROM cache_rules WHERE rule_id = :p0';
        try {
            $stmt = $con->prepare($sql);            
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildCacheRules $obj */
            $obj = new ChildCacheRules();
            $obj->hydrate($row);
            CacheRulesTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildCacheRules|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(12, 56, 832), $con);
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
     * @return $this|ChildCacheRulesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CacheRulesTableMap::COL_RULE_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCacheRulesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CacheRulesTableMap::COL_RULE_ID, $keys, Criteria::IN);
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
     * @param     mixed $ruleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCacheRulesQuery The current query, for fluid interface
     */
    public function filterByRuleId($ruleId = null, $comparison = null)
    {
        if (is_array($ruleId)) {
            $useMinMax = false;
            if (isset($ruleId['min'])) {
                $this->addUsingAlias(CacheRulesTableMap::COL_RULE_ID, $ruleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ruleId['max'])) {
                $this->addUsingAlias(CacheRulesTableMap::COL_RULE_ID, $ruleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CacheRulesTableMap::COL_RULE_ID, $ruleId, $comparison);
    }

    /**
     * Filter the query on the local_ttl column
     *
     * Example usage:
     * <code>
     * $query->filterByLocalTtl(1234); // WHERE local_ttl = 1234
     * $query->filterByLocalTtl(array(12, 34)); // WHERE local_ttl IN (12, 34)
     * $query->filterByLocalTtl(array('min' => 12)); // WHERE local_ttl > 12
     * </code>
     *
     * @param     mixed $localTtl The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCacheRulesQuery The current query, for fluid interface
     */
    public function filterByLocalTtl($localTtl = null, $comparison = null)
    {
        if (is_array($localTtl)) {
            $useMinMax = false;
            if (isset($localTtl['min'])) {
                $this->addUsingAlias(CacheRulesTableMap::COL_LOCAL_TTL, $localTtl['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($localTtl['max'])) {
                $this->addUsingAlias(CacheRulesTableMap::COL_LOCAL_TTL, $localTtl['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CacheRulesTableMap::COL_LOCAL_TTL, $localTtl, $comparison);
    }

    /**
     * Filter the query on the global_ttl column
     *
     * Example usage:
     * <code>
     * $query->filterByGlobalTtl(1234); // WHERE global_ttl = 1234
     * $query->filterByGlobalTtl(array(12, 34)); // WHERE global_ttl IN (12, 34)
     * $query->filterByGlobalTtl(array('min' => 12)); // WHERE global_ttl > 12
     * </code>
     *
     * @param     mixed $globalTtl The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCacheRulesQuery The current query, for fluid interface
     */
    public function filterByGlobalTtl($globalTtl = null, $comparison = null)
    {
        if (is_array($globalTtl)) {
            $useMinMax = false;
            if (isset($globalTtl['min'])) {
                $this->addUsingAlias(CacheRulesTableMap::COL_GLOBAL_TTL, $globalTtl['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($globalTtl['max'])) {
                $this->addUsingAlias(CacheRulesTableMap::COL_GLOBAL_TTL, $globalTtl['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CacheRulesTableMap::COL_GLOBAL_TTL, $globalTtl, $comparison);
    }

    /**
     * Filter the query by a related \CacheMatchVariables object
     *
     * @param \CacheMatchVariables|ObjectCollection $cacheMatchVariables the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCacheRulesQuery The current query, for fluid interface
     */
    public function filterByCacheMatchVariables($cacheMatchVariables, $comparison = null)
    {
        if ($cacheMatchVariables instanceof \CacheMatchVariables) {
            return $this
                ->addUsingAlias(CacheRulesTableMap::COL_RULE_ID, $cacheMatchVariables->getRuleId(), $comparison);
        } elseif ($cacheMatchVariables instanceof ObjectCollection) {
            return $this
                ->useCacheMatchVariablesQuery()
                ->filterByPrimaryKeys($cacheMatchVariables->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCacheMatchVariables() only accepts arguments of type \CacheMatchVariables or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CacheMatchVariables relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCacheRulesQuery The current query, for fluid interface
     */
    public function joinCacheMatchVariables($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CacheMatchVariables');

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
            $this->addJoinObject($join, 'CacheMatchVariables');
        }

        return $this;
    }

    /**
     * Use the CacheMatchVariables relation CacheMatchVariables object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \CacheMatchVariablesQuery A secondary query class using the current class as primary query
     */
    public function useCacheMatchVariablesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCacheMatchVariables($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CacheMatchVariables', '\CacheMatchVariablesQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCacheRules $cacheRules Object to remove from the list of results
     *
     * @return $this|ChildCacheRulesQuery The current query, for fluid interface
     */
    public function prune($cacheRules = null)
    {
        if ($cacheRules) {
            $this->addUsingAlias(CacheRulesTableMap::COL_RULE_ID, $cacheRules->getRuleId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the cache_rules table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CacheRulesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CacheRulesTableMap::clearInstancePool();
            CacheRulesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CacheRulesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CacheRulesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            CacheRulesTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            CacheRulesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // CacheRulesQuery
