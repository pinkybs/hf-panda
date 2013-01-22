<?php /* Smarty version 2.6.19, created on 2011-05-16 15:18:26
         compiled from index/index.phtml */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>快乐岛主</title>
<link href="<?php echo $this->_tpl_vars['staticUrl']; ?>
/apps/island/main_2011041101.css?v=1.01" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
a img,:link img,:visited img {
	border: 0 none;
}

object#swfcontent {
	display: inline;
}

.box {
	border: 1px solid #CCCCCC;
}
-->
</style>
</head>
<body>
<div class="main" style="height:840px;">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.phtml", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="main_content">
		<div id="loadingdiv" style="position: absolute; left: 350px; top: 250px;"><img src="<?php echo $this->_tpl_vars['staticUrl']; ?>
/apps/island/images/common/loading0.gif" /><br/>加载中......</div>
		<div id="flashdiv"></div>
		<script src="<?php echo $this->_tpl_vars['staticUrl']; ?>
/js/swfobject.js" type="text/javascript"></script>
		<script type="text/javascript">
			var VIEWER_ID = <?php echo $this->_tpl_vars['uid']; ?>
;
			var OWNER_ID = VIEWER_ID;
			var FIRST_LOGIN = <?php echo $this->_tpl_vars['newuser']; ?>
;
		
			function getCookie(name) {
				var arr = document.cookie.match(new RegExp('(^| )'+name+'=([^;]*)(;|$)'));
				if(arr != null) return unescape(arr[2]); return null;
			}
			var hf_skey = getCookie('hf_skey');
			if (hf_skey) {
	        	var flashVars={"ver":"4","pNum":"10","uid":"<?php echo $this->_tpl_vars['uid']; ?>
","loadingUi":"<?php echo $this->_tpl_vars['staticUrl']; ?>
/swf/loadingUi2.swf?v=2011040701", "interfaceUrl":"<?php echo $this->_tpl_vars['hostUrl']; ?>
/api/initswf?v=2011020901","snsType":"renren","charge":"1"};
	        	var params = { base: "<?php echo $this->_tpl_vars['staticUrl']; ?>
/", allowScriptAccess: "always", menu:"false", wmode: "opaque", allowFullScreen: "true", bgcolor: "#ffffff", align: "middle", quality: "high"};
	        	var attrs = { id: "islandLoader", name: "islandLoader" };
	        	swfobject.embedSWF("<?php echo $this->_tpl_vars['staticUrl']; ?>
/swf/islandLoader.swf?v=2011040701", "flashdiv",  "748", "600", "10.0", "<?php echo $this->_tpl_vars['staticUrl']; ?>
/expressInstall.swf", flashVars, params, attrs);
	        } else {
	        	var html = '<div style="padding-top:50px;color:red;font-size:14px;text-align:center;">检测到您的浏览器没有开启或接收Cookie！请尝试开启后，重新进入。</div>'
	        	$('#flashdiv').html(html);
	        }
	        
	        function hideLoading()
	        {
	        	$('#loadingdiv').hide();
	        	if (FIRST_LOGIN) {
	        		sendNormalFeed();
	        	}
	        }
		</script>
	</div>
<div id="fanbox" style=" position:absolute; display:none; top:174px;  background-color:#000000;  filter:alpha(opacity=50);  opacity:0.5;  width:748px; height:600px;">
	</div>
	<div id="addfans"  style="position:absolute; display:none; top:305px; left:180px; width:395px; height:109px;overflow:hidden;  background-image:url('<?php echo $this->_tpl_vars['staticUrl']; ?>
/apps/island/images/fans_bg01.gif');">
  		<a style="float:right; display:block; width:10px; height:10px; margin:2px 5px 0 0;" href="#" onclick="colsefans();"></a>
  		<div style=" clear:both; height:30px; overflow:hidden; width:100px; padding:40px 0 0 260px;">
    		<iframe ALLOWTRANSPARENCY  id='fanbox_view' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' hspace='0' vspace='0' style='height:23px;width:100px;' src='http://www.kaixin001.com/rest/fanbox.php?fkey=100189788&style=2'></iframe>
		</div>
	</div>
</div>
<script type="text/javascript">
	function goInvite()
	{
		location.href = '<?php echo $this->_tpl_vars['hostUrl']; ?>
/invite/top';
	}
	
	function goPay()
	{
		//return;
		location.href = '<?php echo $this->_tpl_vars['hostUrl']; ?>
/pay/top';
	}
	
	function showuser(uid, name, face)
	{
	}
	
	function reloadGame()
	{
		top.location.href = 'http://www.kaixin001.com/!app_happyisland/';
	}

	function returnGift()
	{
		location.href = '<?php echo $this->_tpl_vars['hostUrl']; ?>
/gift/top';
	}
	function addfans()
	{
		document.getElementById('fanbox').style.display='block';
		document.getElementById('addfans').style.display='block';
	}
	function colsefans()
	{
		document.getElementById('fanbox').style.display='none';
		document.getElementById('addfans').style.display='none';
	}
	
	function sendTeamBuyFeed()
    {
		if (FIRST_LOGIN) {
			return;
		}
		var ajaxurl = "<?php echo $this->_tpl_vars['hostUrl']; ?>
/event/sendteambuyfeed";
		$.ajax({
			   type: "post",
			   url: ajaxurl,
			   dataType: "text",
			   success: function(msg){
				   var feed = $.parseJSON(msg);
				   sendFeed(feed);
			   }
			});
    }

	function sendStromFeed()
    {
		if (FIRST_LOGIN) {
			return;
		}
		var ajaxurl = "<?php echo $this->_tpl_vars['hostUrl']; ?>
/event/sendstromfeed";
		$.ajax({
			   type: "post",
			   url: ajaxurl,
			   dataType: "text",
			   success: function(msg){
				   var feed = $.parseJSON(msg);
				   sendFeed(feed);
			   }
			});
    }
</script>

 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.phtml", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>