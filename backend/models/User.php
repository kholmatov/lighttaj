<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property integer $confirmedEmail
 *
 * @property Deal[] $deals
 * @property UserProfile[] $userProfiles
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['confirmedEmail','status','hasPhoto'], 'integer'],
            [['username', 'email', 'password'], 'string', 'max' => 254],
            [['email'], 'unique'],
            [['username'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'confirmedEmail' => 'Confirmed Email',
            'status'=>'Status',
            'hasPhoto'=>'Has Photo'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeals()
    {
        return $this->hasMany(Deal::className(), ['userID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfiles()
    {
        return $this->hasMany(UserProfile::className(), ['userID' => 'id']);
    }

    //в талибцу User добавиль поля status => 0 - Active, 1 - Suspended (user Status: Active or Suspended)
   static public function getUser_count($status)
    {
        if($status=='all') return User::find()->count();

        return User::find()->where(['status' => $status])->count();
    }

}
