<?php

/**
 * Quick test of the caching systems
 *
 * @copyright (C) 2011 by iRail vzw/asbl
 * @license AGPLv3
 * @author Michiel Vancoillie
 */

require "vendor/autoload.php";

use tdt\cache\MemCache;
use tdt\cache\NoCache;
use tdt\cache\Cache;

class CacheTest extends \PHPUnit_Framework_TestCase{

    public function testCache(){
        // Test nocache
        $nocache = $this->_createCacheInstance("NoCache");
        $nocache->set("key","value", 0);
        $this->assertNull($nocache->get("key"), "NoCache shouldn't be caching!");
        Cache::destroy();

        // Test memcached
        $memcache = $this->_createCacheInstance("MemCache");
        $memcache->set("key","value", 0);
        $this->assertEquals("value", $memcache->get("key"), "MemCache didn't cache!");
        $memcache->set("key2","value2", 2);
        $this->assertEquals("value2", $memcache->get("key2"), "MemCache didn't cache expiring value!");
        sleep(1);
        $this->assertEquals("value2", $memcache->get("key2"), "MemCache didn't cache expiring value (1s)!");
        sleep(1);
        $this->assertNull($memcache->get("key2"), "Memcache didn't expire 'key2'.");
        $memcache->delete("key");
        $this->assertNull($memcache->get("key"), "Memcache didn't delete 'key'.");
        Cache::destroy();
    }

    private function _createCacheInstance($type){
        $config = array("system" => $type);
        $cache = Cache::getInstance($config);
        $this->assertInstanceOf('tdt\cache\Cache', $cache, "Could not construct $type instance");

        return $cache;
    }

}