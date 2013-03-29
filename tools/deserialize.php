<?php
/**
 * d.php
 * 07-Feb-2013
 *
 * PHP Version 5
 *
 * @category d
 * @package  d
 * @author   Ken Guest <ken@linux.ie>
 * @license  GPL (see http://www.gnu.org/licenses/gpl.txt)
 * @version  CVS: <cvs_id>
 * @link     d.php
 * @todo
*/

if (!isset($_SERVER['argv'][1])) {
	die("Provide filename\n");
}
$fn = $_SERVER['argv'][1];
$c = file($fn);
foreach ($c as $l) {

$d = unserialize($l);
echo var_export($d), ";";
}
?>
