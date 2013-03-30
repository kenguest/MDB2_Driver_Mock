<?php
/**
 * Test.php
 * 24-Jan-2013
 *
 * PHP Version 5
 *
 * @category Database
 * @package  MDB2_Driver_Mock
 * @author   Ken Guest <ken@linux.ie>
 * @license  BSD License, http://www.opensource.org/licenses/bsd-license.html
 * @link     Test.php
 */
require_once 'PHPUnit/Framework/TestCase.php';
require 'MDB2.php';

/**
 * Exhibit, and test, some features of the mock driver.
 *
 * @category  Database
 * @package   MDB2_Driver_Mock
 * @author    Ken Guest <ken@linux.ie>
 * @copyright 2013 Ken Guest.
 * @license   BSD License, http://www.opensource.org/licenses/bsd-license.html
 * @link      Test.php
 */
class Test extends PHPUnit_Framework_TestCase
{

    /**
     * testGetById
     *
     * @return void
     */
    function testGetById()
    {
        $mock = MDB2::factory(
            array(
                'phptype' => 'mock',
                'database' => './responses/testGetById.ser'
            )
        );
        $q = "SELECT *
            FROM person WHERE id = 3";
        $db = $mock;
        $res = $mock->query($q);
        // assert we got just the one row.
        $this->assertEquals($res->numRows(), 1);
        $row = $res->fetchRow(MDB2_FETCHMODE_ASSOC);
        $this->assertEquals($row['id'], 3);
        $this->assertEquals($row['role_id'], 1);
        $this->assertEquals($row['region_id'], 2);
        $this->assertEquals($row['email'], 'fred@example.com');
        // getQueries method returns an array listing every executed query
        $this->assertEquals(
            $mock->getQueries(),
            array("SELECT * FROM person WHERE id = 3")
        );
    }

}
// vim:set et ts=4 sw=4:
?>
