<?php
error_reporting(0);
require_once 'PEAR/PackageFileManager2.php';
// recommended - makes PEAR_Errors act like exceptions (kind of)
PEAR::setErrorHandling(PEAR_ERROR_DIE);

$packagexml = new PEAR_PackageFileManager2();
$packagexml->setOptions(
    array(
    'filelistgenerator' => 'file',
    'packagedirectory' => dirname(__FILE__),
    'baseinstalldir' => '/',
    'dir_roles' => array(
        'examples' => 'doc',
        'docs'     => 'doc',
        'tests'    => 'test'
        ),
    'ignore' => array(
        'test.php',
        'coverage.db',
        'package.php',
        'package.xml',
        'package.xml.old'
    ),
    'simpleoutput' => true)
);
$packagexml->setPackageType('php');
$packagexml->addRelease();
$packagexml->setPackage('MDB2_Driver_Mock');
$packagexml->setChannel('pear.php.net');
$packagexml->setReleaseVersion('0.1.0');
$packagexml->setAPIVersion('0.1.0');
$packagexml->setReleaseStability('alpha');
$packagexml->setAPIStability('alpha');
$packagexml->setSummary('Mocking driver for MDB2');
$packagexml->setDescription('Used for mocking connections and queries/results.');
$packagexml->setNotes(
    'Experimental'
);
$packagexml->setPhpDep('5.2.0');
$packagexml->setPearinstallerDep('1.7.0');
$packagexml->addPackageDepWithChannel(
    'required',
    'MDB2',
    'pear.php.net',
    '2.5.0'
);
$packagexml->addMaintainer('lead', 'kguest', 'Ken Guest', 'kguest@php.net');
$packagexml->setLicense(
    'BSD License',
    'http://www.opensource.org/licenses/bsd-license.html'
);
$packagexml->addGlobalReplacement('package-info', '@PEAR-VER@', 'version');
$packagexml->generateContents();

if (isset($_GET['make'])
    || (isset($_SERVER['argv'])
    && @$_SERVER['argv'][1] == 'make')
) {
    $packagexml->writePackageFile();
} else {
    $packagexml->debugPackageFile();
}
// vim:set et ts=4 sw=4:
?>
