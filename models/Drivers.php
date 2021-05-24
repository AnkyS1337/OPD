<?php

namespace app\models;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Drivers extends ActiveRecord
{


    public function rules()
    {
        return
            [
                [['name','phone'],'required','message'=>'Данное поле должно быть заполнено']
            ];
    }

    public function attributeLabels()
    {
        return
            [
                'name'=>'ФИО',
                'phone'=>'Телефон'
            ];
    }

    public static function getList_drivers()
    {
        return  ArrayHelper::map(self::find()->where(['deleted'=>0])->all(),'id','name');
    }
}
