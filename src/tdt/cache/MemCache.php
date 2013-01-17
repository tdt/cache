<?php

/**
 * MemCache implementation of Cache
 * 
 * @package The-Datatank/aspects/caching
 * @copyright (C) 2011,2013 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jan Vansteenlandt <jan@iRail.be>
 * @author Pieter Colpaert   <pieter@iRail.be>
 */

namespace tdt\cache;

use tdt\exceptions\TDTException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class MemCache extends Cache {

    private $memcache;

    protected function __construct() {
        $this->memcache = new Memcache();
        /*
         * This is something tricky in PHP. If you use pconnect (p=persistent) the connection will remain open all the time.
         * This is not a bad thing since we're using the cache all the time (you don't turn off the light of the kitchen if you're running in and out, switching it too much would even consume more)
         * In memcache, the old but stable implementation in PHP of memcached the persistent connection works like charm
         * In memcached however there is a severe bug which leads to a memory leak. If you'd take over code from this class to implement memcached, DON'T use the persistent connect!
         */
        
        if(!isset($this->config["host"])){
            throw new TDTException(500, array("No host has been given to cache to."));
        }
        
        if(!isset($this->config["port"])){
            $this->config["port"] = 11211; // the default port for memcached is 11211
        }
        
        if (!$this->memcache->pconnect($this->config["host"], $this->config["port"])) {
            if (isset($this->config["log_dir"])) {
                $log_dir = rtrim($this->config["log_dir"], "/");
                $log = new Logger('cache');
                $log->pushHandler(new StreamHandler($log_dir . "/log_". date('Y-m-d') . ".txt", Logger::CRITICAL));
                $log->addCritical("Could not connect to memcached.", $this->config);                
            }else{
                /*
                 * if we have no log directory, it's no use to throw a TDTException
                 */
                throw new \Exception("No connection could be made to the memcache. Please check your given configuration.");
            }
        }
    }

    public function set($key, $value, $timeout = 60) {
        if ($timeout > 0) {
            $this->memcache->set($key, $value, FALSE, $timeout); //the true flag will compress the value using zlib
        }
    }

    public function get($key) {
        if ($this->memcache->get($key)) {
            return $this->memcache->get($key);
        }
        return null;
    }

    public function delete($key) {
        $this->memcache->delete($key, 0);
    }

}
