<?php

class Hapyfish2_Ipanda_Bll_Compensation extends Hapyfish2_Ipanda_Bll_Award
{
	protected $_uids;

	protected $_blockUids;

	protected $_feedTitle;
	
	protected $_sendFeed;

	public function __construct()
	{
		$this->_uids = array();
		$this->_blockUids = array();
		$this->_feedTitle = '';
		$this->_sendFeed = true;
	}

	public function setUid($uid)
	{
		$this->_uids[] = $uid;
	}

	public function setUids($uids)
	{
		foreach ($uids as $uid) {
			$this->_uids[] = $uid;
		}
	}

	public function setBlockUids($begin, $end)
	{
		$this->_blockUids[] = array('begin' => $begin, 'end' => $end);
	}

	public function setFeedTitle($title)
	{
		$this->_feedTitle = $title;
	}
	
	public function setSendFeed($value)
	{
		if ($value) {
			$this->_sendFeed = true;
		} else {
			$this->_sendFeed = false;
		}
	}

	public function send($feedPrefix = '')
	{
		$num = 0;
		foreach ($this->_uids as $uid) {
			$ok = $this->doOne($uid, $feedPrefix);
			if ($ok) {
				$num++;
			}
		}

		foreach ($this->_blockUids as $block) {
			$begin = $block['begin'];
			$end = $block['end'];
			for($i = $begin; $i <= $end; $i++) {
				$ok = $this->doOne($i, $feedPrefix);
				if ($ok) {
					$num++;
				}
			}
		}

		return $num;
	}

	public function doOne($uid, $feedPrefix)
	{
		$isAppUser = Hapyfish2_Ipanda_Cache_User::isAppUser($uid);
		if (!$isAppUser) {
			return false;
		}

		$ok = $this->sendOne($uid);
		if ($ok && $this->_sendFeed) {
			$content = $this->getContent($uid);
			if (count($content) > 0) {
				if ($this->_feedTitle != '') {
					$title = $feedPrefix . $this->_feedTitle;
				} else {
					$title = $feedPrefix . implode(',', $content);
				}
				$feed = array(
					'uid' => $uid,
					'template_id' => 0,
					'actor' => GM_UID_LELE,
					'target' => $uid,
					'type' => 9,
					'title' => array('title' => $title),
					'create_time' => time()
				);
				Hapyfish2_Ipanda_Bll_Feed::insertMiniFeed($feed);
				
				$this->clearContent($uid);
			}
		}
		
		return $ok;
	}

}