<?php


class Hapyfish2_Ipanda_Stat_Dal_Main
{
    protected static $_instance;
    
    private $_tb_day_main = 'day_main';

    /**
     * Single Instance
     *
     * @return Hapyfish2_Ipanda_Stat_Dal_Main
     */
    public static function getDefaultInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function getUserInfoTableName($id)
    {
        if (APP_STATUS_DEV == 4) {
            return 'ipanda_user_info';
        }
        return 'ipanda_user_info_' . $id;
    }
    
    public function getDay($day)
    {
    	$tbname = $this->_tb_day_main;
    	$sql = "SELECT log_time,total_count,add_user,add_user_male,add_user_female,active,active_male,active_female,active_twoday,active_secondday FROM $tbname WHERE log_time=:day";
    	
        $db = Hapyfish2_Db_FactoryStat::getStatLogDB();
        $rdb = $db['r'];
    	
        return $rdb->fetchRow($sql, array('day' => $day));
    }
    
    public function getRange($begin, $end)
    {
    	$tbname = $this->_tb_day_main;
    	$sql = "SELECT log_time,total_count,add_user,add_user_male,add_user_female,active,active_male,active_female FROM $tbname WHERE log_time>=:begin AND log_time<=:end ORDER BY log_time DESC";
    	
        $db = Hapyfish2_Db_FactoryStat::getStatLogDB();
        $rdb = $db['r'];
    	
        return $rdb->fetchAll($sql, array('begin' => $begin, 'end' => $end));
    }
    
    public function update($day, $info)
    {
        $tbname = $this->_tb_day_main;
        
        $db = Hapyfish2_Db_FactoryStat::getStatLogDB();
        $wdb = $db['w'];
        
    	$where = $wdb->quoteinto('log_time = ?', $day);
    	
        $wdb->update($tbname, $info, $where);
    }
    
    //连续登录两天活跃用户
    public function getActiveTwoDay($dbId, $tbId, $begin, $end)
    {
        $tbname = $this->getUserInfoTableName($tbId);
        $sql = "SELECT COUNT(1) FROM $tbname WHERE active_login_count>1 AND last_login_time>=:begin AND last_login_time<:end";

        $db = Hapyfish2_Db_FactoryTool::getDB($dbId);
        $rdb = $db['r'];
        return $rdb->fetchOne($sql, array('begin' => $begin, 'end' => $end));
    }

    //所有用户等级分布
    public function getUserLevelList($dbId, $tbId)
    {
        $tbname = $this->getUserInfoTableName($tbId);
        $sql = "SELECT level,COUNT(1) AS count FROM $tbname GROUP BY level ";

        $db = Hapyfish2_Db_FactoryTool::getDB($dbId);
        $rdb = $db['r'];
        return $rdb->fetchAll($sql);
    }
    
    public function insertUserLevelList($info)
    {
        $tbname = 'day_user_level';

        $db = Hapyfish2_Db_FactoryStat::getStatLogDB();
        $wdb = $db['w'];
        
        return $wdb->insert($tbname, $info);
    }
    
    
    
    //连续登录两天活跃用户
    public function getLossUser($dbId, $tbId, $begin, $end)
    {
        $tbname = $this->getUserInfoTableName($tbId);
        $sql = "SELECT uid,level,love FROM $tbname WHERE all_login_count>=:begin AND all_login_count<:end ";

        $db = Hapyfish2_Db_FactoryTool::getDB($dbId);
        $rdb = $db['r'];
        return $rdb->fetchAll($sql, array('begin' => $begin, 'end' => $end));
    }
    
    //7天为登录，流失用户信息
    public function insertLossUser($info)
    {
        $tbname = 'day_lossuser';

        $db = Hapyfish2_Db_FactoryStat::getStatLogDB();
        $wdb = $db['w'];
        
        return $wdb->insert($tbname, $info);
    }
    
}