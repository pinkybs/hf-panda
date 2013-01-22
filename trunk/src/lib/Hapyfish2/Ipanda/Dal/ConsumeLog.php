<?php


class Hapyfish2_Ipanda_Dal_ConsumeLog
{
    protected static $_instance;

    /**
     * Single Instance
     *
     * @return Hapyfish2_Ipanda_Dal_ConsumeLog
     */
    public static function getDefaultInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function getLoveTableName($uid, $yearmonth)
    {
    	if (APP_STATUS_DEV == 4) {
    		return 'ipanda_user_lovelog';
    	}
    	
    	$id = floor($uid/DATABASE_NODE_NUM) % 10;
    	return 'ipanda_user_lovelog_' . $yearmonth . '_' . $id;
    }
    
    public function getGoldTableName($uid, $yearmonth)
    {
    	if (APP_STATUS_DEV == 4) {
    		return 'ipanda_user_goldlog';
    	}
    	
    	$id = floor($uid/DATABASE_NODE_NUM) % 10;
    	return 'ipanda_user_goldlog_' . $yearmonth . '_' . $id;
    }
    
    public function getPayOrderFlowTableName($uid, $yearmonth)
    {
        if (APP_STATUS_DEV == 4) {
    		return 'ipanda_user_payorder_flow';
    	}
    	
    	$id = floor($uid/DATABASE_NODE_NUM) % 10;
    	return 'ipanda_user_payorder_flow_' . $yearmonth . '_' . $id;
    }
    
    public function getLove($uid, $yearmonth, $limit = 50)
    {
    	$tbname = $this->getCoinTableName($uid, $yearmonth);
    	$sql = "SELECT cost,summary,create_time FROM $tbname WHERE uid=:uid ORDER BY create_time DESC";
    	if ($limit > 0) {
    		$sql .= ' LIMIT ' . $limit;
    	}
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
    	
        return $rdb->fetchAll($sql, array('uid' => $uid));
    }
    
    public function insertLove($uid, $info)
    {
        $yearmonth = date('Ym', $info['create_time']);
    	$tbname = $this->getLoveTableName($uid, $yearmonth);

        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
    	return $wdb->insert($tbname, $info); 	
    }
    
    public function clearLove($uid, $yearmonth)
    {
        $tbname = $this->getLoveTableName($uid, $yearmonth);
        
        $sql = "DELETE FROM $tbname WHERE uid=:uid";
        
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
        $wdb->query($sql, array('uid' => $uid));
    }
    
    public function getGold($uid, $yearmonth, $limit = 50)
    {
    	$tbname = $this->getGoldTableName($uid, $yearmonth);
    	$sql = "SELECT cost,summary,create_time FROM $tbname WHERE uid=:uid ORDER BY create_time DESC";
    	if ($limit > 0) {
    		$sql .= ' LIMIT ' . $limit;
    	}
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
    	
        return $rdb->fetchAll($sql, array('uid' => $uid));
    }
    
    public function insertGold($uid, $info)
    {
        $yearmonth = date('Ym', $info['create_time']);
    	$tbname = $this->getGoldTableName($uid, $yearmonth);

        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
    	return $wdb->insert($tbname, $info); 	
    }
    
    public function clearGold($uid, $yearmonth)
    {
        $tbname = $this->getGoldTableName($uid, $yearmonth);
        
        $sql = "DELETE FROM $tbname WHERE uid=:uid";
        
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
        $wdb->query($sql, array('uid' => $uid));
    }
    
    public function getPayOrderFlow($uid, $yearmonth, $limit = 100)
    {
    	$tbname = $this->getPayOrderFlowTableName($uid, $yearmonth);
    	$sql = "SELECT * FROM $tbname WHERE uid=:uid ORDER BY time DESC";
    	if ($limit > 0) {
    		$sql .= ' LIMIT ' . $limit;
    	}
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
    	
        return $rdb->fetchAll($sql, array('uid' => $uid));
    }
    
    public function insertPayOrderFlow($uid, $info)
    {
        $yearmonth = date('Ym', $info['time']);
    	$tbname = $this->getPayOrderFlowTableName($uid, $yearmonth);

        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
    	return $wdb->insert($tbname, $info); 	
    }
    
    public function clearPayOrderFlow($uid, $yearmonth)
    {
        $tbname = $this->getPayOrderFlowTableName($uid, $yearmonth);
        
        $sql = "DELETE FROM $tbname WHERE uid=:uid";
        
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
        $wdb->query($sql, array('uid' => $uid));
    }
    
}