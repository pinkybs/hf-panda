<?php
/**
 * bujisky.li
 * bujisky.li@hapyfish.com
 * */
class Hapyfish2_Ipanda_Admin_Bll_AdminColumn
{
	
	
	
	
	public function getColumnInfo($column_no)
	{
		if(empty($column_no))
		{
			return 0;
		}
		$key = 'basic:admincolumn:' . $column_no;
        $cache = Hapyfish2_Cache_Factory::getBasicMC(0);
        $data = $cache->get($key);
        $data = 0;
		if(empty($data))
		{
			$adminColumn = Hapyfish2_Ipanda_Admin_Dal_AdminColumn::getDefaultInstance();
			$data = $adminColumn->get($column_no);
		
			$key = 'basic:admincolumn:' . $column_no;
	        $cache = Hapyfish2_Cache_Factory::getBasicMC(0);
	        $cache->set($key, $data);
		}
        
		return  $data;
	}
	public function getColumnList()
	{
		
		$key = 'basic:admincolumnlist:';
        $cache = Hapyfish2_Cache_Factory::getBasicMC(0);
        $data = $cache->get($key);
        $data = 0;
		if(empty($data))
		{
			$adminColumn = Hapyfish2_Ipanda_Admin_Dal_AdminColumn::getDefaultInstance();
			$data = $adminColumn->getAll();
		
			$key = 'basic:admincolumnlist:';
	        $cache = Hapyfish2_Cache_Factory::getBasicMC(0);
	        $cache->set($key, $data);
		}
        
		return  $data;
	}
	//先看下几个参数吧    
	//$num 为总共的条数   比如说这个分类下共有15篇文章    
	// $perpage为每页要显示的条数    
	//$curpage为当前的页数    
	//$mpurl为url的除去表示页数变量的一部分，    
	//$page为$multipage这个字符串中要显示的表示页数的变量个数    
	//$maxpages为最大的页数值   此函数最后有一句$maxpage = $realpages;    
	public function multi($num, $perpage, $curpage, $mpurl, $page = 10) { 
			
		$multipage = ''; 
		$realpages = 1; 
		if ($num > $perpage){ 
			$offset = 4; 
			
			$realpages = @ceil($num / $perpage); 
			if ($page > $realpages) { 
				$form = 1; 
				$to = $realpages; 
			}else { 
				$form = $curpage - $offset; 
				$to = $form + $page - 1; 
				if ($form < 1) { 
					$form = 1; 
					//$to = $curpage + 1 - $form; 
					if ($to - $form < $page) { 
						$to = $page; 
					} 
				} 
			} 
			
			$multipage = ($curpage > 1 ? '<a href="'.$mpurl.'page='.($curpage - 1).'">up</a> ' : ''); 
			for ($i = $form; $i <= $to; $i++) { 
				$multipage .= $i == $curpage ? '<strong>'.$i.'</strong> ' : 
				'<a href="'.$mpurl.'page='.$i.'">'.$i.'</a> '; 
			} 
			$multipage .= $curpage < $realpages ? '<a href="'.$mpurl.'page='.($curpage + 1).'">next</a> ' : ''; 
			$multipage = $multipage ? '<div class="pages">'.$multipage.'</div>' : ''; 
		} 
		
		return $multipage; 
	}


	
}