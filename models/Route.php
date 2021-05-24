<?php

namespace app\models;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Route extends ActiveRecord
{


    public function rules()
    {
        return
            [
                [['name'],'required','message'=>'Данное поле должно быть заполнено']
            ];
    }

    public function attributeLabels()
    {
        return
            [
                'name'=>'Название маршрута',
            ];
    }
    public static function getList_routes()
    {
        return  ArrayHelper::map(self::find()->all(),'id','name');
    }

}
