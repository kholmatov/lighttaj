<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Deal;

/**
 * DealSearch represents the model behind the search form about `backend\models\Deal`.
 */
class DealSearch extends Deal
{
    public $user;
    public $category;
    public $searchstring;
    public $mystatus;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'userID', 'categoryID', 'priceType', 'offDollar', 'offPercent','status'], 'integer'],
            [['lat', 'lon', 'priceSale', 'priceRegular'], 'number'],
            [['title', 'description', 'units', 'benefit', 'dateCreated', 'dateEnding', 'storeName', 'storeAddress','user','category','searchstring','statusDatetime','mystatus'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Deal::find();
        $query->joinWith(['user', 'category']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['dateCreated'=>SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['user'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['user.username' => SORT_ASC],
            'desc' => ['user.username' => SORT_DESC],
        ];
        // Lets do the same with country now
        $dataProvider->sort->attributes['category'] = [
            'asc' => ['category.name' => SORT_ASC],
            'desc' => ['category.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        //status(tinyt) - Deal статус
        //0 - active
 	    //1 - flagged
 	    //2 - suspend
 	    //3 - expired
        $query->orFilterWhere(['like', 'deal.title', $this->searchstring])
            ->orFilterWhere(['like', 'deal.description', $this->searchstring])
            ->orFilterWhere(['like', 'user.username', $this->searchstring]);
        $query->andFilterWhere(['not in', 'deal.status', $this->mystatus]);

        return $dataProvider;
    }
}
