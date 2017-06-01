<?php
/**
 * Class bootstrap installer class file
 *
 * (c) Moisés Barquín <moises.barquin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP version 7.0
 *
 * @package    symfonyMerc
 * @subpackage Install
 * @author     Moises Barquin <moises.barquin@gmail.com>
 * @copyright  (c) 2016, Moisés Barquín <moises.barquin@gmail.com>
 * @version    GIT: $Id$
 */
namespace AppBundle\Install;
use Composer\Installer\PackageEvent;
use Composer\Package\Package;
/**
 * startbootstrap-sb-admin-2 installer
 *
 * After a composer  install/update It copies sources
 * from startbootstrap-sb-admin-2 package into public
 * src directory
 *
 * startbootstrap-sb-admin-2 Is not composer friendly
 */
class PostInstallScript
{
    const packageName    = 'blackrockdigital/startbootstrap-sb-admin-2';
    const jobTypeUpdate  = 'update';
    const jobTypeInstall = 'install';
    private static $bootstrapUpdated = false;
    /**
     * Invoked by composer after a succesful package update operation
     *
     * @param PackageEvent $event
     *
     * @return void
     */
    public static function postPackageUpdate(PackageEvent $event)
    {
         static::checkPackage($event);
    }
    /**
     * Invoked by composer after a succesful package install operation
     *
     * @param PackageEvent $event
     *
     * @return void
     */
    public static function postPackageInstall(PackageEvent $event)
    {
        static::checkPackage($event);
    }
    /**
     * Copies package dist files and related vendor packages into public dir
     *
     * @param PackageEvent $event
     * @param Package      $oPackage
     * @param string       $packageName
     *
     * @return void
     */
    static private function copyPublicFiles(PackageEvent $event, $oPackage, $packageName) {
        $installMng = $event->getComposer()->getInstallationManager();
        $originDir  = $installMng->getInstallPath($oPackage);
        $auxArray   = explode("/", $packageName);
        $vendorName = '/'.$auxArray[1];
        $targetName = getcwd()."/web/vendor";
        echo "\nCopying files into public folder\n\tvendor/".$vendorName;
        static::recurse_copy($originDir.'/dist', $targetName.$vendorName);
        static::recurse_copy($originDir.'/vendor', $targetName, false);
    }
    /**
     * Performs recursive directory copies
     *
     * @param string $src           Path to source files as string
     * @param string $dst           Path to target files as string
     * @param bool   $createDstDir  If necessary create all path dirs
     */
    public static function recurse_copy($src, $dst, $createDstDir = true) {
        $dir = opendir($src);
        if($createDstDir === true) {
            mkdir($dst, 0777, true);
        }
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                echo "\n\t - From: ".$src . '/' . $file;
                echo "\n\t   To: ".$dst . '/' . $file;
                if ( is_dir($src . '/' . $file) ) {
                    self::recurse_copy($src . '/' . $file,$dst . '/' . $file);
                } else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
    /**
     * Gets and returns the package object depending on jobType
     *
     * @param PackageEvent $event
     *
     * @return Package
     */
    private static function getPackage(PackageEvent $event) {
        $command = $event->getOperation()->getJobType();
        if ($command === static::jobTypeInstall) {
            return $event->getOperation()->getPackage();
        } elseif ($command === static::jobTypeUpdate) {
            return $event->getOperation()->getTargetPackage();
        }
        return null;
    }
    /**
     * Gets package object and package name to begin operations
     *
     * @param PackageEvent $event
     *
     * @return void
     */
    private static function checkPackage(PackageEvent $event)
    {
        $oPackage    = static::getPackage($event);
        $packageName = $oPackage->getName();
        if(stripos($packageName, self::packageName) === 0) {
            if($oPackage !== null) {
                static::copyPublicFiles($event, $oPackage, $packageName);
            }
        }
    }
}