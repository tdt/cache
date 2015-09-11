<?php
/**
 * The caching class. Depending on the globals given in the config it will be able to set and get a variable
 *
 * Usage of an implementation:
 *
 *   $c = Cache::getInstance();
 *   $element = $c->get($id);
 *   if(is_null($element)){
 *      $element = get_the_right_data();
 *      $c->set($id,$element,$timeout);
 *   }
 *
 * @package tdt\cache
 * @copyright (C) 2011,2013 by iRail vzw/asbl, OKFN Belgium vzw/asbl
 * @license AGPLv3
 * @author Jan Vansteenlandt    <jan@iRail.be>
 * @author Michiel Vancoillie   <michiel@iRail.be>
 * @author Pieter Colpaert      <pieter@iRail.be>
 */

namespace tdt\cache;

use tdt\exceptions\TDTException;

abstract class Cache
{
    private static $instance;
    protected $config = array();


    /**
     * @param array $config
     */
    protected function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * The config expects:
     * system = name of the caching system i.e. MemCache, NoCache,...
     * host = the host on which the caching system runs
     * port = the port on which to connect to the host
     *
     * If NoCache is used, no host or port is necessary.
     *
     * @param array $config
     * @return
     * @throws TDTException
     */

    public static function getInstance(array $config)
    {
        if (!isset(self::$instance)) {
            if (isset($config["system"])) {
                $cacheclass = 'tdt\\cache\\' . $config["system"];
                if (class_exists($cacheclass)) {
                    self::$instance = new $cacheclass($config);
                } else {
                    throw new TDTException(551, array("tdt\\cache\\" . $config["system"]));
                }
            } else {
                throw new TDTException(500, array("The cache system was not set in the configuration"));
            }

        }
        return self::$instance;
    }

    public static function destroy()
    {
        self::$instance = null;
    }

    /**
     * @param $key
     * @param $value
     * @param int $TTL
     * @return mixed
     */
    abstract public function set($key, $value, $TTL = 60);

    /**
     * @param $key
     * @return mixed
     */
    abstract public function get($key);

    /**
     * @param $key
     * @return mixed
     */
    abstract public function delete($key);

}