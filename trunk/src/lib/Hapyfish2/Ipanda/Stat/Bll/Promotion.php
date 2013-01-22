<?php

class Hapyfish2_Ipanda_Stat_Bll_Promotion
{

    public static $_aryPromotionInfo = array(
        			'p10000' => array('code'=>'p10000', 's_code'=>'cDEwMDAw', 'name'=>'测试活动', 'start'=>'', 'end'=>'', 'des'=>'活动描述')
    );

	/**
     * promte id
     * @var string
     */
    protected $_promoteId;

    /**
     * start time
     * @var int
     */
    protected $_startTime;

    /**
     * end time
     * @var int
     */
    protected $_endTime;

    protected $_errCode;

    /**
     * __construct() -
     *
     * @param string $promoteId
     * @return void
     */
    public function __construct($promoteId)
    {
        $this->_errCode = '';
        $info = $this->getPromoteInfo($promoteId);
        if ($info) {
            $this->_promoteId = $promoteId;
            $this->_startTime = $info['start'];
            $this->_endTime = $info['end'];;
        }
        else {
            $this->_errCode = 'promotion_not_found';
        }
    }

    public function getErrCode()
    {
        return $this->_errCode;
    }

    public function getPromoteList()
    {
        $list = self::$_aryPromotionInfo;
        return $list;
    }

    public function getPromoteInfo($promoteId)
    {
        $list = $this->getPromoteList();
        if (isset($list[$promoteId])) {
            return $list[$promoteId];
        }
        return null;
    }

    public function report($logName)
	{
	    $tm = time();
	    $valid = true;
        if ($this->_startTime) {
            if ($tm < $this->_startTime) {
                $valid = false;
            }
        }
        if ($this->_endTime) {
            if ($tm > $this->_endTime) {
                $valid = false;
            }
        }

        if ($valid && $logName) {
            try {
                $log = Hapyfish2_Util_Log::getInstance();
                $log->report($logName, array($this->_promoteId));
            }
    		catch (Exception $e) {
    		    $this->_errCode = 'write_log_failed';
                err_log('Stat_Bll_Promotion.report:'. $e->getMessage());
    		    return 0;
    		}
    		return 1;
        }

        return 0;
	}

}