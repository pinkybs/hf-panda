<?php

class Hapyfish2_Ipanda_Bll_UserResult
{
	private static $result = array(
		'status' => 1,
		'content' => '',
		'levelUp' => false,
		'love' => 0,
		'gold' => 0,
		'exp' => 0,
		'energy' => 0
	);
	
	private static $uid = 0;
	
	private static $field = array();
	
	//任务相关
	private static $taskCompletedId = array();
	private static $taskNewId = array();
	
	//升级相关
	private static $levelUpInfo = array();
	
	//成就
	private static $achieveCompletedId = array();
	
	//材料变更
	private static $materialChanged = false;
	//道具更新
	private static $cardChanged = false;
	
	//道具卡状态更新
	private static $cardStatusChanged = false;
	
	private static $changedData = array();
	
	public static function isChange()
	{
		if (self::$result['levelUp']) {
			return true;
		}
		if (self::$result['love'] != 0) {
			return true;
		}
		if (self::$result['gold'] != 0) {
			return true;
		}
		if (self::$result['exp'] != 0) {
			return true;
		}
		if (self::$result['energy'] != 0) {
			return true;
		}
		
		return false;
	}
	
	public static function addField($uid, $name, $value)
	{
		if (self::$uid == $uid) {
			if (!isset(self::$field[$name])) {
				self::$field[$name] = $value;
			} else {
				self::$field[$name] = array_merge(self::$field[$name], $value);
			}
		}
	}
	
	public static function field($name)
	{
		if (!isset(self::$field[$name])) {
			return null;
		} else {
			return self::$field[$name];
		}
	}
	
	public static function addTaskCompletedId($uid, $id)
	{
		if (self::$uid == $uid) {
			self::$taskCompletedId[] = $id;
		}
	}
	
	public static function getTaskCompletedId()
	{
		return self::$taskCompletedId;
	}
	
	public static function addTaskNewId($uid, $id)
	{
		if (self::$uid == $uid) {
			self::$taskNewId[] = $id;
		}
	}
	
	public static function addTaskNewIdList($uid, $list)
	{
		if (self::$uid == $uid) {
			array_merge(self::$taskNewId, $list);
		}
	}
	
	public static function getTaskNewId()
	{
		return self::$taskNewId;
	}
	
	public static function addAchieveCompletedId($uid, $id)
	{
		if (self::$uid == $uid) {
			self::$achieveCompletedId[] = $id;
		}
	}
	
	public static function getAchieveCompletedId()
	{
		return self::$achieveCompletedId;
	}
	
	public static function setUser($uid)
	{
		self::$uid = $uid;
	}
	
	public static function mergeLove($uid, $love)
	{
		if (self::$uid == $uid) {
			self::$result['love'] += $love;
		}
	}
	
	public static function mergeGold($uid, $gold)
	{
		if (self::$uid == $uid) {
			self::$result['gold'] += $gold;
		}
	}
	
	public static function mergeExp($uid, $exp)
	{
		if (self::$uid == $uid) {
			self::$result['exp'] += $exp;
		}
	}
	
	public static function mergeEnergy($uid, $energy)
	{
		if (self::$uid == $uid) {
			self::$result['energy'] += $energy;
		}
	}
	
	public static function setLevelUp($uid, $levelUp)
	{
		if (self::$uid == $uid) {
			self::$result['levelUp'] = $levelUp;
		}
	}
	
	public static function isLevelUp()
	{
		return self::$result['levelUp'];
	}
	
	public static function setLevelUpInfo($uid, $name, $value)
	{
		if (self::$uid == $uid) {
			self::$levelUpInfo[$name] = $value;
		}
	}
	
	public static function getLevelUpInfo()
	{
		return self::$levelUpInfo;
	}
	
	public static function setMaterialChange($uid, $change = true)
	{
		if (self::$uid == $uid) {
			self::$materialChanged = $change;
		}
	}
	
	public static function isMaterialChanged()
	{
		return self::$materialChanged;
	}
	
	public static function setCardChange($uid, $change = true)
	{
		if (self::$uid == $uid) {
			self::$cardChanged = $change;
		}
	}
	
	public static function isCardChanged()
	{
		return self::$cardChanged;
	}
	
	public static function setCardStatusChange($uid, $change = true)
	{
		if (self::$uid == $uid) {
			self::$cardStatusChanged = $change;
		}
	}
	
	public static function isCardStatusChanged()
	{
		return self::$cardStatusChanged;
	}
	
	public static function addChanged($uid, $cid, $num)
	{
		if (self::$uid == $uid) {
			if (isset(self::$changedData[$cid])) {
				self::$changedData[$cid] += $num;
			} else {
				self::$changedData[$cid] = $num;
			}
		}
	}
	
	public static function getChanged()
	{
		return self::$changedData;
	}
	
	public static function result()
	{
		return self::$result;
	}
}