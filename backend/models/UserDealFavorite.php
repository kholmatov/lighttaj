<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user_deal_favorite".
 *
 * @property integer $userID
 * @property integer $dealID
 */
class UserDealFavorite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_deal_favorite';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userID', 'dealID'], 'required'],
            [['userID', 'dealID'], 'integer'],
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
        ];
    }
}
