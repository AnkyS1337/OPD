<?php

namespace app\models;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Product extends ActiveRecord
{


    public function rules()
    {
        return
            [
                [['name', 'kod'],'required','message'=>'Данное поле должно быть заполнено'],
                [['name', 'kod'],'unique','message'=>'Данное поле должно быть уникальным']
            ];
    }

    public function attributeLabels()
    {
        return
            [
                'name'=>'Название товара',
                'kod' => 'Код товара',
                'id' =>'Номенклатурный номер'
            ];
    }

    public static function getList_product()
    {
        return  ArrayHelper::map(self::find()->where(['deleted'=>0])->all(),'id','name');
    }

    public static function getProduct_name($id)
    {
        if($id==4 )
        {
            $product="Мол 0,9";
        }else{
            if($id==5)
            {
                $product="Мол 0,5";
            }else{
                if($id==9)
                {
                    $product="твор кг.";
                }else{
                    if($id==10)
                    {
                        $product="сме кг.";
                    }else{
                        $product = (self::find()->where(['id'=>$id])->one())->name;
                    }
                }
            }
        }

        return $product;
    }


}
