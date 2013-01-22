<?php


class Hapyfish2_Ipanda_Stat_Dal_PaymentLog
{
    protected static $_instance;

    private $_tb_day_paylist = 'day_paylist';
    
    /**
     * Single Instance
     *
     * @return Hapyfish2_Ipanda_Stat_Dal_PaymentLog
     */
    public static function getDefaultInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function getPaymentLogTableName($id)
    {
    	return 'ipanda_user_paylog_' . $id;
    }
    
    public function getGoldTableName($tbId, $yearmonth)
    {
        return 'ipanda_user_goldlog_' . $yearmonth . '_' . $tbId;
    }
    
    public function getPayorderTableName()
    {
        return 'ipanda_user_payorder';
    }
    
    public function getPaymentLogData($dbId, $tbId, $begin, $end)
    {
    	$tbname = $this->getPaymentLogTableName($tbId);
    	$sql = "SELECT uid,amount,gold,user_level,create_time FROM $tbname WHERE create_time>=:begin AND create_time<:end";

        $db = Hapyfish2_Db_FactoryTool::getDB($dbId);
        $rdb = $db['r'];
    	
        return $rdb->fetchAll($sql, array('begin' => $begin, 'end' => $end));
    }

    public function getGold($dbId, $tbId, $yearmonth, $begin, $end)
    {
        $tbname = $this->getGoldTableName($tbId, $yearmonth);
        $sql = "SELECT SUM(cost) AS allcost FROM $tbname WHERE create_time>=:begin AND create_time<:end ";
        $db = Hapyfish2_Db_FactoryTool::getDB($dbId);
        $rdb = $db['r'];
        
        return $rdb->fetchOne($sql, array('begin' => $begin, 'end' => $end));
    }
    
    public function insertPayCountByAmount($info)
    {
        $tbname = $this->_tb_day_paylist;

        $db = Hapyfish2_Db_FactoryStat::getStatLogDB();
        $wdb = $db['w'];
        
        return $wdb->insert($tbname, $info);
    }
    
    public function getPayCountByAmount($dbId)
    {
        $tbname = $this->getPayorderTableName();
        $sql = "SELECT amount,COUNT(1) AS count FROM $tbname WHERE status=1 GROUP BY amount";
        $db = Hapyfish2_Db_FactoryTool::getDB($dbId);
        $rdb = $db['r'];
        
        return $rdb->fetchAll($sql);
    }
    
    public function getFirstPayLevel($dbId, $begin, $end)
    {
        $tbname = $this->getPayorderTableName();
        $sql = "SELECT uid,user_level FROM $tbname WHERE status=1 AND complete_time>=:begin AND complete_time<:end GROUP BY uid ";

        $db = Hapyfish2_Db_FactoryTool::getDB($dbId);
        $rdb = $db['r'];
        return $rdb->fetchAll($sql, array('begin' => $begin, 'end' => $end));
    }

    public function getAllPayLevel($dbId)
    {
        $tbname = $this->getPayorderTableName();
        $sql = "SELECT user_level,COUNT(1) AS count,SUM(amount) AS amount FROM $tbname WHERE status=1 GROUP BY user_level ";

        $db = Hapyfish2_Db_FactoryTool::getDB($dbId);
        $rdb = $db['r'];
        
        return $rdb->fetchAll($sql);
    }

    public function getDayPaylist($day)
    {
        $tbname = $this->_tb_day_paylist;
        $sql = "SELECT * FROM $tbname WHERE log_time=:day";
        
        $db = Hapyfish2_Db_FactoryStat::getStatLogDB();
        $rdb = $db['r'];
        
        return $rdb->fetchRow($sql, array('day' => $day));
    }
}