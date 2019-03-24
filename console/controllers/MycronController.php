<?php
/**
 * Created by PhpStorm.
 * User: kholmatov
 * Date: 27/02/16
 * Time: 15:21
 */

namespace console\controllers;
use yii\console\Controller;
use Yii;


class MycronController extends Controller
{
	public function actionIndex ()
	{
		$command = Yii::$app->db->createCommand("SELECT * FROM user LIMIT 1")->queryOne();
	    echo "cron service runnning\n";
	}

	//Set status deal Expired
	public function actionSetstatus(){
	   $update="UPDATE deal SET status = 3, statusDatetime = now()
				WHERE (dateEnding < now() OR (dateEnding IS NULL
				AND dateCreated < NOW() - INTERVAL 45 DAY)) AND status <> 3";
		Yii::$app->db->createCommand($update)->query();
		echo "Update Status\n";
	}

	public function actionTest(){
		echo"I am cron test";
	}

}