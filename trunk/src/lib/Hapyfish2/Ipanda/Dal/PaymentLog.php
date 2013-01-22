<?php


class Hapyfish2_Ipanda_Dal_PaymentLog
{
    protected static $_instance;

    /**
     * Single Instance
     *
     * @return Hapyfish2_Ipanda_Dal_PaymentLog
     */
    public static function getDefaultInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function getPaymentLogTableName($uid)
    {
        if (APP_STATUS_DEV == 4) {
    		return 'ipanda_user_paylog';
    	}

    	$id = floor($uid/DATABASE_NODE_NUM) % 10;
    	return 'ipanda_user_paylog_' . $id;
    }

    public function getPayment($uid, $limit = 50)
    {
    	$tbname = $this->getPaymentLogTableName($uid);
    	$sql = "SELECT amount,gold,summary,orderid,create_time FROM $tbname WHERE uid=:uid ORDER BY create_time DESC";
    	if ($limit > 0) {
    		$sql .= ' LIMIT ' . $limit;
    	}

        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];

        return $rdb->fetchAll($sql, array('uid' => $uid));
    }

    public function insert($uid, $info)
    {
		$tbname = $this->getPaymentLogTableName($uid);

        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];

    	return $wdb->insert($tbname, $info);
    }

    public function checkNewPayUser($uid)
    {
    	$tbname = $this->getPaymentLogTableName($uid);

    	$sql = "SELECT uid FROM $tbname WHERE uid=:uid AND amount <> 1";

		$db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];

        $hasData = $rdb->fetchOne($sql, array('uid' => $uid));

        $result = $hasData ? 1 : 0;

        return $result;
    }

}