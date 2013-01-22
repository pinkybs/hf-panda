<?php


class Hapyfish2_Ipanda_Dal_Donate
{
    protected static $_instance;

    /**
     * Single Instance
     *
     * @return Hapyfish2_Ipanda_Dal_Donate
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
    	return 'ipanda_user_donate';
    }

    public function getDonate($uid, $donateid)
    {
        $tbname = $this->getTableName($uid);
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];

        $sql = "SELECT * FROM $tbname WHERE donateid=:donateid AND uid=:uid";
        return $rdb->fetchRow($sql, array('donateid' => $donateid, 'uid' => $uid));
    }

    public function regDonate($uid, $info)
    {
        $tbname = $this->getTableName($uid);
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
    	return $wdb->insert($tbname, $info);
    }

    public function completeDonate($uid, $donateid, $info)
    {
        $tbname = $this->getTableName($uid);
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
    	$where = $wdb->quoteinto('donateid = ?', $donateid);

        return $wdb->update($tbname, $info, $where);
    }

    public function listDonate($uid, $pageIndex=1, $pageSize=10)
    {
    	$start = ($pageIndex - 1) * $pageSize;
    	$tbname = $this->getTableName($uid);
    	$db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
        //'2011-10-15'
        $sql = "SELECT * FROM $tbname WHERE uid=:uid AND create_time>1318608000 ORDER BY create_time DESC LIMIT $start,$pageSize";
        return $rdb->fetchAll($sql, array('uid' => $uid));
    }

}