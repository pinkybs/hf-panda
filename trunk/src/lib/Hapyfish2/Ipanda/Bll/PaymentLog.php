<?php

class Hapyfish2_Ipanda_Bll_PaymentLog
{
	public static function getPayment($uid, $limit = 50)
	{
		try {
			$dalLog = Hapyfish2_Ipanda_Dal_PaymentLog::getDefaultInstance();
			return $dalLog->getPayment($uid, $limit);
		} catch (Exception $e) {

		}

		return null;
	}
}