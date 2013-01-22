<?php


class Hapyfish2_Ipanda_Dal_Repair
{
    protected static $_instance;

    /**
     * Single Instance
     *
     * @return Hapyfish2_Ipanda_Dal_Repair
     */
    public static function getDefaultInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function getUserTableName($tableId)
    {
    	if (APP_STATUS_DEV == 4) {
    		return 'ipanda_user_info';
    	}
    	
    	return 'ipanda_user_info_' . $tableId;
    }

    public function getUidListByPage($dbId, $tableId)
    {
        $db = Hapyfish2_Db_FactoryTool::getDB($dbId);
        $rdb = $db['r'];
    	
    	$tbname = $this->getUserTableName($tableId);
        $sql = "SELECT uid FROM $tbname";
        
        return $rdb->fetchAll($sql);
    }
    
}