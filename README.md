cache
=====

This repository holds a wrapper around a caching system. You can use NoCache if no caching system is installed, or MemCache. This allows the user to provide caching in his code, and switch to other 
caching systems later on if necessary.

# Usage

```php

$c = Cache::getInstance( array("system" => "Memcache", "host" => "localhost", "port" => 11211 ) );
$c->set("key", $objectToCache, $TTL); // TTL is optional

$cachedObject = $c->get("key");
// delete the cachedObject
$c->delete("key");

```