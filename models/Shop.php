<?php

namespace app\models;


use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Shop extends ActiveRecord
{


    public function rules()
    {
        return
            [
              [['address','payment_method','coord'],'required','message'=>'Данное поле должно быть заполнено'],
                [['id_entity','name'],'fields']
            ];
    }

    public function attributeLabels()
 {
        return
        [
            'id_entity'=>'Юридическое лицо',
            'name'=>'Название',
            'address'=>'Адрес',
            'payment_method'=>'Способ оплаты',
        ];
 }

    public static function getType_of_payment_method()
    {
        return
            ['Наличный','Безналичный'];
    }
    public static function getName_of_payment_method($id_pay)
    {
        $list=self::getType_of_payment_method();
        return $list[$id_pay];
    }
    public static function getList_shops()
    {
        $shops=ArrayHelper::map(self::find()->where(['deleted'=>0])->all(),'id','id_entity');
        foreach ($shops as $key=>$id_entity)
        {
            $shops[$key]=(Entity::find()->where(['id'=>$id_entity])->one())->name." ". (Shop::find()->where(['id'=>$key])->one())->address." ".(Shop::find()->where(['id'=>$key])->one())->name;
        }
        return $shops;
    }
    public static  function getEntity_name($id)
    {
        return (Entity::find()->where(['id'=>$id])->one())->name;
    }
}