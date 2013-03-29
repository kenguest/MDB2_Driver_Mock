<?php
/**
 * mock.php
 * 05-Feb-2013
 *
 * PHP Version 5
 *
 * @category Database
 * @package  MDB2
 * @author   Ken Guest <ken@linux.ie>
 * @license  BSD http://www.opensource.org/licenses/bsd-license.php
 * @link     mock.php
 */

/**
 * MDB2 Mock driver
 *
 * @category Database
 * @package  MDB2
 * @author   Ken Guest <ken@linux.ie>
 * @license  BSD http://www.opensource.org/licenses/bsd-license.php
 * @link     mock.php
 */
class MDB2_Driver_mock extends MDB2_Driver_Common
{
    protected $queries = array();

    protected $affected = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->phptype  = 'mock';
        $this->dbsyntax = 'mysql';
    }

    /**
     * setAffected
     *
     * @param boolean $is_manip If the query is a manipulation query
     * @param array   $result   The mock result being parsed
     *
     * @return void
     */
    private function setAffected($is_manip, $result)
    {
        if ($is_manip) {
            if (isset($request['affected'])) {
                $this->affected = $request['affected'];
            } else {
                // Unless specified, assume 1 row was affected.
                $this->affected = 1;
            }
        } else {
            $this->affected = null;
        }
    }

    /**
     * Connect to the database
     *
     * @return true on success, MDB2 Error Object on failure
     */
    public function connect()
    {
        if (file_exists($this->database_name)) {
            return MDB2_OK;
        } else {
            return $this->raiseError(
                MDB2_ERROR_NOT_FOUND, null, null,
                'File ' . $this->database_name . ' not found.', __FUNCTION__
            );
        }
    }

    /**
     * Return array of executed queries in the order in which they were executed.
     *
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
    }

    /**
     * Execute a query
     *
     * @param string   $query         The query
     * @param boolean  $is_manip      If the query is a manipulation query
     * @param resource $connection    Connection
     * @param string   $database_name Name of database
     *
     * @return result or error object
     * @access protected
     */
    public function _doQuery(
        $query,
        $is_manip = false,
        $connection = null,
        $database_name = null
    ) {
        // remove unwanted spaces.
        $query = trim(preg_replace('/\s\s+/', ' ', $query));

        $this->last_query = $query;
        $this->queries[] = $query;
        $result = $this->debug(
            $query,
            'query',
            array('is_manip' => $is_manip, 'when' => 'pre')
        );
        $contents = file($database_name);
        if (sizeof($contents) == 1) {
            $data = unserialize($contents[0]);
            $request = $data[0];
            if (trim($request['query']) == $query) {
                $this->setAffected($is_manip, $request);
                return $request['response'];
            } else {
                return $this->raiseError(
                    MDB2_ERROR_NOT_FOUND, null, null,
                    "matching query ($query) not found in $database_name",
                    __FUNCTION__
                );
            }
        } else {
            foreach ($contents as $line) {
                $data = unserialize($line);
                foreach ($data as $request) {
                    if (trim($request['query']) == $query) {
                        $this->setAffected($is_manip, $request);
                        return $request['response'];
                    }
                }
            }
            return $this->raiseError(
                MDB2_ERROR_NOT_FOUND, null, null,
                "matching query ($query) not found in $database_name",
                __FUNCTION__
            );
        }
    }

    /**
     * Returns the autoincrement ID if supported or $id or fetches the current
     * ID in a sequence called: $table.(empty($field) ? '' : '_'.$field)
     *
     * @param string $table name of the table into which a new row was inserted
     * @param string $field name of the field into which a new row was inserted
     * @return mixed MDB2 Error Object or id
     * @access public
     */
    public function lastInsertID($table = null, $field = null) {
        static $id = 1;
        return $id++;
    }

    /**
     * Returns the number of rows affected
     *
     * @param resource $result
     * @param resource $connection
     * @return mixed MDB2 Error Object or the number of rows affected
     * @access private
     */
    function _affectedRows($connection, $result = null)
    {
        return $this->affected;
    }

}

/**
 * MDB2 Mock result driver
 *
 * @category Database
 * @package  MDB2
 * @author   Ken Guest <ken@linux.ie>
 * @license  BSD http://www.opensource.org/licenses/bsd-license.php
 * @link     mock.php
 */
class MDB2_Result_mock extends MDB2_Result_Common
{
    /**
     * Fetch a row and insert the data into an existing array.
     *
     * @param int $fetchmode How the array data should be indexed
     * @param int $rownum    Number of the row where the data can be found
     *
     * @return int data array on success, a MDB2 error on failure
     * @access public
     */
    public function fetchRow($fetchmode = MDB2_FETCHMODE_DEFAULT, $rownum = null)
    {
        ++$this->rownum;
        if (isset($this->result[$this->rownum])) {
            return $this->result[$this->rownum];
        }
    }

    /**
     * Retrieve the names of columns returned by the DBMS in a query result.
     *
     * @return  mixed   Array variable that holds the names of columns as keys
     *                  or an MDB2 error on failure.
     *                  Some DBMS may not return any columns when the result set
     *                  does not contain any rows.
     * @access private
     */
    public function _getColumnNames()
    {
        if (null === $this->result) {
            return array();
        }
        return array_keys($this->result);
    }

    /**
     * Returns the number of rows in a result object
     *
     * @return mixed MDB2 Error Object or the number of rows
     * @access public
     */
    public function numRows()
    {
        return sizeof($this->result);
    }

}

/**
 * MDB2 Mock buffered result driver
 *
 * @category Database
 * @package  MDB2
 * @author   Ken Guest <ken@linux.ie>
 * @license  BSD http://www.opensource.org/licenses/bsd-license.php
 * @link     mock.php
 */
class MDB2_BufferedResult_mock extends MDB2_Result_mock
{
}

class MDB2_Statement_mock extends MDB2_Statement_Common
{

}
// vim:set et ts=4 sw=4:
?>
