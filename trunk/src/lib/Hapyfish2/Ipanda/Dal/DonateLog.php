<?php


class Hapyfish2_Ipanda_Dal_DonateLog
{
    protected static $_instance;

    /**
     * Single Instance
     *
     * @return Hapyfish2_Ipanda_Dal_DonateLog
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
    		return 'ipanda_user_donatelog';
    	}

    	$id = floor($uid/DATABASE_NODE_NUM) % 10;
    	return 'ipanda_user_donatelog_' . $id;
    }

    public function listDonate($uid, $limit = 50)
    {
    	$tbname = $this->getTableName($uid);
    	$sql = "SELECT * FROM $tbname WHERE uid=:uid ORDER BY create_time DESC";
    	if ($limit > 0) {
    		$sql .= ' LIMIT ' . $limit;
    	}

        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];

        return $rdb->fetchAll($sql, array('uid' => $uid));
    }

    public function insert($uid, $info)
    {
		$tbname = $this->getTableName($uid);

        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];

    	return $wdb->insert($tbname, $info);
    }

}