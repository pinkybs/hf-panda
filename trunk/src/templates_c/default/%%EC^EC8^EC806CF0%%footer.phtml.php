<?php /* Smarty version 2.6.19, created on 2011-05-16 15:18:26
         compiled from footer.phtml */ ?>
<iframe width="760" height="100" frameborder="0" scrolling="no" src="<?php echo $this->_tpl_vars['staticUrl']; ?>
/html/banner/index.html?v=2011021801"></iframe>

<div style="width:750px;align:center;text-align:center;font-weight:bold;">
<div>【快乐岛主】应用由上海乐鱼数码提供，若您在游戏中遇到问题，请<a href="http://www.kaixin001.com/group/topic.php?gid=1059318&tid=34866844" target="_blank">点击这里</a>，或者联系乐乐客服：<a href="http://wpa.qq.com/msgrd?V=1&Uin=1471558464&Site=快乐岛主&Menu=yes" target="_blank"><img border="0" style="top:6px;position:relative;" alt="1471558464" title="1471558464" src="http://wpa.qq.com/pa?p=1:1471558464:1"></a>
</div>
<div style="color:#3b5998;padding-top:2px;padding-bottom:12px;"><a href="javascript:addFavor();"><img src="<?php echo $this->_tpl_vars['staticUrl']; ?>
/apps/island/images/common/favorite.png" style="top:6px;position:relative;padding-right:8px;" /></a>小岛门牌号: <?php echo $this->_tpl_vars['uid']; ?>
<span id="userinfo" style="color:red;"></span></div>
<div>

<script type="text/javascript">
    var fconfig = {
        appUrl:"http://www.kaixin001.com/!app_happyisland/",
        appId:"<?php echo $this->_tpl_vars['appId']; ?>
",
        appKey:"<?php echo $this->_tpl_vars['appKey']; ?>
",
        userId:"<?php echo $this->_tpl_vars['uid']; ?>
",
		platformUid:"<?php echo $this->_tpl_vars['platformUid']; ?>
",
		staticUrl:"<?php echo $this->_tpl_vars['staticUrl']; ?>
",
        streamUrl:"<?php echo $this->_tpl_vars['hostUrl']; ?>
/api/sendnewsfeed",
        shareStreamUrl:"",
        systemNewsUrl:"",
        invitationUrl:"<?php echo $this->_tpl_vars['hostUrl']; ?>
/api/sendinvitation",
        freegiftLink:"",
        gameMode:"opaque"
    };

    Hapyfish.Kaixin001.init({"jsConfig":{"publishStream_erro":"\u5bf9\u4e0d\u8d77\uff0c\u8bf7\u6c42\u670d\u52a1\u5668\u9519\u8bef\uff0c\u8bf7\u7a0d\u5019\u518d\u8bd5\uff01"}},fconfig);

	Hapyfish.Kaixin001.adjustHeight();

	function sendFeed(feed)
	{
		if (FIRST_LOGIN) {
			return;
		}
		var opt = $.parseJSON(feed);
		Hapyfish.Kaixin001.publishStream(opt);
	}

	function sendNormalFeed()
	{
		if (FIRST_LOGIN) {
			return;
		}
		var opt = {
			"templateId":0,
			"isShare":false,
			"attachment":{
				"description":"阳光？沙滩？美女？帅哥！尽在快乐岛主！赶快加入吧~",
				"media": [
					{"src":"<?php echo $this->_tpl_vars['staticUrl']; ?>
/apps/island/images/feed/join.gif"}
				]
			},
			action:[
				{"text":"Let's Go"}
			]
		};

		Hapyfish.Kaixin001.publishStream(opt);
	}

	function sendUserLevelUpFeed(flag)
	{
		if (FIRST_LOGIN) {
			return;
		}
		if (flag == 1) {
			var opt = {
				"templateId":2,
				"isShare":false,
				"attachment":{
					"description":"{_USER_}的小岛在他的努力下又变的更大啦！你们羡慕么~那一起来玩吧！",
					"media": [
						{"src":"<?php echo $this->_tpl_vars['staticUrl']; ?>
/apps/island/images/feed/island_level_up.gif"}
					]
				},
				action:[
					{"text":"Let's Go"}
				]
			};
		} else {
			var opt = {
				"templateId":1,
				"isShare":false,
				"attachment":{
					"description":"{_USER_}的小岛升级了！去游览还能拿到免费礼物哦！快去看看吧！",
					"media": [
						{"src":"<?php echo $this->_tpl_vars['staticUrl']; ?>
/apps/island/images/feed/user_level_up.gif"}
					]
				},
				action:[
					{"text":"Let's Go"}
				]
			};
		}

		Hapyfish.Kaixin001.publishStream(opt);
	}

	function sendDailyTaskFeed()
	{
		if (FIRST_LOGIN) {
			return;
		}
		var opt = {
			"templateId":8,
			"isShare":false,
			"attachment":{
				"description":"{_USER_}通过一天的努力，所有日常任务都完成了哦！鼓掌~",
				"media": [
					{"src":"<?php echo $this->_tpl_vars['staticUrl']; ?>
/apps/island/images/feed/daily_mission_complete.gif"}
				]
			},
			action:[
				{"text":"赞"}
			]
		};

		Hapyfish.Kaixin001.publishStream(opt);
	}

	function testInvitation()
	{
		var opt = {};
		Hapyfish.Kaixin001.sendInvitation(opt);
	}

	function addFavor()
	{
		var url = "http://www.kaixin001.com/!app_happyisland/";
		var title = "快乐岛主-开心网";
		if (document.all) {
			window.external.AddFavorite( url, title);
		} else if (window.sidebar) {
			window.sidebar.addPanel(title, url,"");
		} else if (window.opera && window.print) {
			var mbm = document.createElement('a');
			mbm.setAttribute('rel','sidebar');
			mbm.setAttribute('href',url);
			mbm.setAttribute('title',title);
			mbm.click();
		} else {
			alert("浏览器不支持直接加入收藏夹，请手动添加。");
		}
	}
</script>
</div>
</div>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-21547494-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>