<?php

namespace app\models;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Products_for_entity extends ActiveRecord
{

    public $price_dot;

    public function rules()
    {
        return
            [
                [['id_entity','id_product'],'required','message'=>'Данное поле должно быть заполнено'],
                [['price','price_dot'],'fields'],

//                [['price'],'double','message'=>'Данное поле должно быть заполнено с . (пример 21.123)'],
            ];
    }


    public function attributeLabels()
    {
        return
            [
                'id_entity'=>'Название маршрута',
                'id_product'=>'Товар',
                'price'=>'Цена'
            ];
    }
    public static function getList_shop_for_entity($id)
    {
        $all_products=ArrayHelper::map(Product::find()->where(['deleted'=>0])->all(),'id','name');
        $entity_have_products=ArrayHelper::map(Products_for_entity::find()->where(['and',['deleted'=>0,'id_entity'=>$id]])->all(),'id','id_product');;
        foreach ($entity_have_products as $entity_have_product)
        {
            if(
                $all_products[$entity_have_product]!=null
            )
            {
                unset($all_products[$entity_have_product]);
            }
        }
        return $all_products;
    }
    public  function getProduct_name()
    {
        return $this->hasOne(Product::className(),['id'=>'id_product']);
    }
}
