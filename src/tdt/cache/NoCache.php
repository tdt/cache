<?php
/**
 * Dummy class - when no cache could be installed on the system (e.g. cheap hosts)
 *
 * @package The-Datatank/aspects/caching
 * @copyright (C) 2011 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jan Vansteenlandt    <jan@iRail.be>
 * @author Michiel Vancoillie   <michiel@iRail.be>
 * @author Pieter Colpaert      <pieter@iRail.be>
 */

namespace tdt\cache;

class NoCache extends Cache{
    protected function __construct(){

    }

    public function set($key,$value, $timeout=60){
        // do nothing
    }

    public function get($key){
        return null;
    }

    public function delete($key){
        // do nothing
    }

}