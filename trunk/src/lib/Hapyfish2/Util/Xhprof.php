<?php

class Hapyfish2_Util_Xhprof
{
    protected static $_instance;
    
    private $_namespace;
    
    private $_uid;

    public function __construct()
    {
		$this->_namespace = 'Default_Ipanda';
		$this->_uid = 0;
    }

    /**
     * single instance of Hapyfish2_Util_Xhprof
     *
     * @return Hapyfish2_Util_Xhprof
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    
    public function setNameSpace($namespace)
    {
    	$this->_namespace = $namespace;
    }
    
    public function setUid($uid)
    {
    	$this->_uid = $uid;
    }
    
    public function start()
    {
    	//xhprof_enable();
    	
    	//不记录内置的函数
		//xhprof_enable(XHPROF_FLAGS_NO_BUILTINS);
		
    	//同时分析CPU和Mem的开销
		xhprof_enable(XHPROF_FLAGS_NO_BUILTINS + XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
    }
    
    public function stop()
    {
    	$xhprof_data = xhprof_disable();
     	//display raw xhprof data for the profiler run
     	//print_r($xhprof_data);

     	$XHPROF_ROOT = LIB_DIR . '/xhprof';

     	include_once $XHPROF_ROOT . '/utils/xhprof_lib.php';
     	include_once $XHPROF_ROOT . '/utils/xhprof_runs.php';

     	// save raw data for this profiler run using default
     	// implementation of iXHProfRuns.
     	$xhprof_runs = new XHProfRuns_Default();

     	// save the run under a namespace
     	$run_id = $xhprof_runs->save_run($xhprof_data, $this->_namespace);
     	
     	$XHPROF_DISPLAY_FILE = DOC_DIR . '/xhprof.html';

    	$content = '<a target="_blank" href="http://xhprof.happyfish001.com/index.php?run=' . $run_id 
    		. '&source=' . $this->_namespace . '">' . $this->_namespace;
		if ($this->_uid > 0) {
			$content .= '[' . $this->_uid . ']';
		}
    	
    	$content .= '(' . $run_id . ')</a><br/>';
    			 
    	file_put_contents($XHPROF_DISPLAY_FILE, $content, FILE_APPEND);
    }
}