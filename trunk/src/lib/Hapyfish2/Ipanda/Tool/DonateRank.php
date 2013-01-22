<?php

class Hapyfish2_Ipanda_Tool_DonateRank
{

	public static function listDonate($tmStart, $tmEnd)
	{

	    if (empty($tmStart)) {
	        $tmStart = 1318608000;
	    }
	    if (empty($tmEnd)) {
	        $tmEnd = time();
	    }

	    try {
            $allRows = array();
    	    for ($dbid=0; $dbid<DATABASE_NODE_NUM; $dbid++) {
    	        $db = Hapyfish2_Db_FactoryTool::getDB($dbid);
    	        $rdb = $db['r'];
    	        $tbName = 'ipanda_user_donate';
                $sql = "SELECT * FROM $tbName WHERE status=1 AND complete_time>$tmStart AND complete_time<$tmEnd ORDER BY create_time DESC LIMIT 0,100";
                $rst = $rdb->fetchAll($sql);

                if ($rst) {
                    $allRows = array_merge($allRows, $rst);
                }
    	    }
	    } catch (Exception $e) {
			info_log('listDonate:'.$e->getMessage(), 'toolDonateRank');
			return null;
		}

		return $allRows;
	}

}