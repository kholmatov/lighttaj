<?php
/**
 * Created by PhpStorm.
 * User: kholmatov
 * Date: 05/03/16
 * Time: 22:41
 */

namespace backend\models;
use Yii;
use yii\base\Model;
use backend\models\UserAdmin;

class PasswordForm extends Model{
	    public $oldpass;
	    public $newpass;
	    public $repeatnewpass;

	    public function rules(){
		    return [
			    [['oldpass','newpass','repeatnewpass'],'required'],
			    ['oldpass','findPasswords'],
			    ['repeatnewpass','compare','compareAttribute'=>'newpass'],
		    ];
	    }

	    public function findPasswords($attribute, $params){
		    $user = UserAdmin::find()->where([
			    'username'=>Yii::$app->user->identity->username
		    ])->one();
		    $password = $user->password_hash;
			if(!$this->validatePassword($password))//($password!=$this->oldpass)
			    $this->addError($attribute,'Old password is incorrect');
	    }

	    public function attributeLabels(){
		    return [
			    'oldpass'=>'Old Password',
			    'newpass'=>'New Password',
			    'repeatnewpass'=>'Repeat New Password',
		    ];
	    }

		public function validatePassword($password)
		{
			if(is_null($this->oldpass))
				return false;
			return Yii::$app->security->validatePassword($this->oldpass,$password);
		}
    }