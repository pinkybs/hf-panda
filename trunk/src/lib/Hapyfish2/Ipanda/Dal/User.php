<?php


class Hapyfish2_Ipanda_Dal_User
{
    protected static $_instance;

    /**
     * Single Instance
     *
     * @return Hapyfish2_Ipanda_Dal_User
     */
    public static function getDefaultInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function getTableName($uid)
    {
    	if (APP_STATUS_DEV == 4) {
    		return 'ipanda_user_info';
    	}
    	
    	$id = floor($uid/DATABASE_NODE_NUM) % 10;
    	return 'ipanda_user_info_' . $id;
    }
    
    public function get($uid)
    {
        $tbname = $this->getTableName($uid);
    	$sql = "SELECT love,gold,exp,level,ipanda_level FROM $tbname WHERE uid=:uid";
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
    	
        return $rdb->fetchRow($sql, array('uid' => $uid), Zend_Db::FETCH_NUM);
    }
    
    public function getExp($uid)
    {
        $tbname = $this->getTableName($uid);
    	$sql = "SELECT exp FROM $tbname WHERE uid=:uid";
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
    	
        return $rdb->fetchOne($sql, array('uid' => $uid));
    }
    
 	public function getEnergy($uid)
    {
        $tbname = $this->getTableName($uid);
    	$sql = "SELECT energy,energy_top,energy_recover_time FROM $tbname WHERE uid=:uid";
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
    	
        return $rdb->fetchRow($sql, array('uid' => $uid), Zend_Db::FETCH_NUM);
    }
    
    public function getLove($uid)
    {
        $tbname = $this->getTableName($uid);
    	$sql = "SELECT love FROM $tbname WHERE uid=:uid";
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
    	
        return $rdb->fetchOne($sql, array('uid' => $uid));
    }
    
    public function getGold($uid)
    {
        $tbname = $this->getTableName($uid);
    	$sql = "SELECT gold FROM $tbname WHERE uid=:uid";
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
    	
        return $rdb->fetchOne($sql, array('uid' => $uid));
    }
    
    public function incGold($uid, $gold)
    {
        $tbname = $this->getTableName($uid);
        $sql = "UPDATE $tbname SET gold=gold+:gold WHERE uid=:uid";
        
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
        return $wdb->query($sql, array('uid' => $uid, 'gold' => $gold));
    }
    
    public function decGold($uid, $gold)
    {
        $tbname = $this->getTableName($uid);
        $sql = "UPDATE $tbname SET gold=gold-:gold WHERE uid=:uid AND gold>=:gold";
        
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        $stmt = $wdb->query($sql, array('uid' => $uid, 'gold' => $gold));
        $result = $stmt->rowCount();
        return $result;
    }
    
 	public function incLove($uid, $love)
    {
        $tbname = $this->getTableName($uid);
        $sql = "UPDATE $tbname SET love=love+:love WHERE uid=:uid";
        
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
        return $wdb->query($sql, array('uid' => $uid, 'love' => $love));
    }
    
    public function decLove($uid, $love)
    {
        $tbname = $this->getTableName($uid);
        $sql = "UPDATE $tbname SET love=love-:love WHERE uid=:uid AND love>=:love";
        
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        $stmt = $wdb->query($sql, array('uid' => $uid, 'love' => $love));
        $result = $stmt->rowCount();
        return $result;
    }
    
    public function getLevel($uid)
    {
        $tbname = $this->getTableName($uid);
    	$sql = "SELECT level FROM $tbname WHERE uid=:uid";
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
    	
        return $rdb->fetchOne($sql, array('uid' => $uid));
    }
    
    public function getAdminNum($uid)
    {
        $tbname = $this->getTableName($uid);
    	$sql = "SELECT admin_num FROM $tbname WHERE uid=:uid";
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
    	
        return $rdb->fetchOne($sql, array('uid' => $uid));
    }
    
	public function getUserVo($uid)
    {
        $tbname = $this->getTableName($uid);
    	$sql = "SELECT uid,exp,love,gold,level,energy,energy_top,energy_recover_time,admin_num,title,title_list FROM $tbname WHERE uid=:uid";
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
    	
        return $rdb->fetchRow($sql, array('uid' => $uid));
    }
    
    public function getTitle($uid)
    {
        $tbname = $this->getTableName($uid);
    	$sql = "SELECT title,title_list FROM $tbname WHERE uid=:uid";
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
    	
        return $rdb->fetchRow($sql, array('uid' => $uid), Zend_Db::FETCH_NUM);
    }
    
    public function getLoginInfo($uid)
    {
        $tbname = $this->getTableName($uid);
    	$sql = "SELECT last_login_time,today_login_count,active_login_count,max_active_login_count,all_login_count FROM $tbname WHERE uid=:uid";
        
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
    	
        return $rdb->fetchRow($sql, array('uid' => $uid), Zend_Db::FETCH_NUM);
    }
    
    public function update($uid, $info)
    {
        $tbname = $this->getTableName($uid);
        
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
    	$where = $wdb->quoteinto('uid = ?', $uid);
    	
        $wdb->update($tbname, $info, $where);   	
    }
    
	public function updateAdminNum($uid)
    {
        $tbname = $this->getTableName($uid);
        
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
    	$sql = "update $tbname set admin_num=admin_num+1 where uid=$uid limit 1";
    	return $wdb->query($sql);
    }
    
    public function init($uid)
    {
        $tbname = $this->getTableName($uid);
        $t = time();
        $love = INIT_USER_LOVE;
        $gold = INIT_USER_GOLD;
        $energy = INIT_USER_ENERGY;
        $sql = "INSERT INTO $tbname(uid,love,gold,energy,energy_top,energy_recover_time,admin_num,create_time) VALUES(:uid,$love,$gold,$energy,$energy,$t,1,$t)";
        
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
        $wdb->query($sql, array('uid' => $uid));
    }
    
    public function clear($uid)
    {
        $tbname = $this->getTableName($uid);
        
        $sql = "DELETE FROM $tbname WHERE uid=:uid";
        
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
        $wdb->query($sql, array('uid' => $uid));
    }
    
}