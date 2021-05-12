<?php

namespace app\models;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Entity extends ActiveRecord
{


    public function rules()
    {
        return
            [
                [['name'],'required','message'=>'Данное поле должно быть заполнено'],
                [['name'],'unique','message'=>'Данное поле должно быть уникальным']
            ];
    }

    public function attributeLabels()
    {
        return
            [
                'name'=>'Название юридического лица',
            ];
    }

    public static function getList_entity()
    {
        return  ArrayHelper::map(self::find()->where(['deleted'=>0])->all(),'id','name');
    }

}
