<?php

class Hapyfish2_Ipanda_Dal_Basic
{
    protected static $_instance;
    
    protected function getDB()
    {
    	$key = 'db_0';
    	return Hapyfish2_Db_Factory::getBasicDB($key);
    }

    /**
     * Single Instance
     *
     * @return Hapyfish2_Ipanda_Dal_Basic
     */
    public static function getDefaultInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /*
     * 日志模板
     */
    public function getFeedTemplate()
    {
    	$sql = "SELECT id,title FROM ipanda_feed_template";
    	
        $db = $this->getDB();
        $rdb = $db['r'];
    	
        return $rdb->fetchPairs($sql);
    }
    
    public function getDecorateList()
    {
    	$sql = "SELECT cid,name,class_name,content,price_type,gold_price,love_price,effect_time,can_renewal,cheap_price,cheap_start_time,cheap_end_time,sale_price,need_level,nodes,item_type,item_id,isnew,can_buy,attribute_change,attr_love,attr_exp,effect_nodes,act_name,can_sale,can_recyle,walkable,walkable_weight FROM ipanda_decorate";
    	
        $db = $this->getDB();
        $rdb = $db['r'];
    	
        return $rdb->fetchAssoc($sql);
    }
    
    public function getBuildingList()
    {
    	$sql = "SELECT cid,name,class_name,content,love_price,gold_price,unlock_level_gold,price_type,cheap_price,cheap_start_time,cheap_end_time,sale_price,need_level,need_material,effect_nodes,nodes,item_type,item_id,isnew,can_buy,safe_love_num,level,next_level_cid,act_name,durable,durable_time,need_fix,attribute,checkout_time,animal_cid,material_group_id FROM ipanda_building";
    	
        $db = $this->getDB();
        $rdb = $db['r'];
    	
        return $rdb->fetchAssoc($sql);
    }
    
	public function getMaterialGroup()
    {
    	$sql = "SELECT id,start_level,end_level,nature_type,building_level,attr_condtion,material,material_group_id FROM ipanda_get_material_group";
    	
        $db = $this->getDB();
        $rdb = $db['r'];
    	
        return $rdb->fetchAssoc($sql);
    }
    
	public function getMaterialList()
    {
    	$sql = "SELECT cid,name,class_name,use_type,gold_price FROM ipanda_material";
    	
        $db = $this->getDB();
        $rdb = $db['r'];
    	
        return $rdb->fetchAssoc($sql);
    }
    
	public function getAnimalDialogue()
    {
    	$sql = "SELECT * FROM ipanda_animal_dialogue";
    	
        $db = $this->getDB();
        $rdb = $db['r'];
    	
        return $rdb->fetchAssoc($sql);
    }
    
	public function getPhytotronAnimalList()
    {
    	$sql = "SELECT cid,name,class_name,header_class,item_id,item_type,nature_type,need_user_level,unlock_condition,product_time,price_type,love_price,gold_price,phytotron_cid,phytotron_name,phytotron_class_name,phytotron_item_id,phytotron_item_type,consume_building FROM ipanda_phytotron_animal";
    	
        $db = $this->getDB();
        $rdb = $db['r'];
    	
        return $rdb->fetchAssoc($sql);
    }
    
	public function getPhytotronUnlockList()
    {
    	$sql = "SELECT id,love_price,gold_price,need_material,level,friend_num,is_open,build_time,admin_num,admin_gold,effect_time,buy_end_time FROM ipanda_phytotron_unlock_list";
    	
        $db = $this->getDB();
        $rdb = $db['r'];
    	
        return $rdb->fetchAssoc($sql);
    }
    
    /*
     * 道具基本信息
     */
    public function getCardList()
    {
    	$sql = "SELECT cid,name,class_name,introduce,effect_cid,effect_num,price,price_type,cheap_price,cheap_start_time,cheap_end_time,sale_price,add_exp,need_level,item_type,use_type,obj_level,is_new,can_buy FROM ipanda_card";
    	
        $db = $this->getDB();
        $rdb = $db['r'];
    	
        return $rdb->fetchAssoc($sql);
    }
    
    public function getUserLevelList()
    {
    	$sql = "SELECT level,exp,energy,admin_num,rent_award,award_gold,award_material_cid,award_material_num,award_card_cid,award_card_num,award_decorate_cid,award_decorate_num FROM ipanda_level_user";
    	
        $db = $this->getDB();
        $rdb = $db['r'];
    	
        return $rdb->fetchAssoc($sql);
    }
    
