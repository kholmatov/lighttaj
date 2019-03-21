<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user_deal_like".
 *
 * @property integer $userID
 * @property integer $dealID
 * @property integer $likeVal
 */
class UserDealLike extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_deal_like';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userID', 'dealID'], 'required'],
            [['userID', 'dealID', 'likeVal'], 'integer'],
            [['userID', 'dealID'], 'unique', 'targetAttribute' => ['userID', 'dealID'], 'message' => 'The combination of User ID and Deal ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userID' => Yii::t('lighttaj', 'User ID'),
            'dealID' => Yii::t('lighttaj', 'Deal ID'),
            'likeVal' => Yii::t('lighttaj', 'Like Val'),
        ];
    }
}
