<?php


class Hapyfish2_Ipanda_Event_Dal_Reward
{
    protected static $_instance;

    /**
     * Single Instance
     *
     * @return Hapyfish2_Ipanda_Dal_Donate
     */
    public static function getDefaultInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function getInit()
    {
        $db = Hapyfish2_Db_Factory::getEventDB('db_0');
        $rdb = $db['r'];
        $sql = "SELECT * FROM event_reward ";
        return $rdb->fetchAll($sql);
    }
}