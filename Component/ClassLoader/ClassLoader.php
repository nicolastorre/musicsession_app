<?php

/**
 * Classloader Component
 *
 * Autoloading of the required class use in the app
 *
 *
 * @package    MusicSessionApp
 * @author     Nicolas Torre <nico.torre.06@gmail.com>
 */
class ClassLoader
{
    /**
     * Autoloading Class from Config directory
     *
     * @param string $className Name of the required class.
     * @return void .
     */
    public static function autoloadConfig($className) {
        $filename = "Config/" . $className . ".php";
        if (is_readable($filename)) {
            require_once $filename;
        }
    }

    /**
     * Autoloading Class from Kernel directory
     *
     * @param string $className Name of the required class.
     * @return void .
     */
    public static function autoloadKernel($className) {
        $filename = "Kernel/" . $className . ".php";
        if (is_readable($filename)) {
            require_once $filename;
        }
    }

    /**
     * Autoloading Class from Component directory
     *
     * @param string $className Name of the required class.
     * @return void .
     */
    public static function autoloadComponent($className) {
        $listdir = scandir("Component"); //only folder in component >>> no files
        foreach ($listdir as $dir) {
            $filename = "Component/" . $dir ."/". $className . ".php";
            if (is_readable($filename)) {
                require_once $filename;
            }
        }
    }

    /**
     * Autoloading Class from Controller directory
     *
     * @param string $className Name of the required class.
     * @return void .
     */
    public static function autoloadController($className) {
        $filename = "Controller/" . $className . ".php";
        if (is_readable($filename)) {
            require_once $filename;
        }
    }

    /**
     * Autoloading Class from Model directory
     *
     * @param string $className Name of the required class.
     * @return void .
     */
    public static function autoloadModel($className) {
        $filename = "Model/" . $className . ".php";
        if (is_readable($filename)) {
            require_once $filename;
        }
    }

    /**
     * Autoloading Class from Views directory
     *
     * @param string $className Name of the required class.
     * @return void .
     */
    public static function autoloadViews($className) {
        $filename = "Views/" . $className . ".php";
        if (is_readable($filename)) {
            require_once $filename;
        }
    }
}

/*
* Register every autoload method in the __autoload() pile
*
*/
spl_autoload_register("ClassLoader::autoloadConfig");
spl_autoload_register("ClassLoader::autoloadKernel");
spl_autoload_register("ClassLoader::autoloadComponent");
spl_autoload_register("ClassLoader::autoloadController");
spl_autoload_register("ClassLoader::autoloadModel");
spl_autoload_register("ClassLoader::autoloadViews");
