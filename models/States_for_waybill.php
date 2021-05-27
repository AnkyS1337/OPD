<?php

namespace app\models;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class States_for_waybill extends ActiveRecord
{
    public $products;
    public $npp;
    public function rules()
    {
        return
            [
                [['id_waybill','id_shop','id_product','price_for_one','name_shop','address',
                    'type_of_payment','NPP'],'fields','message'=>'Данное поле должно быть заполнено'],
                [['returns','count'],'fields']
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
                'NPP'=>'Очередность в маршруте',
//                'name'=>'Название',
                'name_shop'=>'Название',
                'address'=>'Адрес',
                'payment_method'=>'Способ оплаты',
                'returns'=>'Вовзращено товара',
            ];
    }
    public static function getShop_name_for_pdf($id_shop,$for_pdf)
    {
        if($id_shop==-1){$name="Доп.";}else{
        $shop=Shop::find()->where(['id'=>$id_shop])->one();
        $payment_method_for_check=$shop->payment_method;

        if($for_pdf==1)
        {
            if($payment_method_for_check==0)
            {
                $payment_method='(нал)';
            }
            if($payment_method_for_check==1)
            {
                $payment_method='(без)';
            }
        }


        $address_2=str_replace('Россия,','',$shop->address);
        $address_3=str_replace('Хабаровск,','',$address_2);
        $address_4=str_replace('Хабаровский край,','',$address_3);
        $address_5=str_replace('улица','',$address_4);
        $address_6=str_replace('Хабаровский район,','',$address_5);
        $address_7=str_replace('посёлок городского типа','',$address_6);

        $name=(Entity::find()->where(['id'=>$shop->id_entity])->one())->name." ".$address_7."".$payment_method;
        }
        return $name ;
    }
    public  function getProduct_name()
    {
        return $this->hasOne(Product::className(),['id'=>'id_product']);
    }
  
    public static function getList_shops($model)
    {
        $shops_first=States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$model->id]])->all();
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

    public static function getProducts_for_adding_shop($id_entity)
    {
        $products_entity=ArrayHelper::map(Product::find()->where(['deleted'=>0])->all(),'id','name');
        foreach ($products_entity as $key =>$product_entity)
        {
            if((Products_for_entity::find()->where(['and',['id_entity'=>$id_entity,'id_product'=>$key,'deleted'=>0]])->one())==null)
            {
                unset($products_entity[$key]);
            }
        }
        return $products_entity;
    }
}
