<?php

namespace Base;

use \GetVariables as ChildGetVariables;
use \GetVariablesQuery as ChildGetVariablesQuery;
use \Exception;
use \PDO;
use Map\GetVariablesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'get_variables' table.
 *
 * 
 *
 * @method     ChildGetVariablesQuery orderByQueryId($order = Criteria::ASC) Order by the query_id column
 * @method     ChildGetVariablesQuery orderByVariableName($order = Criteria::ASC) Order by the variable_name column
 * @method     ChildGetVariablesQuery orderByVariableValue($order = Criteria::ASC) Order by the variable_value column
 *
 * @method     ChildGetVariablesQuery groupByQueryId() Group by the query_id column
 * @method     ChildGetVariablesQuery groupByVariableName() Group by the variable_name column
 * @method     ChildGetVariablesQuery groupByVariableValue() Group by the variable_value column
 *
 * @method     ChildGetVariablesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildGetVariablesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildGetVariablesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildGetVariablesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildGetVariablesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildGetVariablesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildGetVariablesQuery leftJoinCachedRequests($relationAlias = null) Adds a LEFT JOIN clause to the query using the CachedRequests relation
 * @method     ChildGetVariablesQuery rightJoinCachedRequests($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CachedRequests relation
 * @method     ChildGetVariablesQuery innerJoinCachedRequests($relationAlias = null) Adds a INNER JOIN clause to the query using the CachedRequests relation
 *
 * @method     ChildGetVariablesQuery joinWithCachedRequests($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CachedRequests relation
 *
 * @method     ChildGetVariablesQuery leftJoinWithCachedRequests() Adds a LEFT JOIN clause and with to the query using the CachedRequests relation
 * @method     ChildGetVariablesQuery rightJoinWithCachedRequests() Adds a RIGHT JOIN clause and with to the query using the CachedRequests relation
 * @method     ChildGetVariablesQuery innerJoinWithCachedRequests() Adds a INNER JOIN clause and with to the query using the CachedRequests relation
 *
 * @method     \CachedRequestsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildGetVariables findOne(ConnectionInterface $con = null) Return the first ChildGetVariables matching the query
 * @method     ChildGetVariables findOneOrCreate(ConnectionInterface $con = null) Return the first ChildGetVariables matching the query, or a new ChildGetVariables object populated from the query conditions when no match is found
 *
 * @method     ChildGetVariables findOneByQueryId(int $query_id) Return the first ChildGetVariables filtered by the query_id column
 * @method     ChildGetVariables findOneByVariableName(string $variable_name) Return the first ChildGetVariables filtered by the variable_name column
 * @method     ChildGetVariables findOneByVariableValue(string $variable_value) Return the first ChildGetVariables filtered by the variable_value column *

 * @method     ChildGetVariables requirePk($key, ConnectionInterface $con = null) Return the ChildGetVariables by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGetVariables requireOne(ConnectionInterface $con = null) Return the first ChildGetVariables matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildGetVariables requireOneByQueryId(int $query_id) Return the first ChildGetVariables filtered by the query_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGetVariables requireOneByVariableName(string $variable_name) Return the first ChildGetVariables filtered by the variable_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGetVariables requireOneByVariableValue(string $variable_value) Return the first ChildGetVariables filtered by the variable_value column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildGetVariables[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildGetVariables objects based on current ModelCriteria
 * @method     ChildGetVariables[]|ObjectCollection findByQueryId(int $query_id) Return ChildGetVariables objects filtered by the query_id column
 * @method     ChildGetVariables[]|ObjectCollection findByVariableName(string $variable_name) Return ChildGetVariables objects filtered by the variable_name column
 * @method     ChildGetVariables[]|ObjectCollection findByVariableValue(string $variable_value) Return ChildGetVariables objects filtered by the variable_value column
 * @method     ChildGetVariables[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class GetVariablesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\GetVariablesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\GetVariables', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildGetVariablesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildGetVariablesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildGetVariablesQuery) {
            return $criteria;
        }
        $query = new ChildGetVariablesQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$query_id, $variable_name] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildGetVariables|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(GetVariablesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = GetVariablesTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildGetVariables A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT query_id, variable_name, variable_value FROM get_variables WHERE query_id = :p0 AND variable_name = :p1';
        try {
            $stmt = $con->prepare($sql);            
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);            
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildGetVariables $obj */
            $obj = new ChildGetVariables();
            $obj->hydrate($row);
            GetVariablesTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildGetVariables|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildGetVariablesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(GetVariablesTableMap::COL_QUERY_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(GetVariablesTableMap::COL_VARIABLE_NAME, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildGetVariablesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(GetVariablesTableMap::COL_QUERY_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(GetVariablesTableMap::COL_VARIABLE_NAME, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the query_id column
     *
     * Example usage:
     * <code>
     * $query->filterByQueryId(1234); // WHERE query_id = 1234
     * $query->filterByQueryId(array(12, 34)); // WHERE query_id IN (12, 34)
     * $query->filterByQueryId(array('min' => 12)); // WHERE query_id > 12
     * </code>
     *
     * @see       filterByCachedRequests()
     *
     * @param     mixed $queryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGetVariablesQuery The current query, for fluid interface
     */
    public function filterByQueryId($queryId = null, $comparison = null)
    {
        if (is_array($queryId)) {
            $useMinMax = false;
            if (isset($queryId['min'])) {
                $this->addUsingAlias(GetVariablesTableMap::COL_QUERY_ID, $queryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($queryId['max'])) {
                $this->addUsingAlias(GetVariablesTableMap::COL_QUERY_ID, $queryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GetVariablesTableMap::COL_QUERY_ID, $queryId, $comparison);
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
     * @return $this|ChildGetVariablesQuery The current query, for fluid interface
     */
    public function filterByVariableName($variableName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($variableName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GetVariablesTableMap::COL_VARIABLE_NAME, $variableName, $comparison);
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
     * @return $this|ChildGetVariablesQuery The current query, for fluid interface
     */
    public function filterByVariableValue($variableValue = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($variableValue)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GetVariablesTableMap::COL_VARIABLE_VALUE, $variableValue, $comparison);
    }

    /**
     * Filter the query by a related \CachedRequests object
     *
     * @param \CachedRequests|ObjectCollection $cachedRequests The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildGetVariablesQuery The current query, for fluid interface
     */
    public function filterByCachedRequests($cachedRequests, $comparison = null)
    {
        if ($cachedRequests instanceof \CachedRequests) {
            return $this
                ->addUsingAlias(GetVariablesTableMap::COL_QUERY_ID, $cachedRequests->getQueryId(), $comparison);
        } elseif ($cachedRequests instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(GetVariablesTableMap::COL_QUERY_ID, $cachedRequests->toKeyValue('PrimaryKey', 'QueryId'), $comparison);
        } else {
            throw new PropelException('filterByCachedRequests() only accepts arguments of type \CachedRequests or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CachedRequests relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildGetVariablesQuery The current query, for fluid interface
     */
    public function joinCachedRequests($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CachedRequests');

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
            $this->addJoinObject($join, 'CachedRequests');
        }

        return $this;
    }

    /**
     * Use the CachedRequests relation CachedRequests object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \CachedRequestsQuery A secondary query class using the current class as primary query
     */
    public function useCachedRequestsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCachedRequests($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CachedRequests', '\CachedRequestsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildGetVariables $getVariables Object to remove from the list of results
     *
     * @return $this|ChildGetVariablesQuery The current query, for fluid interface
     */
    public function prune($getVariables = null)
    {
        if ($getVariables) {
            $this->addCond('pruneCond0', $this->getAliasedColName(GetVariablesTableMap::COL_QUERY_ID), $getVariables->getQueryId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(GetVariablesTableMap::COL_VARIABLE_NAME), $getVariables->getVariableName(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the get_variables table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GetVariablesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            GetVariablesTableMap::clearInstancePool();
            GetVariablesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(GetVariablesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(GetVariablesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            GetVariablesTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            GetVariablesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // GetVariablesQuery
