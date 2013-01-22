<?php

class Hapyfish2_Ipanda_Stat_Dal_Levelup
{
    protected static $_instance;
    
    private $_tb_day_levelup = 'day_levelup';

    /**
     * Single Instance
     *
     * @return Hapyfish2_Ipanda_Stat_Dal_Levelup
     */
    public static function getDefaultInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function insert($info)
    {
        $tbname = $this->_tb_day_levelup;

        $db = Hapyfish2_Db_FactoryStat::getStatLogDB();
        $wdb = $db['w'];
        
        return $wdb->insert($tbname, $info);
    }
    
    public function getDay($day)
    {
    	$tbname = $this->_tb_day_levelup;
    	$sql = "SELECT * FROM $tbname WHERE log_time=:day";
    	
        $db = Hapyfish2_Db_FactoryStat::getStatLogDB();
        $rdb = $db['r'];
        
        return $rdb->fetchRow($sql, array('day' => $day));
    }
    
}