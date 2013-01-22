<?php

//Admin Username
define('ADMIN_USERNAME','admin');
//Admin Password
define('ADMIN_PASSWORD','hapyfish@2011');  	

class HapyfishController extends Zend_Controller_Action
{
	public function init()
	{		
		if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
           $_SERVER['PHP_AUTH_USER'] != ADMIN_USERNAME ||$_SERVER['PHP_AUTH_PW'] != ADMIN_PASSWORD) {
			Header("WWW-Authenticate: Basic realm=\"Happy Ipanda, Login\"");
			Header("HTTP/1.0 401 Unauthorized");

			echo <<<EOB
				<html><body>
				<h1>Rejected!</h1>
				<big>Wrong Username or Password!</big>
				</body></html>
EOB;
			exit;
		}
		
		$this->view->baseUrl = $this->_request->getBaseUrl();
        $this->view->staticUrl = STATIC_HOST;
        $this->view->hostUrl = HOST;
        $this->view->appId = APP_ID;
        $this->view->appKey = APP_KEY;
	}
	
	function vaild()
	{

	}

	function check()
	{
		
	}
	function indexAction()
	{
		$this->render();
	}
	function leftAction()
	{
		$columnlist = Hapyfish2_Ipanda_Admin_Bll_AdminColumn::getColumnList();
		
		$this->view->list = $columnlist;
        $this->render();
	}
	function listAction()
	{
		$column_no = $this->_request->getParam('column_no');
		$page = $this->_request->getParam('page');
		$keyword = $this->_request->getParam('keyword');
		$field = $this->_request->getParam('field');
		$num = 20;
		if(empty($column_no))
		{
			echo "error";exit();
		}
		if(empty($page))
		{
			$page = 1;
		}
		$columnInfo = Hapyfish2_Ipanda_Admin_Bll_AdminColumn::getColumnInfo($column_no);
		
		$fieldInfo = Hapyfish2_Ipanda_Admin_Bll_AdminField::getFieldInfo($column_no);
		
		$arr = explode(",",$columnInfo['primary_key']);
		
		$adminColumn = Hapyfish2_Ipanda_Admin_Dal_AdminColumn::getDefaultInstance();
		$where = "";
		if(!empty($keyword))
		{
			$where = "where $field like '%$keyword%'";
		}
		$ret = $adminColumn->getBasicList($columnInfo['table_name'], $page, $num,$fieldInfo,$where);
		
		$list = array();
		foreach ($ret as $key => $value )
		{
			$data =array();
			foreach ($fieldInfo as $k => $v)
			{
				$data[$v['field_db_name']] = $value[$v['field_db_name']];
			}
			
			if(sizeof($arr) > 1)
			{
				$pvalue =array();
				for($i=0;$i<sizeof($arr);$i++)
				{
					$pvalue[] = $value[$arr[$i]];
				}
				$data['primaryKey'] = join(",",$pvalue);
			}
			else 
			{
			
				$data['primaryKey'] = $value[$columnInfo['primary_key']];
			}
			$list[] = $data;
		}
		
		$count = $adminColumn->getBasicCount($columnInfo['table_name'],$where);
		var_dump($count );
		$fengye = Hapyfish2_Ipanda_Admin_Bll_AdminColumn::multi($count['num'],$num,$page,"list?column_no=".$column_no."&keyword=".$keyword."&field=".$field."&");
		$this->view->columnInfo = $columnInfo;
 		$this->view->fieldInfo = $fieldInfo;
 		$this->view->list = $list;
 		$this->view->fengye = $fengye;
 		$width = sizeof($fieldInfo) * 80 +100;
 		$this->view->width = $width;
        $this->render();
	}
	
	function editAction()
	{
		
		$column_no = $this->_request->getParam('column_no');
		$cid = $this->_request->getParam('cid');
		if(empty($column_no))
		{
			echo "error";exit();
		}
		
		$columnInfo = Hapyfish2_Ipanda_Admin_Bll_AdminColumn::getColumnInfo($column_no);
		
		$fieldInfo = Hapyfish2_Ipanda_Admin_Bll_AdminField::getFieldInfo($column_no);
		
		$arr = explode(",",$columnInfo['primary_key']);
		if(sizeof($arr) > 1)
		{
			$valueArr = explode(",",$cid);
			if(sizeof($valueArr) != sizeof($arr))
			{
				echo "主键值不够！";exit;
			}
			$c = array();
			foreach ($arr as $key => $value)
			{
				$c[] = $value."='".$valueArr[$key]."'";
			}
			$cstr = join(" and ", $c);
			$where = $cstr;
		}
		else
		{
			$where = $columnInfo['primary_key']."='$cid'";
		}
		
		$action = $_POST['action'];
		//var_dump($_POST);
		if($action == "edit")
		{
			$data = array();
			foreach ($fieldInfo as $key => $value)
			{
				$field = $value['field_db_name'];
				$data[$field] = $_POST[$field];
			}
			if(is_array($data))
			{
				$adminColumn = Hapyfish2_Ipanda_Admin_Dal_AdminColumn::getDefaultInstance();
				$ret = $adminColumn->basicUpdate($data, $columnInfo['table_name'],$where);
			}
			echo "<a href='list?column_no=".$column_no."'>OK</a>";exit;
		}
		$adminColumn = Hapyfish2_Ipanda_Admin_Dal_AdminColumn::getDefaultInstance();
		
		//if()
		
		$columnData = $adminColumn->getBasicData($where, $columnInfo['table_name'],$fieldInfo);
		//var_dump($columnData);exit;
		foreach ($fieldInfo as $key => $value)
		{
			$value['default_value'] =  $columnData[$value['field_db_name']];
			$data[] = $value;
		}
		//var_dump($data);
		$this->view->columnInfo = $columnInfo;
 		$this->view->fieldInfo = $fieldInfo;
 		$this->view->data = $data;
        $this->render();
	}
	
	function addAction()
	{
		
		$column_no = $this->_request->getParam('column_no');
		if(empty($column_no))
		{
			echo "error";exit();
		}
		$columnInfo = Hapyfish2_Ipanda_Admin_Bll_AdminColumn::getColumnInfo($column_no);
		
		$fieldInfo = Hapyfish2_Ipanda_Admin_Bll_AdminField::getFieldInfo($column_no);
		
		$action = $_POST['action'];
		//var_dump($_POST);
		if($action == "add")
		{
			$data = array();
			foreach ($fieldInfo as $key => $value)
			{
				$field = $value['field_db_name'];
				$data[$field] = $_POST[$field];
			}
			if(is_array($data))
			{
				$adminColumn = Hapyfish2_Ipanda_Admin_Dal_AdminColumn::getDefaultInstance();
				$ret = $adminColumn->basicAdd($data, $columnInfo['table_name']);
			}
			echo "<a href='add?column_no=".$column_no."'>OK</a>";exit;
		}
		
		$this->view->columnInfo = $columnInfo;
 		$this->view->fieldInfo = $fieldInfo;
        $this->render();
		
	}
	function importAction()
	{
		$column_no = $this->_request->getParam('column_no');
		if(empty($column_no))
		{
			echo "error";exit();
		}
		
		$columnInfo = Hapyfish2_Ipanda_Admin_Bll_AdminColumn::getColumnInfo($column_no);
		if (isset($_POST['import'])){
		 
		    $file = $_FILES['filename'];
		    
		    $file_type = substr(strstr($file['name'],'.'),1);
		     
		    // 检查文件格式
		    if ($file_type != 'csv' && $file_type != 'txt'){
		        echo '文件格式不对,请重新上传!';
		        exit;
		    }
		    $handle = fopen($file['tmp_name'],"r");
		    $file_encoding = mb_detect_encoding($handle);
		    
		    
		    $k = 0;
		   $fields = array();
		   $sql_value = array();
		    while ($row = fgets($handle)){
		        //echo "<font color=red>$row</font>";  //可以知道总共有多少行
//		        var_dump(sizeof($row));
//		        var_dump($row);
//		        echo "<br>";
				if(sizeof($row) == 1)
				{
					$data = explode("	", $row);
				}
				else 
				{
					$data = $row;
				}
		    	
		    	$k++;
		        
		       if ($k == 1)
		       {
					$num = count($data);
					for ($i=0; $i<$num; $i++){
					 $fields[] = "`".trim($data[$i])."`";
					} 
					
		        continue;
		       }
		        $num = count($data);
		        // 这里会依次输出每行当中每个单元格的数据
		        $value = array();
		        for ($i=0; $i<$num; $i++){
		           
		            $value[] = "'".trim($data[$i])."'";
		            // 在这里对数据进行处理
		        }
		        $sql_value[] = "(".join(",",$value).")";
		    }
		    $sql = "insert into ".$columnInfo['table_name']." (".join(",",$fields).") values ".join(",",$sql_value)."";
	
		    fclose($handle);
		    $adminColumn = Hapyfish2_Ipanda_Admin_Dal_AdminColumn::getDefaultInstance();
		    $ret = $adminColumn->basicQuery($sql);
			//var_dump($ret);
			
		}
		echo "<br>";
		echo "<a href='list?column_no=".$column_no."'>OK</a>";exit;
		exit;	
	}
	function clearAction()
	{
		$column_no = $this->_request->getParam('column_no');
		if(empty($column_no) || $column_no == "999001" || $column_no == "999002")
		{
			echo "error $column_no";exit();
		}
		$str = $this->_request->getParam('str');
		if($str != "eric")
		{
			echo "<a href='list?column_no=".$column_no."'>密码不对</a>";exit;
		}
		$columnInfo = Hapyfish2_Ipanda_Admin_Bll_AdminColumn::getColumnInfo($column_no);
		
		$sql = "TRUNCATE ".$columnInfo['table_name']." ;";
		echo "$sql";
	    $adminColumn = Hapyfish2_Ipanda_Admin_Dal_AdminColumn::getDefaultInstance();
	    $ret = $adminColumn->basicQuery($sql);
		echo "<a href='list?column_no=".$column_no."'>OK</a>";exit;
	}
	function alterAction()
	{
		
		$columnInfo = Hapyfish2_Ipanda_Admin_Bll_AdminColumn::getColumnInfo($column_no);
		$columnInfo['column_name'] = "修改表结构(alter)";
		$fieldInfo = Hapyfish2_Ipanda_Admin_Bll_AdminField::getFieldInfo($column_no);
		$fieldInfo = array(
			array("field_name" => "数据表","type" => 0 ,"field_db_name" => "table_name" ,"default_value" => "ipanda_user_","comment" => "" ),
			array("field_name" => "分库序号","type" => 0 ,"field_db_name" => "database_num" ,"default_value" => "0","comment" => "第几个库" ),
			array("field_name" => "分表数","type" => 0 ,"field_db_name" => "table_num" ,"default_value" => "10","comment" => "分表数" ),
			array("field_name" => "sql语句","type" => 1 ,"field_db_name" => "sql" ,"default_value" => "","comment" => "sql语句，多个用;连接","w" => 30,"h"=> 5 ),

		);
		$action = $_POST['action'];
		//var_dump($_POST);
		if($action == "add")
		{
			$table = $_POST['table_name'];
			$dbnum = $_POST['database_num'];
			$tbnum = $_POST['table_num'];
			$sql = $_POST['sql'];
	
			//for($i = 0 ; $i < $dbnum ; $i++)
			//{
			$i =$dbnum;
				$allsql = "";
				for($j = 0 ; $j < $tbnum ; $j++)
				{
					$thesql = str_replace("`".$table."`" , "`".$table."_".$j."`" , $sql);
					$allsql .= $thesql;
					$adminColumn = Hapyfish2_Ipanda_Admin_Dal_AdminColumn::getDefaultInstance();
	   				
	   				echo $thesql;echo "\n";
				}
				//$ret = $adminColumn->userQuery($i, $allsql);
			//}
			exit;
			echo "<a href='alter'>OK</a>";exit;
		}
		
		$this->view->columnInfo = $columnInfo;
 		$this->view->fieldInfo = $fieldInfo;
        $this->render();
	}
}