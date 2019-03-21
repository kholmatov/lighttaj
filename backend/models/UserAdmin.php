<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user_admin".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $role
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserAdmin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_admin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'status'], 'required'],
            [['status', 'role', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('lighttaj', 'ID'),
            'username' => Yii::t('lighttaj', 'Username'),
            'auth_key' => Yii::t('lighttaj', 'Auth Key'),
            'password_hash' => Yii::t('lighttaj', 'Password'),
            'password_reset_token' => Yii::t('lighttaj', 'Password Reset Token'),
            'email' => Yii::t('lighttaj', 'Email'),
            'status' => Yii::t('lighttaj', 'Status Active'),
            'role' => Yii::t('lighttaj', 'Role'),
            'created_at' => Yii::t('lighttaj', 'Created At'),
            'updated_at' => Yii::t('lighttaj', 'Updated At'),
        ];
    }


}
