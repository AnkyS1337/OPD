<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Waybill;

/**
 * WaybillSearch represents the model behind the search form of `app\models\Waybill`.
 */
class WaybillSearch extends Waybill
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_driver', 'id_route', 'deleted'], 'integer'],
            [['date'], 'safe'],
        ];
    }

    public static function tableName()
    {
        return 'waybill'; // TODO: Change the autogenerated stub
    }

    /**
     * {@inheritdoc}
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
        $query = Waybill::find()->where(['deleted'=>0]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['date'=> SORT_DESC]),
        ]);
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_driver' => $this->id_driver,
            'id_route' => $this->id_route,
            'date' => $this->date,
            'deleted' => $this->deleted,
        ]);

        return $dataProvider;
    }
}
