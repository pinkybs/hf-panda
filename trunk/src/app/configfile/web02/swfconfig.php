<?php

$bgMusic = STATIC_HOST . '/swf/bg_music.mp3?v=2011111501';

$modules = array(
	array(  'name' 			=> 'MainInfoMediator',
            'className'		=> 'application.view.ui.MainInfoMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 1
	),
	array(  'name' 			=> 'DIYDownMediator',
            'className'		=> 'application.view.ui.DIYDownMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 2
	),
	array(  'name' 			=> 'FriendsInfoMediator',
            'className'		=> 'application.view.ui.FriendsInfoMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 1
	),
	array(  'name' 			=> 'ShowGrilMediator',
            'className'		=> 'application.view.ui.ShowGrilMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 2
	),
	array(  'name' 			=> 'SysMenuMediator',
            'className'		=> 'application.view.ui.SysMenuMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 2
	),
	array(  'name' 			=> 'MenuMediator',
            'className'		=> 'application.view.ui.MenuMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 2
	),
	array(  'name' 			=> 'MapMediator',
            'className'		=> 'application.view.scene.MapMediator',
            'mediator'		=> 'SceneMediator',
            'level'			=> 1
	),
	array(  'name' 			=> 'DIYMediator',
            'className'		=> 'application.view.scene.DIYMediator',
            'mediator'		=> 'SceneMediator',
            'level'			=> 2
	),
	array(  'name' 			=> 'WorldMediator',
            'className'		=> 'application.view.scene.WorldMediator',
            'mediator'		=> 'SceneMediator',
            'level'			=> 2
	),
	array(  'name' 			=> 'AChooseTimeMediator',
            'className'		=> 'application.view.ui.AChooseTimeMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 2
	),
	array(  'name' 			=> 'AChooseAnimalMediator',
            'className'		=> 'application.view.ui.AChooseAnimalMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 2
	),
	array(  'name' 			=> 'AdminEmployMediator',
            'className'		=> 'application.view.ui.AdminEmployMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 2
	),
	array(  'name' 			=> 'BuildTimeAccountMediator',
            'className'		=> 'application.view.ui.BuildTimeAccountMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 2
	),
	array(  'name' 			=> 'LandExtendMediator',
            'className'		=> 'application.view.ui.LandExtendMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 2
	),
	array(  'name' 			=> 'AnimalListMediator',
            'className'		=> 'application.view.ui.AnimalListMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 2
	),
	array(  'name' 			=> 'BuyItemMediator',
            'className'		=> 'application.view.ui.BuyItemMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 3
	),
	array(  'name' 			=> 'ErrorAlertMediator',
            'className'		=> 'application.view.ui.ErrorAlertMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 99
	),
	array(  'name' 			=> 'BuildingLevelUpMediator',
            'className'		=> 'application.view.ui.BuildingLevelUpMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 2
	),
	array(  'name' 			=> 'RucksackMediator',
            'className'		=> 'application.view.ui.RucksackMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 3
	),
	array(  'name' 			=> 'ShopMediator',
            'className'		=> 'application.view.ui.ShopMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 2
	),
	array(  'name' 			=> 'SuccessMediator',
            'className'		=> 'application.view.ui.SuccessMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 2
	),
	array(  'name' 			=> 'LogViewMediator',
            'className'		=> 'application.view.ui.LogViewMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 2
	),
	array(  'name' 			=> 'TaskMediator',
            'className'		=> 'application.view.ui.TaskMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 9
	),
	array(  'name' 			=> 'AllRecycleMediator',
            'className'		=> 'application.view.ui.AllRecycleMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 2
	),
	array(  'name' 			=> 'DepotMediator',
            'className'		=> 'application.view.ui.DepotMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 2
	),
	array(  'name' 			=> 'UseItemMediator',
            'className'		=> 'application.view.ui.UseItemMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 4
	),
	array(  'name' 			=> 'LevelUpMediator',
            'className'		=> 'application.view.ui.LevelUpMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 99
	),
	array(  'name' 			=> 'ViewLoadingMediator',
            'className'		=> 'application.view.ui.ViewLoadingMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 99
	),
	array(  'name' 			=> 'PandaQuestionMediator',
            'className'		=> 'application.view.ui.PandaQuestionMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 3
	),
	array(  'name' 			=> 'AnimalLevelUpMediator',
            'className'		=> 'application.view.ui.AnimalLevelUpMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 99
	),
	array(  'name' 			=> 'ShowAltersMediator',
            'className'		=> 'application.view.ui.ShowAltersMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 99
	),
	array(  'name' 			=> 'PiaoMsgMediator',
            'className'		=> 'application.view.ui.PiaoMsgMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 99
	),
	array(  'name'			=> 'ExpireMediator',
            'className'		=> 'application.view.ui.ExpireMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 2
    ),
	array(  'name' 			=> 'ActModuleMediator',
            'className'		=> 'application.view.ui.actModule.ActModuleMediator',
            'mediator'		=> 'UiMediator',
            'level'			=> 4
	)
);

$swfResult = array(
	'stageWidth' 		=> 748,
	'stageHeight' 		=> 600,
    'bgMusic'			=> $bgMusic,
	'energy_recover'	=> EG_RECOVERY_TIME,
	'interfaceHost'		=> HOST . '/',
	'modules'			=> $modules
);
