<?php

class TestdataController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->baseUrl = $this->_request->getBaseUrl();
        $this->view->staticUrl = STATIC_HOST;
        $this->view->hostUrl = HOST;
    }

	public function itemlistAction()
	{
		$swfList = array(
			'mapbg.swf',
			'decorate1.swf',
			'building1.swf',
			'building2.swf',
			'phytotron1.swf',
			'items1.swf',
			'materialIcon1.swf',
			'icon1.swf'
		);
		
		$decorateList = Hapyfish2_Ipanda_Cache_Basic_Asset::getDecorateList();
		$data = array();
		
		foreach ($decorateList as $v) {
			$key = 'cid_' . $v['cid'];
			$data[$key] = array(
				'cid' => (int)$v['cid'],
				'mapClass' => $v['class_name'],
				'name' => $v['name']
			);
		}
		
		$buildingList = Hapyfish2_Ipanda_Cache_Basic_Asset::getBuildingList();
		foreach ($buildingList as $v) {
			$key = 'cid_' . $v['cid'];
			$data[$key] = array(
				'cid' => (int)$v['cid'],
				'mapClass' => $v['class_name'],
				'name' => $v['name']
			);
		}
		
		$phytotronList = Hapyfish2_Ipanda_Cache_Basic_Asset::getPhytotronAnimalList();
		foreach ($phytotronList as $v) {
			$key = 'cid_' . $v['cid'];
			$data[$key] = array(
				'cid' => (int)$v['phytotron_cid'],
				'mapClass' => $v['phytotron_class_name'],
				'name' => $v['phytotron_name']
			);
		}
		
		$cardList = Hapyfish2_Ipanda_Cache_Basic_Asset::getCardList();
		foreach ($cardList as $v) {
			$key = 'cid_' . $v['cid'];
			$data[$key] = array(
				'cid' => (int)$v['cid'],
				'mapClass' => $v['class_name'],
				'name' => $v['name']
			);
		}
		
		$materialList = Hapyfish2_Ipanda_Cache_Basic_Asset::getMaterialList();
		foreach ($materialList as $v) {
			$key = 'cid_' . $v['cid'];
			$data[$key] = array(
				'cid' => (int)$v['cid'],
				'mapClass' => $v['class_name'],
				'name' => $v['name']
			);
		}
		
		//爱心
		$data['cid_1'] = array(
			'cid' => 1,
			'mapClass' => 'icon.1.love',
			'name' => '爱心'
		);
		
		//金币
		$data['cid_2'] = array(
			'cid' => 2,
			'mapClass' => 'icon.1.gold',
			'name' => '金币'
		);
		
		$result = array(
			'swfHost' => STATIC_HOST . '/swf/',
			'swfs' => $swfList,
			'data' => $data
		);
		
		echo json_encode($result);
		exit;
	}
	
 }

