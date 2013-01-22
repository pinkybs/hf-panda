<?php

class Hapyfish2_Ipanda_Dal_Server
{
    protected static $_instance;
    
    protected function getDB()
    {
    	$key = 'db_0';
    	return Hapyfish2_Db_Factory::getBasicDB($key);
    }

    /**
     * Single Instance
     *
     * @return Hapyfish2_Ipanda_Dal_Server
     */
    public static function getDefaultInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function getServerList()
    {
    	$sql = "SELECT id,name,pub_ip,local_ip,type,add_time FROM ipanda_server";
    	
        $db = $this->getDB();
        $rdb = $db['r'];
    	
        return $rdb->fetchAssoc($sql);
    }
    
    public function getWebServerList()
    {
    	$sql = "SELECT id,name,pub_ip,local_ip,type,add_time FROM ipanda_server WHERE type='WEB'";
    	
        $db = $this->getDB();
        $rdb = $db['r'];
    	
        return $rdb->fetchAssoc($sql);
    }

}