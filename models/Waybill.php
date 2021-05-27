<?php

namespace app\models;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Waybill extends ActiveRecord
{


    public function rules()
    {
        return
            [
                [['id_driver','id_route','date'],'required','message'=>'Данное поле должно быть заполнено'],
//                ['id_route','IfRoute_not_good_for_directory'],
            ];
    }

    public function attributeLabels()
    {
        return
            [
                'id_driver'=>'Водитель',
                'id_route'=>'Номер маршрута',
                'date'=>'Дата',
            ];
    }
    public  function getRoute_name()
    {
        return $this->hasOne(Route::className(),['id'=>'id_route']);
    }

    public  function getDrivers_name()
    {
        return $this->hasOne(Drivers::className(),['id'=>'id_driver']);
    }
//    public function errorIfRoute_not_good_for_directory()
//    {
//        if($this->hasErrors())
//            return;
//        $this->id_route;
//        $this->id_directory;
//        $shops_in_directory=array_unique(Information_of_directory::find()->where(['and',['deleted'=>0,'id_directory'=>$this->id_directory]])->select('id_shop')->column());
//        $shops_in_rout=Order_of_Route::find()->where(['and',['deleted'=>0,'id_route'=>$this->id_route]])->select('id_shop')->column();
//        if(count($shops_in_directory)<>count($shops_in_rout))
//            $this->addError('id_route','');
//    }
}
