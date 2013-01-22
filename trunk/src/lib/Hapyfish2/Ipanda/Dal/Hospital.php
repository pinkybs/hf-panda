<?php

/**
 * 道具数据操作类
 *
 */
class Hapyfish2_Ipanda_Dal_Hospital
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
     * @return Hapyfish2_Ipanda_Dal_Card
     */
    public static function getDefaultInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function getUserTab($uid)
    {
    	if (APP_STATUS_DEV == 4) {
    		return 'ipanda_user_hospital';
    	}
    	
    	$id = floor($uid/DATABASE_NODE_NUM) % 10;
    	return 'ipanda_user_hospital_' . $id;
    }
    
    public function getDisTb($uid)
    {
    	if (APP_STATUS_DEV == 4) {
    		return 'ipanda_user_disease';
    	}
    	
    	$id = floor($uid/DATABASE_NODE_NUM) % 10;
    	return 'ipanda_user_disease_' . $id;
    }

    public function getHospital()
    {
   	 	$sql = "select * from ipanda_hospital";
   	 	$db = $this->getDB();
        $rdb = $db['r'];
        return $rdb->fetchAll($sql);
    }
    
 	public function getDisease()
    {
   	 	$sql = "select * from ipanda_disease";
   	 	$db = $this->getDB();
        $rdb = $db['r'];
        return $rdb->fetchAll($sql);
    }
    
	public function getDrug()
	{
  		$sql = "select * from ipanda_drug";
   	 	$db = $this->getDB();
        $rdb = $db['r'];
        return $rdb->fetchAll($sql);
  	}
  
	public function updateUuserdis($data)
	{	$tbName = $this->getDisTb($data['uid']);
		$sql = "INSERT INTO $tbName (id, uid, cid, die_time, status, dis_id, name) VALUES(:id, :uid, :cid, :die_time, :status, :dis_id, :name) ON DUPLICATE KEY UPDATE cid=:cid, status=:status";
		$db = Hapyfish2_Db_Factory::getDB($data['uid']);
		$wdb = $db['w'];
		return $wdb->query($sql, array('id'=>$data['id'], 'uid' => $data['uid'], 'cid' => $data['cid'], 'die_time' => $data['die_time'], 'status'=>$data['status'], 'dis_id'=>$data['dis_id'], 'name'=>$data['name']));
	}
  
    public function getUserHospital($uid)
    {
  	    $tbname = $this->getUserTab($uid);
  	    $sql = "select * from $tbname where uid=:uid";
  	    $db = Hapyfish2_Db_Factory::getDB($uid);
  	    $rdb = $db['r'];
  	    return $rdb->fetchAll($sql, array('uid'=>$uid));
    }
    
    public function clearUserHospital($uid)
    {
    	$tbname = $this->getUserTab($uid);
  	    $sql = "delete from $tbname where uid=:uid";
  	    $db = Hapyfish2_Db_Factory::getDB($uid);
  	   	$wdb = $db['w'];
  	    return $wdb->query($sql, array('uid'=>$uid));
    }
  
    public function updateUserHospital($data)
    {
  	    $tbname = $this->getUserTab($data['uid']);
  	    $sql = "INSERT INTO $tbname (uid, id, drug_id, stage, end_time) VALUES(:uid, :id, :drug_id, :stage, :end_time) ON DUPLICATE KEY UPDATE drug_id=:drug_id, stage=:stage, end_time=:end_time";
  	    $db = Hapyfish2_Db_Factory::getDB($data['uid']);
   	    $wdb = $db['w'];
        return $wdb->query($sql, array('uid'=>$data['uid'], 'id' => $data['id'], 'drug_id' => $data['drug_id'], 'stage' => $data['stage'], 'end_time'=>$data['end_time']));
    }
  
    public function getUserDis($uid, $id)
    {
  		$tb = $this->getDisTb($uid);
  		$sql = "select * from $tb where uid=:uid and id=:id";
  		$db = Hapyfish2_Db_Factory::getDB($uid);
  	    $rdb = $db['r'];
  	    return $rdb->fetchRow($sql, array('uid'=>$uid, 'id'=>$id));
    }
    
    public function getUserDisList($uid)
    {
    	$tb = $this->getDisTb($uid);
  		$sql = "select id from $tb where uid=:uid";
  		$db = Hapyfish2_Db_Factory::getDB($uid);
  	    $rdb = $db['r'];
  	    return $rdb->fetchCol($sql, array('uid'=>$uid));
    }
    
    public function deleteUserDis($uid, $id)
    {
    	$tb = $this->getDisTb($uid);
    	$sql = "delete from $tb where uid=:uid and id=:id";
     	$db = Hapyfish2_Db_Factory::getDB($uid);
   	    $wdb = $db['w'];
        return $wdb->query($sql, array('uid'=>$uid, 'id' => $id));
    }
}