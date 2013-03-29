<?php
require_once 'PHPUnit/Framework/TestCase.php';
require 'MDB2.php';

class Test extends PHPUnit_Framework_TestCase
{

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
        $res = $db->query($q);
        $row = $res->fetchRow(MDB2_FETCHMODE_ASSOC);
        $this->assertEquals($row['id'], 3);
        $this->assertEquals($row['role_id'], 1);
        $this->assertEquals($row['region_id'], 2);
        $this->assertEquals($row['email'], 'fred@example.com');
        $numrows = $res->numRows();
    }

}
// vim:set et ts=4 sw=4:
?>
