<?php


class Hapyfish2_Ipanda_Dal_PhytotronAnimal
{
    protected static $_instance;

    /**
     * Single Instance
     *
     * @return Hapyfish2_Ipanda_Dal_PhytotronAnimal
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
    		return 'ipanda_user_phytotron_animal';
    	}
    	
    	$id = floor($uid/DATABASE_NODE_NUM) % 20;
    	return 'ipanda_user_phytotron_animal_' . $id;
    }
    
    
    public function getAll($uid)
    {
        $tbname = $this->getTableName($uid);
    	$sql = "SELECT * FROM $tbname WHERE uid=:uid";
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
    	
        return $rdb->fetchAssoc($sql, array('uid' => $uid));
    }
    
    public function getAllIds($uid)
    {
        $tbname = $this->getTableName($uid);
    	$sql = "SELECT animal_cid FROM $tbname WHERE uid=:uid";
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
    	
        return $rdb->fetchCol($sql, array('uid' => $uid));
    }
    
    public function getOne($uid, $cid)
    {
        $tbname = $this->getTableName($uid);
    	$sql = "SELECT animal_cid,ipanda_phytotron_unlock_list_id,phytotron_cid,service_num,animal_level FROM $tbname WHERE uid=:uid AND animal_cid=:cid";
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
    	
        return $rdb->fetchRow($sql, array('uid' => $uid, 'cid' => $cid), Zend_Db::FETCH_NUM);
    }
    
    public function getList($uid)
    {
        $tbname = $this->getTableName($uid);
    	$sql = "SELECT animal_cid,ipanda_phytotron_unlock_list_id,phytotron_cid,service_num,animal_level FROM $tbname WHERE uid=:uid";
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
    	
        return $rdb->fetchAssoc($sql, array('uid' => $uid));
    }
    
    public function updateByCid($uid, $cid, $info)
    {
        $tbname = $this->getTableName($uid);
        
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
    	$uid = $wdb->quote($uid);
        $cid = $wdb->quote($cid);
    	$where = "uid=$uid AND animal_cid=$cid";
    	
        return $wdb->update($tbname, $info, $where); 
    }
    
    public function insert($uid, $info)
    {
        $tbname = $this->getTableName($uid);

        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
    	return $wdb->insert($tbname, $info);
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

        $sql = "INSERT INTO $tbname(uid,id,phytotron_cid,phytotron_class_name,phytotron_name,
				animal_cid,animal_class_name,header_class,animal_name)
            VALUES (:uid, 1, 131, 'phytotron.1.xiongmao', '熊猫培育屋', 161, 'animal.1.xiongmao1', 'headIcon.1.xiongmao', '熊猫')";

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