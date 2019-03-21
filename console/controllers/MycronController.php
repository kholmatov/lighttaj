<?php
/**
 * Created by PhpStorm.
 * User: kholmatov
 * Date: 27/02/16
 * Time: 15:21
 */

namespace console\controllers;
use yii\console\Controller;
use backend\assets\AssetManager;
use Yii;


class MycronController extends Controller
{
	//Set status deal Expired
	public function actionSetstatus(){
	   $update="UPDATE deal SET status = 3, statusDatetime = now()
				WHERE (dateEnding < now() OR (dateEnding IS NULL
				AND dateCreated < NOW() - INTERVAL 45 DAY)) AND status <> 3";
		Yii::$app->db->createCommand($update)->execute();
		echo "Update Status\n";
	}

	//delete expired deal after 30 days
	public function actionDelete(){

		$query = "SELECT id, imageList FROM deal
					WHERE status = 3 AND statusDatetime < NOW() - INTERVAL 30 DAY";
		$result = Yii::$app->db->createCommand($query)->queryAll();

		if(count($result)){
			$myarray = Array();
			$key_array = Array();
			foreach($result as $rows){
				$myarray[] = $rows['id'];

				if(!empty($rows['imageList'])) {
					$tempImageArray = AssetManager::fetchImageFilesForDealDelete($rows['id'],$rows['imageList']);
					foreach($tempImageArray as $imageItem){
						$key_array[] = Array('Key'=>$imageItem);
					}

				}
			}

			if(count($key_array)>0){

				//delet images from s3;
				$result = AssetManager::deletingMultiple($key_array);
				
			}

			Yii::$app->db->createCommand()->delete('user_deal_favorite', ['dealID' => $myarray])->execute();
			Yii::$app->db->createCommand()->delete('user_deal_like', ['dealID' => $myarray])->execute();
			Yii::$app->db->createCommand()->delete('deal', ['id' => $myarray])->execute();
		}

		echo "Delete Deal!\n";
	}

}