<?php

class Hapyfish2_Ipanda_Stat_Bll_Christmas
{

	public static function get($file)
	{
		$content = file_get_contents($file);
		if (empty($content)) {
			print_r('空的');
			return;
		}
		
		$temp = explode("\n", $content);
		$data = array();
		$num = array();
		foreach($temp as $line) {
			if (empty($line)) {
				continue;
			}
			
			$r = explode("\t", $line);
			//$uid = $r[2];
			$h = date('H',$r[0]);
			$list[$h][$r[3]] += $r[4];
			if(isset($num[$r[2]])){
				$num[$r[2]] +=1;
			}else{
				$num[$r[2]] = 1;
			}
		}
		$count = count($num);
		return array('list'=>$list, 'num'=>$count);
	}
	
	public static function getExchange($file)
	{
		$content = file_get_contents($file);
		if (empty($content)) {
			print_r('空的');
			return;
		}
		
		$temp = explode("\n", $content);
		$data = array();
		$num = array();
		foreach($temp as $line) {
			if (empty($line)) {
				continue;
			}
			
			$r = explode("\t", $line);
			//$uid = $r[2];
			if(isset($num[$r[3]])){
				$num[$r[3]] +=1;
			}else{
				$num[$r[3]] = 1;
			}
		}
		return $num;
	}
}