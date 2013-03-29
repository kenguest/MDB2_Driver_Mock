<?php
/**
 * serialize.php
 * 08-Feb-2013
 *
 * PHP Version 5
 *
 * @category serialize
 * @package  serialize
 * @author   Ken Guest <ken@linux.ie>
 * @license  GPL (see http://www.gnu.org/licenses/gpl.txt)
 * @version  CVS: <cvs_id>
 * @link     serialize.php
 * @todo
*/


if (!isset($_SERVER['argv'][1])) {
	die("Provide filename\n");
}
$fn = $_SERVER['argv'][1];
include($fn);
$s = serialize($s);
echo "$s\n";
?>
