<?php

class Hapyfish2_Ipanda_Bll_Message
{
    protected static $template = array
        (
            'INVITE' => '{*actor*}在【{*app_link*}】中邀请您去Ta的森林做客，费用全包哦~赶快动身吧！{*app_link2*}',
            'GIFT'   => '{*actor*}送了您一份来自【{*app_link*}】的礼物，赶快打开看看吧！{*app_link2*}',
            'REMIND_1'   => '【{*app_link*}】的{*actor*}提醒您可以去收钱了！{*app_link2*}',
            'REMIND_2'   => '【{*app_link*}】的{*actor*}提醒您可以去接游客了！{*app_link2*}',
            'REMIND_3'   => '{*actor*}提醒您可以去收钱了！{*app_link2*}',
            'REMIND_4'   => '{*actor*}提醒您可以去收钱了！{*app_link2*}',
            'moochPlant'   => '{*actor*}到你的小岛偷了不少钱，避免更多损失，赶快回去收下钱哦！{*app_link2*}'
        );

    public static function send($type, $actor, $target, $data = null)
    {
        if(SEND_MESSAGE && isset(self::$template[$type])) {
            $appUrl = 'http://i.taobao.com/apps/show.htm?appkey='.APP_KEY;

            $st = floor(microtime(true)*1000);

            $rowUser = Hapyfish2_Platform_Bll_UidMap::getUser($actor);
            $actor_info = Hapyfish2_Platform_Bll_User::getUser($rowUser['uid']);

            if ($data) {
                $data['actor'] = $actor_info['name'];
            } else {
                $data = array('actor' => $actor_info['name']);
            }

            if ($type == 'INVITE') {
                $invite_param= 'hf_invite=true&hf_inviter=' . $actor . '&hf_st=' . $st;
                $sg = md5($invite_param . APP_KEY . APP_SECRET);
                $appUrl .= '&' . $invite_param . '&hf_sg=' . $sg;

                Hapyfish2_Ipanda_Bll_InviteLog::addInvite($actor, $target, $st, $sg);
                $app_link2 = '<a href="' . $appUrl . '">加入游戏</a>';
                $data['app_link2'] = $app_link2;
            }

            $app_link = '<a href="' . $appUrl . '">快乐森林</a>';
            $data['app_link'] = $app_link;
            $tpl = self::$template[$type];
            $body = self::buildTemplate($tpl, $data);

            $context = Hapyfish2_Util_Context::getDefaultInstance();
    		$session_key = $context->get('session_key');
            $taobao = Taobao_Rest::getInstance();
            $taobao->setUser($actor, $session_key);

            try {
                $taobao->jianghu_sendmsg($target, $body, 1);
            } catch (Exception $e) {
                err_log($e->getMessage());
            }
        }
    }

    protected static function buildTemplate($tpl, $json_array)
    {
        foreach ($json_array as $k => $v) {
            $keys[] = '{*' . $k . '*}';
            $values[] = $v;
        }

        return str_replace($keys, $values, $tpl);
    }
}