<?php

class Hapyfish2_Ipanda_Stat_Log_Levelup
{
	public static function getLevelupCount($day, $file)
	{
        info_log('/*****levelupcount:start-'.$day.'******/'.time(), 'stat');
        $content = file_get_contents($file);
        if (empty($content)) {
            info_log('no data', 'stat');
            return;
        }
        $temp = explode("\n", $content);
        
        $levelupArray = array();
        
        foreach ($temp as $line) {
            if (empty($line)) {
                continue;
            }
            $r = explode("\t", $line);
            $uid= $r[2];
            $level = $r[3];
            
            if ( isset($levelupArray[$level]) ) {
            	$levelupArray[$level]++;
            }
            else {
            	$levelupArray[$level] = 1;
            }
        }
        
        $data = array('log_time' => $day, 'levelup' => json_encode($levelupArray));
        
        $dal = Hapyfish2_Ipanda_Stat_Dal_Levelup::getDefaultInstance();
        $dal->insert($data);
        
        info_log('/*****levelupcount:end******/', 'stat');
        return $data;
	}
	
}