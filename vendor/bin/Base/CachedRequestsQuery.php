<?php

namespace Base;

use \CachedRequests as ChildCachedRequests;
use \CachedRequestsQuery as ChildCachedRequestsQuery;
use \Exception;
use \PDO;
use Map\CachedRequestsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'cached_requests' table.
 *
 * 
 *
 * @method     ChildCachedRequestsQuery orderByQueryId($order = Criteria::ASC) Order by the query_id column
 * @method     ChildCachedRequestsQuery orderByQueryResponse($order = Criteria::ASC) Order by the query_response column
 *
 * @method     ChildCachedRequestsQuery groupByQueryId() Group by the query_id column
 * @method     ChildCachedRequestsQuery groupByQueryResponse() Group by the query_response column
 *
 * @method     ChildCachedRequestsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCachedRequestsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCachedRequestsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCachedRequestsQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCachedRequestsQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCachedRequestsQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCachedRequestsQuery leftJoinGetVariables($relationAlias = null) Adds a LEFT JOIN clause to the query using the GetVariables relation
 * @method     ChildCachedRequestsQuery rightJoinGetVariables($relationAlias = null) Adds a RIGHT JOIN clause to the query using the GetVariables relation
 * @method     ChildCachedRequestsQuery innerJoinGetVariables($relationAlias = null) Adds a INNER JOIN clause to the query using the GetVariables relation
 *
 * @method     ChildCachedRequestsQuery joinWithGetVariables($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the GetVariables relation
 *
 * @method     ChildCachedRequestsQuery leftJoinWithGetVariables() Adds a LEFT JOIN clause and with to the query using the GetVariables relation
 * @method     ChildCachedRequestsQuery rightJoinWithGetVariables() Adds a RIGHT JOIN clause and with to the query using the GetVariables relation
 * @method     ChildCachedRequestsQuery innerJoinWithGetVariables() Adds a INNER JOIN clause and with to the query using the GetVariables relation
 *
 * @method     \GetVariablesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildCachedRequests findOne(ConnectionInterface $con = null) Return the first ChildCachedRequests matching the query
 * @method     ChildCachedRequests findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCachedRequests matching the query, or a new ChildCachedRequests object populated from the query conditions when no match is found
 *
 * @method     ChildCachedRequests findOneByQueryId(string $query_id) Return the first ChildCachedRequests filtered by the query_id column
 * @method     ChildCachedRequests findOneByQueryResponse(string $query_response) Return the first ChildCachedRequests filtered by the query_response column *

 * @method     ChildCachedRequests requirePk($key, ConnectionInterface $con = null) Return the ChildCachedRequests by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCachedRequests requireOne(ConnectionInterface $con = null) Return the first ChildCachedRequests matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCachedRequests requireOneByQueryId(string $query_id) Return the first ChildCachedRequests filtered by the query_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCachedRequests requireOneByQueryResponse(string $query_response) Return the first ChildCachedRequests filtered by the query_response column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCachedRequests[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCachedRequests objects based on current ModelCriteria
 * @method     ChildCachedRequests[]|ObjectCollection findByQueryId(string $query_id) Return ChildCachedRequests objects filtered by the query_id column
 * @method     ChildCachedRequests[]|ObjectCollection findByQueryResponse(string $query_response) Return ChildCachedRequests objects filtered by the query_response column
 * @method     ChildCachedRequests[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CachedRequestsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\CachedRequestsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\CachedRequests', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCachedRequestsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCachedRequestsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCachedRequestsQuery) {
            return $criteria;
        }
        $query = new ChildCachedRequestsQuery();
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
     * @return ChildCachedRequests|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CachedRequestsTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CachedRequestsTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildCachedRequests A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT query_id, query_response FROM cached_requests WHERE query_id = :p0';
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
            /** @var ChildCachedRequests $obj */
            $obj = new ChildCachedRequests();
            $obj->hydrate($row);
            CachedRequestsTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildCachedRequests|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildCachedRequestsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CachedRequestsTableMap::COL_QUERY_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCachedRequestsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CachedRequestsTableMap::COL_QUERY_ID, $keys, Criteria::IN);
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
     * @param     mixed $queryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCachedRequestsQuery The current query, for fluid interface
     */
    public function filterByQueryId($queryId = null, $comparison = null)
    {
        if (is_array($queryId)) {
            $useMinMax = false;
            if (isset($queryId['min'])) {
                $this->addUsingAlias(CachedRequestsTableMap::COL_QUERY_ID, $queryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($queryId['max'])) {
                $this->addUsingAlias(CachedRequestsTableMap::COL_QUERY_ID, $queryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CachedRequestsTableMap::COL_QUERY_ID, $queryId, $comparison);
    }

    /**
     * Filter the query on the query_response column
     *
     * Example usage:
     * <code>
     * $query->filterByQueryResponse('fooValue');   // WHERE query_response = 'fooValue'
     * $query->filterByQueryResponse('%fooValue%', Criteria::LIKE); // WHERE query_response LIKE '%fooValue%'
     * </code>
     *
     * @param     string $queryResponse The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCachedRequestsQuery The current query, for fluid interface
     */
    public function filterByQueryResponse($queryResponse = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($queryResponse)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CachedRequestsTableMap::COL_QUERY_RESPONSE, $queryResponse, $comparison);
    }

    /**
     * Filter the query by a related \GetVariables object
     *
     * @param \GetVariables|ObjectCollection $getVariables the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCachedRequestsQuery The current query, for fluid interface
     */
    public function filterByGetVariables($getVariables, $comparison = null)
    {
        if ($getVariables instanceof \GetVariables) {
            return $this
                ->addUsingAlias(CachedRequestsTableMap::COL_QUERY_ID, $getVariables->getQueryId(), $comparison);
        } elseif ($getVariables instanceof ObjectCollection) {
            return $this
                ->useGetVariablesQuery()
                ->filterByPrimaryKeys($getVariables->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByGetVariables() only accepts arguments of type \GetVariables or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the GetVariables relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCachedRequestsQuery The current query, for fluid interface
     */
    public function joinGetVariables($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('GetVariables');

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
            $this->addJoinObject($join, 'GetVariables');
        }

        return $this;
    }

    /**
     * Use the GetVariables relation GetVariables object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \GetVariablesQuery A secondary query class using the current class as primary query
     */
    public function useGetVariablesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinGetVariables($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'GetVariables', '\GetVariablesQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCachedRequests $cachedRequests Object to remove from the list of results
     *
     * @return $this|ChildCachedRequestsQuery The current query, for fluid interface
     */
    public function prune($cachedRequests = null)
    {
        if ($cachedRequests) {
            $this->addUsingAlias(CachedRequestsTableMap::COL_QUERY_ID, $cachedRequests->getQueryId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the cached_requests table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CachedRequestsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CachedRequestsTableMap::clearInstancePool();
            CachedRequestsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CachedRequestsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CachedRequestsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            CachedRequestsTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            CachedRequestsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // CachedRequestsQuery
