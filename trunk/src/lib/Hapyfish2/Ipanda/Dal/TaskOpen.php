<?php

class Hapyfish2_Ipanda_Dal_TaskOpen
{
    protected static $_instance;

    /**
     * Single Instance
     *
     * @return Hapyfish2_Ipanda_Dal_TaskOpen
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
    		return 'ipanda_user_task_open';
    	}
    	
    	$id = floor($uid/DATABASE_NODE_NUM) % 10;
    	return 'ipanda_user_task_open_' . $id;
    }

    public function get($uid)
    {
        $tbname = $this->getTableName($uid);
    	$sql = "SELECT list,list2,data,buffer_list FROM $tbname WHERE uid=:uid";

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

        return $wdb->update($tbname, $info, $where);
    }

    public function init($uid)
    {
        $tbname = $this->getTableName($uid);

        $sql = "INSERT INTO $tbname(uid, list, list2, data, buffer_list) VALUES(:uid, '[1,2,4,16]', '[]', '[]', '[]')";

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