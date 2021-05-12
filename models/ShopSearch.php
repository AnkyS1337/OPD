<?php

namespace app\models;


use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;

/**
 * ShopSearch represents the model behind the search form of `app\models\Shop`.
 */
class ShopSearch extends Shop
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'payment_method','id_entity'], 'integer'],
            [['name', 'address'], 'safe'],
        ];
    }

    public static function tableName()
    {
        return 'shop';
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
    public function search($params,$params_add=0)
    {
        $this->load($params);
        //$query = Shop::find()->where(['deleted'=>0]);
        $name = $this->name;
        $address = $this->address;
        $id_entity = $this->id_entity;
        $payment_method = $this->payment_method;


        if($params_add!=0)
        {
            $shops=array_unique(States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$params_add]])->select('id_shop')->asArray()->column());
//            $query->andWhere(['not in','id',$shops]);
            $shops_implided=implode(',',$shops);
            $query = \Yii::$app->db->createCommand('SELECT COUNT(*) FROM `shop` WHERE (`deleted`=0) 
AND address LIKE :address AND name LIKE :name 
AND id_entity LIKE :id_entity AND payment_method LIKE :payment_method   ',
                [':address'=>'%' .$address .'%',':name'=>'%' .$name .'%',':id_entity'=>'%' .$id_entity .'%',':payment_method'=>'%' .$payment_method .'%'])->queryScalar();
            $sql = 'SELECT * FROM `shop` WHERE `deleted`=0 AND address LIKE :address AND name LIKE :name 
                AND id_entity LIKE :id_entity AND payment_method LIKE :payment_method AND id NOT IN (' . implode(',', $shops) . ')  ';



        }else{
            $query = \Yii::$app->db->createCommand('SELECT COUNT(*) FROM `shop` WHERE (`deleted`=0) AND address LIKE :address AND name LIKE :name AND id_entity LIKE :id_entity AND payment_method LIKE :payment_method',
                [':address'=>'%' .$address .'%',':name'=>'%' .$name .'%',':id_entity'=>'%' .$id_entity .'%',':payment_method'=>'%' .$payment_method .'%'])->queryScalar();
            $sql = 'SELECT * FROM `shop` WHERE `deleted`=0 AND address LIKE :address AND name LIKE :name AND id_entity LIKE :id_entity AND payment_method LIKE :payment_method  ';
        }

        // add conditions that should always apply here

//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//        ]);
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'params' => [':address' => '%'. $address . '%',':name'=>'%' .$name .'%',':id_entity'=>'%' .$id_entity .'%',':payment_method'=>'%' .$payment_method .'%'],
            'totalCount' => $query,
            'pagination' => [ 'pageSize' => 20],
            'sort' => [
                'attributes' =>
                    [
                        'id_entity',
                        'name',
                        'address',
                        'payment_method'
                    ]
            ]
        ]);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

//        // grid filtering conditions
//        $query->andFilterWhere([
//            'id' => $this->id,
//            'payment_method' => $this->payment_method,
//            'id_entity' => $this->id_entity,
//        ]);
//
//        $query->andFilterWhere(['like', 'name', $this->name])
//            ->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }
}
