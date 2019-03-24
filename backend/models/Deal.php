<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "deal".
 *
 * @property string $id
 * @property string $userID
 * @property string $categoryID
 * @property double $lat
 * @property double $lon
 * @property string $title
 * @property string $description
 * @property integer $priceType
 * @property string $priceSale
 * @property string $priceRegular
 * @property integer $offDollar
 * @property integer $offPercent
 * @property string $units
 * @property string $benefit
 * @property string $dateCreated
 * @property string $dateEnding
 * @property string $storeName
 * @property string $storeAddress
 *
 * @property Category $category
 * @property User $user
 */
class Deal extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'deal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userID', 'lat', 'lon', 'dateCreated'], 'required'],
            [['userID', 'categoryID', 'priceType', 'offDollar', 'offPercent','status'], 'integer'],
            [['lat', 'lon', 'priceSale', 'priceRegular'], 'number'],
            [['dateCreated', 'dateEnding','statusDatetime'], 'safe'],
            [['title', 'description', 'units', 'benefit'], 'string', 'max' => 254],
            [['storeName', 'storeAddress','imageList'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('lighttaj', 'ID'),
            'userID' => Yii::t('lighttaj', 'User ID'),
            'categoryID' => Yii::t('lighttaj', 'Category ID'),
            'lat' => Yii::t('lighttaj', 'Lat'),
            'lon' => Yii::t('lighttaj', 'Lon'),
            'title' => Yii::t('lighttaj', 'Title'),
            'description' => Yii::t('lighttaj', 'Description'),
            'priceType' => Yii::t('lighttaj', 'Price Type'),
            'priceSale' => Yii::t('lighttaj', 'Price Sale'),
            'priceRegular' => Yii::t('lighttaj', 'Price Regular'),
            'offDollar' => Yii::t('lighttaj', 'Off Dollar'),
            'offPercent' => Yii::t('lighttaj', 'Off Percent'),
            'units' => Yii::t('lighttaj', 'Units'),
            'benefit' => Yii::t('lighttaj', 'Benefit'),
            'dateCreated' => Yii::t('lighttaj', 'Date Created'),
            'dateEnding' => Yii::t('lighttaj', 'Date Ending'),
            'storeName' => Yii::t('lighttaj', 'Store Name'),
            'storeAddress' => Yii::t('lighttaj', 'Store Address'),
            'imageList'=> Yii::t('lighttaj','imageList'),
            'status'=> Yii::t('lighttaj','Status'),
            'statusDatetime'=> Yii::t('lighttaj','statusDatetime'),


        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'categoryID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userID']);
    }
}
