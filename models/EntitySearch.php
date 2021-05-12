<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use app\models\Entity;

/**
 * ProductSearch represents the model behind the search form of `app\models\Product`.
 */
class EntitySearch extends Entity
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'deleted'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    public static function tableName()
    {
        return 'entity';
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
        $this->load($params);
        // $name = \mb_strtolower($this->name);
        $name = $this->name;
        $query = \Yii::$app->db->createCommand('SELECT COUNT(*) FROM `entity` WHERE (`deleted`=0) AND name LIKE :name', [':name'=>'%' .$name .'%'])->queryScalar();

        // $sql = 'SELECT COUNT(*) FROM `entity` WHERE (`deleted`=0) AND (`name` LIKE :name1)';

        // $query = Entity::findBySql($sql, ['name1' => $this->name]); 
        //  // $query = Entity::find()->where(['deleted'=>0]);

        // // add conditions that should always apply here
        // if ($this->name == NULL || $this->name == '')
        // {
        //     $sql = 'SELECT * FROM entity WHERE deleted=0';
        // }
        // else
        // {
            $sql = 'SELECT * FROM `entity` WHERE `deleted`=0 AND name LIKE :name';
        // }
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'params' => [':name' => '%'. $name . '%'],
            'totalCount' => $query,
            'pagination' => [ 'pageSize' => 20],
            'sort' => [
                'attributes' => 
                [
                    'name'
                ]
           ]
         ]);
        // \Yii::$app->session['entitysql'] = $dataProvider;

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // // grid filtering conditions
        // $query->andFilterWhere([
        //     'id' => $this->id,
        //     'deleted' => $this->deleted,
        // ]);

        return $dataProvider;
    }
}
