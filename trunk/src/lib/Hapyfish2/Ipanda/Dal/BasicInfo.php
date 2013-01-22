<?php

class Hapyfish2_Ipanda_Dal_BasicInfo
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
     * @return Hapyfish2_Ipanda_Dal_BasicInfo
     */
    public static function getDefaultInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
   
    /**
     * 公告信息
     */
    public function getNoticeList()
    {
    	$sql = "SELECT id,title,position,priority,link,opened,create_time FROM ipanda_notice WHERE opened=1 ORDER BY position ASC,create_time DESC,priority ASC";
    	
        $db = $this->getDB();
        $rdb = $db['r'];
    	
        return $rdb->fetchAll($sql);
    }
    
    /**
     * 更新公告
     *
     * @param int $id
     * @param Array $info
     */
    public function updateNotice($id, $info)
    {
        $tbname = 'ipanda_notice';
        
        $db = $this->getDB();
        $wdb = $db['w'];
    	$where = $wdb->quoteinto('id = ?', $id);
    	
        $wdb->update($tbname, $info, $where);
    }
    
    public function addNotice($info)
    {
		$tbname = 'ipanda_notice';

        $db = $this->getDB();
        $wdb = $db['w'];
        
    	$wdb->insert($tbname, $info);
        return $wdb->lastInsertId();
    }
    
}