<?php


class Hapyfish2_Ipanda_Dal_Building
{
    protected static $_instance;

    /**
     * Single Instance
     *
     * @return Hapyfish2_Ipanda_Dal_Building
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
    		return 'ipanda_user_building';
    	}
    	
    	$id = floor($uid/DATABASE_NODE_NUM) % 50;
    	return 'ipanda_user_building_' . $id;
    }
    
    
    public function getAll($uid)
    {
        $tbname = $this->getTableName($uid);
    	$sql = "SELECT * FROM $tbname WHERE uid=:uid";
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
    	
        return $rdb->fetchAll($sql, array('uid' => $uid));
    }
    
	public function getOnForest($uid)
    {
        $tbname = $this->getTableName($uid);
    	$sql = "SELECT * FROM $tbname WHERE uid=:uid and status=1";
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
    	
        return $rdb->fetchAssoc($sql, array('uid' => $uid));
    }
    
	public function getAllIds($uid)
    {
        $tbname = $this->getTableName($uid);
    	$sql = "SELECT id FROM $tbname WHERE uid=:uid";
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
        
        return $rdb->fetchCol($sql, array('uid' => $uid));
    }
    
    public function getOnForestIds($uid)
    {
    	$tbname = $this->getTableName($uid);
    	$sql = "SELECT id FROM $tbname WHERE uid=:uid ";
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
        
        return $rdb->fetchCol($sql, array('uid' => $uid));
    }
    
	public function getOne($uid, $id)
    {
        $tbname = $this->getTableName($uid);
    	$sql = "SELECT * FROM $tbname WHERE uid=:uid AND id=:id";
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
    	
        return $rdb->fetchRow($sql, array('uid' => $uid, 'id' => $id));
    }
    
    public function insert($uid, $info)
    {
        $tbname = $this->getTableName($uid);

        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
        $ret = $wdb->insert($tbname, $info);
      
    	return $ret;
    }
    
    public function update($uid, $id, $info)
    {
        $tbname = $this->getTableName($uid);
        
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
    	$uid = $wdb->quote($uid);
        $id = $wdb->quote($id);
    	$where = "uid=$uid AND id=$id";
    	
        return $wdb->update($tbname, $info, $where); 
    }
    
	public function updateAll($uid, $info)
    {
        $tbname = $this->getTableName($uid);
        
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
    	$uid = $wdb->quote($uid);
    	$where = "uid=$uid";
    	
        return $wdb->update($tbname, $info, $where); 
    }
    
    public function delete($uid, $id)
    {
        $tbname = $this->getTableName($uid);
        
        $sql = "DELETE FROM $tbname WHERE uid=:uid AND id=:id";
        
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
        return $wdb->query($sql, array('uid' => $uid, 'id' => $id));
    }
    
    public function init($uid)
    {
        $tbname = $this->getTableName($uid);
		$t = time();
		$t2 = $t + 30;
        $sql = "INSERT INTO $tbname(uid,id,cid,x,y,z,mirro,status,item_id,item_type,deposit,effect_deposit,durable,top_durable,op_start_time,op_end_time,op_status,checkout_time_type,checkout_time,checkout_love,attr,effect_source)
            VALUES
            (:uid,1,411,5,0,1,0,1,411,11,100,100,1,50,$t,$t2,1,0,300,5,'[50,54,0,1,0,0,4,0]','[12,11,10,9,8,6,22,2]'),
            (:uid,2,111,1,0,5,0,1,111,11,0,0,50,50,0,0,2,0,300,5,'[50,55,0,0,0,0,5,0]','[18,17,16,3,7,4,5]')";

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
        
        return $wdb->query($sql, array('uid' => $uid));
    }
    
}