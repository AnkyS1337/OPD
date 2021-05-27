<?php

namespace app\models;


use yii\db\ActiveRecord;

class Information_of_directory extends ActiveRecord
{


    public function rules()
    {
        return
            [
                [['id_directory','id_shop','id_product','price_for_one','count'],'required','message'=>'Данное поле должно быть заполнено'],
                [['price_for_one'],'double','message'=>'Данное поле должно быть заполнено с . (пример 21.123)']
            ];
    }

    public function attributeLabels()
    {
        return
            [
                'id_shop'=>'Название точки продаж',
                'id_product'=>'Название товара',
                'price_for_one'=>'Цена за единицу товара',
                'count'=>'Количество поставляемого продукта',
            ];
    }
    public  function getShop_name()
    {
        $name=(Entity::find()->where(['id'=>(Shop::find()->where(['id'=>$this->id_shop])->one())->id_entity])->one())->name." ".(Shop::find()->where(['id'=>$this->id_shop])->one())->address;
        return $name ;
    }
    public  function getProduct_name()
    {
        return $this->hasOne(Product::className(),['id'=>'id_product']);
    }
    public function getList_shops($model)
    {
        $shops_first=Information_of_directory::find()->where(['and',['deleted'=>0,'id_directory'=>$model->id]])->all();
        foreach ($shops_first as $shop_first)
        {
            $shop=Shop::find()->where(['id'=>$shop_first->id_shop])->one();
            $shop_push[$shop_first->id_shop]=(Entity::find()->where(['id'=>(Shop::find()->where(['id'=>$shop_first->id_shop])->one())->id_entity])->one())->name." ". $shop->address;
        }
//        $shops=ArrayHelper::map(self::find()->where(['deleted'=>0])->all(),'id','id_entity');
//        foreach ($shops as $key=>$id_entity)
//        {
//            $shops[$key]=(Entity::find()->where(['id'=>$id_entity])->one())->name." ". (Shop::find()->where(['id'=>$key])->one())->address;
//        }
        return $shop_push;
    }
}