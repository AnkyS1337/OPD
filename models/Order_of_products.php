<?php

namespace app\models;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Order_of_products extends ActiveRecord
{


    public function rules()
    {
        return
            [
                [['id_product','order'],'required','message'=>'Данное поле должно быть заполнено']
            ];
    }

    public function attributeLabels()
    {
        return
            [
                'id_product'=>'Продукт',
                'order'=>'Его порядок при выводе'
            ];
    }

}

