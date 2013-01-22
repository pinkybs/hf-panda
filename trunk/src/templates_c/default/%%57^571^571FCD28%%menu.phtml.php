<?php /* Smarty version 2.6.19, created on 2011-05-16 15:18:26
         compiled from menu.phtml */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'menu.phtml', 32, false),)), $this); ?>
<script src="<?php echo $this->_tpl_vars['staticUrl']; ?>
/js/jquery-1.4.3.min.js" type="text/javascript"></script>
<script src="<?php echo $this->_tpl_vars['staticUrl']; ?>
/apps/island/js/kaixin001.js?v=1.03" type="text/javascript"></script>

<div id="slider">
	<ul>
		<li style="display:inline"><a href='http://www.kaixin001.com/group/topic.php?gid=1059318&tid=35391921
		' target="_blank"><img src="<?php echo $this->_tpl_vars['staticUrl']; ?>
/apps/island/images/slider/newitem-20110309.jpg" style="display:none;"/></a></li>
		<li style="display:inline"><a href='http://www.kaixin001.com/group/topic.php?gid=1059318&tid=35821718' target="_blank"><img src="<?php echo $this->_tpl_vars['staticUrl']; ?>
/apps/island/images/slider/newitem-20110402-2.jpg" style="display:none;"/></a></li>
	<li style="display:inline"><a href='http://www.kaixin001.com/group/topic.php?gid=1059318&tid=36033443&liststart=0' target="_blank"><img src="<?php echo $this->_tpl_vars['staticUrl']; ?>
/apps/island/images/slider/newitem-20110402-1.jpg" style="display:none;"/></a></li>
   </ul>
</div>
<div class="title">
	<div class="menu">
        <ul style="padding-left:170px;width:700px;">
        	<li style="width:76px;"><a href="http://www.kaixin001.com/!app_happyisland/" target="_top"><img src="<?php echo $this->_tpl_vars['staticUrl']; ?>
/apps/island/images/menu/v2/home.gif" title="我的小岛" alt="我的小岛" /></a></li>
        	<li style="width:88px;"><a href="/invite/top"><img src="<?php echo $this->_tpl_vars['staticUrl']; ?>
/apps/island/images/menu/v2/invite.gif" title="邀请好友" alt="邀请好友" /></a></li>
        	<li style="width:76px;"><a href="/gift/top"><img src="<?php echo $this->_tpl_vars['staticUrl']; ?>
/apps/island/images/menu/v2/gift.gif" title="赠送礼物" alt="赠送礼物" /></a></li>
        	<li style="width: 96px; margin-top: -6px;"><a href="/pay/top"><img src="<?php echo $this->_tpl_vars['staticUrl']; ?>
/apps/island/images/menu/v2/pay3.gif?v=20110316" title="兑换宝石" alt="兑换宝石" /></a></li>
        	<li style="width:68px;"><a href="http://www.kaixin001.com/group/group.php?gid=1059318" target="_blank"><img src="<?php echo $this->_tpl_vars['staticUrl']; ?>
/apps/island/images/menu/v2/forum.gif" title="玩家论坛" alt="玩家论坛" /></a></li>
        	<li style="width:68px;"><a href="/help/top"><img src="<?php echo $this->_tpl_vars['staticUrl']; ?>
/apps/island/images/menu/v2/help.gif" title="帮助" alt="帮助" /></a></li>
        </ul>
	</div>
</div>
    <?php if ($this->_tpl_vars['showNotice'] == true): ?>
	  <div class="guang">
    	  <div class="guang_b" style="clear:both;">
    	  <iframe allowTransparency='true'  id='like_view' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' hspace='0' vspace='0' style='height:27px;width:100px;float:right;margin-top:3px;' src='http://www.kaixin001.com/like/like.php?url=http%3A%2F%2Fstatickx01.591oracle.com%2Flike.html&show_faces=false'></iframe>
		<iframe ALLOWTRANSPARENCY  id='fanbox_view' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' hspace='0' vspace='0' style='height:23px;width:110px;float:right;margin-top:3px;' src='http://www.kaixin001.com/rest/fanbox.php?fkey=100189788&style=2'></iframe>
    	  </div>
    	  <div style="clear:both;"></div>
	  <?php $_from = $this->_tpl_vars['mainNotice']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    	  <div class="zleft guang_z"><?php if ($this->_tpl_vars['data']['link']): ?><a href="<?php echo $this->_tpl_vars['data']['link']; ?>
" target="_blank">[<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['create_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
]  <?php echo $this->_tpl_vars['data']['title']; ?>
</a><?php else: ?>[<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['create_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
]  <?php echo $this->_tpl_vars['data']['title']; ?>
<?php endif; ?></div>
    	 <?php endforeach; endif; unset($_from); ?>
</div>
<?php endif; ?>
<script src="<?php echo $this->_tpl_vars['staticUrl']; ?>
/js/jquery.flashSlider-1.0.min.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery("#slider").flashSlider({controlsShow: false,vertical: true, speed: 1500, pause: 6000});
    });
</script>