	public function getAchievement()
    {
    	$sql = "SELECT id,content,`condition`,title,class_name,award_name,award_cid,award_num,group_id FROM ipanda_achievement";
    	
        $db = $this->getDB();
        $rdb = $db['r'];
    	
        return $rdb->fetchAssoc($sql);
    }
    
	public function getAnimalLevelList()
    {
    	$sql = "SELECT level,service_num,love_price FROM ipanda_level_animal";
    	
        $db = $this->getDB();
        $rdb = $db['r'];
    	
        return $rdb->fetchAssoc($sql);
    }
    
	public function getEntendForestList()
    {
    	$sql = "SELECT id,forest_no,land_num,level,total_nodes,animal_top,love_price,gold_price FROM ipanda_entend_forest";
    	
        $db = $this->getDB();
        $rdb = $db['r'];
    	
        return $rdb->fetchAssoc($sql);
    }
    
    public function getTaskTypeList()
    {
    	$sql = "SELECT id,desp,icon_class_name,is_client_action FROM ipanda_task_type";

        $db = $this->getDB();
        $rdb = $db['r'];

        return $rdb->fetchAssoc($sql);
    }
    
    public function getTaskConditionList()
    {
    	$sql = "SELECT id,desp,condition_type,cid,num,icon_class_name,complete_gold FROM ipanda_task_condition";

        $db = $this->getDB();
        $rdb = $db['r'];

        return $rdb->fetchAssoc($sql);
    }
    
    public function getTaskList()
    {
    	$sql = "SELECT id,priority,condition_ids,need_user_level,next_task_id,front_task_id,need_animal_level,title,foreword,help_desp,done_desp,guide,story,love,`exp`,gold,materials,items,decorates FROM ipanda_task";

        $db = $this->getDB();
        $rdb = $db['r'];

        return $rdb->fetchAssoc($sql);
    }
    
    public function getPandaQuestionList()
    {
    	$sql = "SELECT `id`,`animal_id`,`user_level`,`weight`,`question`,`answer_A`,`answer_B`,`answer_C`,`answer_D`,`answer`,`content`,`award_love`,`award_exp`,`award_intimacy` FROM ipanda_panda_question";

        $db = $this->getDB();
        $rdb = $db['r'];

        return $rdb->fetchAssoc($sql);
    }
    
    public function getPandaQuestionSimpleList()
    {
    	$sql = "SELECT `id`,`animal_id`,`user_level`,`weight`,`answer`,`award_love`,`award_exp`,`award_intimacy` FROM ipanda_panda_question";

        $db = $this->getDB();
        $rdb = $db['r'];

        return $rdb->fetchAssoc($sql);
    }
    
    public function getDailyAwardList()
    {
    	$sql = "SELECT `id`,`base_award`,`fans_award` FROM ipanda_daily_award";

        $db = $this->getDB();
        $rdb = $db['r'];

        return $rdb->fetchAssoc($sql);
    }
    
    public function getPaySettingList()
    {
    	$sql = "SELECT `id`,`section`,`end_time`,`note`,`next_id`,`active`,`update_time` FROM ipanda_pay_setting";

        $db = $this->getDB();
        $rdb = $db['r'];

        return $rdb->fetchAssoc($sql);
    }
    
    public function updatePaySetting($id, $info)
    {
        $tbname = 'ipanda_pay_setting';
        
        $db = $this->getDB();
        $wdb = $db['w'];
    	$where = $wdb->quoteinto('id = ?', $id);
    	
        $wdb->update($tbname, $info, $where);
    }
    
    public function getCacheVersion($key = 'default')
    {
        $sql = "SELECT `val` FROM ipanda_cache_version WHERE keyname=:keyname";
        
    	$db = $this->getDB();
        $rdb = $db['r'];
    	
        return $rdb->fetchOne($sql, array('keyname' => $key));
    }
    
    public function updateCacheVersion($key, $value)
    {
        $tbname = 'ipanda_cache_version';
        
        $db = $this->getDB();
        $wdb = $db['w'];
    	$where = $wdb->quoteinto('keyname = ?', $key);
    	$info = array('val' => $value);
        $wdb->update($tbname, $info, $where);
    }
    
}