<?php

namespace app\controllers;

use app\models\Drivers;
use app\models\Entity;
use app\models\Information_of_directory;
use app\models\Information_of_directorySearch;
use app\models\Order_of_products;
use app\models\Order_of_Route;
use app\models\Product;
use app\models\Products_for_entity;
use app\models\Route;
use app\models\Shop;
use app\models\ShopSearch;
use app\models\States_for_waybill;
use app\models\States_for_waybillSearch;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Yii;
use app\models\Waybill;
use app\models\WaybillSearch;
use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\YiiAsset;
use yii\data\ArrayDataProvider;

/**
 * WaybillController implements the CRUD actions for Waybill model.
 */
class    WaybillController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
//                    'pdf' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Waybill models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WaybillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Waybill model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    public static function transferWaybillState($waybillState)
    {
        $result = [];
        foreach($waybillState as $waybill)
        {
            $idstr = 'id';
            $idshop = 'id_shop';
            $waybill = $waybill->getAttributes();

            foreach($waybill as $attr => $value)
            {

                $result[$waybill[$idshop]][$waybill[$idstr]][$attr]= $value;
            }
        }
        return $result;
    }


    public function actionView($id)
    {
        $searchModel= new States_for_waybillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['id_waybill'=>$id]);
        $models = static::transferWaybillState($dataProvider->getModels());
        $dataprov = new ArrayDataProvider([
            'allModels' => $models,
            'sort' => [
                'attributes' => ['id'],
            ],
            'pagination' => [
                'pageSize' => 0,
          ],
        ]);
        // \Yii::$app->session['os'] = $models;
        return $this->render('view',[
            'model' => $this->findModel($id),
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataprov
        ]);
    }

    public function actionMap()
    {
        
    }

    /**
     * Creates a new Waybill model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Waybill();
        $NPP=0;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();

            $all_shops=Order_of_Route::find()->where(['and',['deleted'=>0,'id_route'=>$model->id_route]])->all();
            foreach ($all_shops as $all_shop)
            {

                $shop=Shop::find()->where(['and',['deleted'=>0,'id'=>$all_shop->id_shop]])->one();
             $products=Products_for_entity::find()->where(['and',['deleted'=>0,'id_entity'=>$shop->id_entity]])->all();

             foreach ($products as $key=>$product)
             {
                 $model_state_for_waybill= new States_for_waybill();

                $model_state_for_waybill->id_waybill=$model->id;
                $model_state_for_waybill->id_shop=$all_shop->id_shop;
                $model_state_for_waybill->id_product=$product->id_product;
                $model_state_for_waybill->price_for_one=$product->price;
//                $model_state_for_waybill->count=0;
                $model_state_for_waybill->NPP=$all_shop->NPP;
                $model_state_for_waybill->name_shop=(Entity::find()->where(['id'=>$shop->id_entity])->one())->name  ;
                $model_state_for_waybill->address=$shop->address;
                $model_state_for_waybill->type_of_payment=$shop->payment_method;
                if($NPP<$all_shop->NPP)
                {
                    $NPP=($all_shop->NPP);
                }
                if($model_state_for_waybill->validate())
                {
                    $model_state_for_waybill->save();
               }
             }
            }
            $NPP++;
            $all_products=Product::find()->where(['deleted'=>0])->select('id')->asArray()->column();
            foreach ($all_products as $all_product)
            {
                $additional= new States_for_waybill();
                $additional->id_waybill=$model->id;
                $additional->id_shop=-1;
                $additional->id_product=$all_product;
                $additional->NPP=$NPP;
                $additional->name_shop="Дополнительные";
                $additional->save();
            }
            return $this->redirect(['view',
                'id' => $model->id,

]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Waybill model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
//    public function actionUpdate($id)
//    {
//        $model = $this->findModel($id);
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        }
//
//        return $this->render('update', [
//            'model' => $model,
//        ]);
//    }

    /**
     * Deletes an existing Waybill model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        $model->deleted=1;
        $model->save();
    }
    public function actionCandelete($id)
    {
//        $roles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId());
//        if (array_key_exists('viewer', $roles))
//        {
//            $can=false;
//        }else
//        {
//            if((States_for_waybill::find()->where(['and',['id_waybill'=>$id,'deleted'=>0]])->all()) == null)
//            {
//                $can=true;
//            }
//            else{
//                $can=false;
//            }
        //}
        return $can=true;
    }

    /**
     * Finds the Waybill model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Waybill the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Waybill::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    protected function findModel_information_of_directory($id)
    {
        if (($model = Information_of_directory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
//    public function actionChange_information_of_directory($id,$id_directory)
//    {
//        Yii::$app->session['333']=$models=Information_of_directory::find()->where(['and',['id_directory'=>$id_directory,'deleted'=>0]])->all();
////        $counter=0;
////        foreach ($all_information_of_directory as $information_of_directory)
////        {
////            $models[$counter]=$this->findModel_information_of_directory($information_of_directory);
////        }
////        Yii::$app->session['123']=$models;
//            return $this->render('change_information_of_directory',['models'=>$models]);
//    }

//    public function actionView_one($id,$id_waybill)
//    {
//
//        $model = new States_for_waybill();
//        $id_entity=(Shop::find()->where(['id'=>$id])->one())->id_entity;
//        $products_id=ArrayHelper::map(Products_for_entity::find()->where(['and',['id'=>$id_entity,'deleted'=>0]])->all(),'id','id_product');
//        $products_push=[];
//        foreach ($products_id as $product_id)
//        {
//            $products_push[$product_id]=(Product::find()->where(['and',['id'=>$product_id,'deleted'=>0]])->all())->name;
//        }
//        if($model->load())
//        {
//
//        }
//        return $this->render('view_one', [
//            'model'=>$model,
//            'products_push'=>$products_push,
//        ]);
//    }


    public function actionFor_production($id,$id_waybill)
    {

        $model = new \yii\base\DynamicModel([
            'count_for_production_add', 'product_for_production',
        ]);
        $model->addRule(['count_for_production_add','product_for_production'], 'fields');
        $id_entity=(Shop::find()->where(['id'=>$id])->one())->id_entity;
        $products_id=ArrayHelper::map(Products_for_entity::find()->where(['and',['id_entity'=>$id_entity,'deleted'=>0]])->all(),'id','id_product');
        $products_push=[];
        foreach ($products_id as $product_id)
        {
            $products_push[$product_id]=(Product::find()->where(['and',['id'=>$product_id,'deleted'=>0]])->one())->name;
        }
        if($model->load(Yii::$app->request->post()))
        {
            $model_for_save=States_for_waybill::find()->where(['and',['deleted'=>0,'id_product'=>$model->product_for_production,'id_waybill'=>$id_waybill,'id_shop'=>$id]])->one();
            $model_for_save->count_for_production=$model->count_for_production_add;
            $model_for_save->save();
            return $this->redirect(['view',
                'id' => $id_waybill,]);
        }
        return $this->render('for_production', [
            'model'=>$model,
            'products_push'=>$products_push,
            'id_waybill'=>$id_waybill
        ]);
    }

    /**
     * Creates a new States_for_waybill model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */


    /**
     * Updates an existing States_for_waybill model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate_one($id)
    {
        $model = $this->findModel_one($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_waybill]);
        }

        return $this->render('update_one', [
            'model' => $model,
        ]);
    }
    public function actionUpdate_count()
    {
        $post=Yii::$app->request->post();
        $model= $this->findModel_one($post['id']);
        $string_count=strtr((string)$post['count'],",",".");
        $model->count= $string_count;
        $model->save();
    }
    public function actionUpdate_returns()
    {
        $post=Yii::$app->request->post();
        $model= $this->findModel_one($post['id']);
        $string_returns=strtr((string)$post['returns'],",",".");
        $model->returns=$string_returns;
        $model->save();
    }

    /**
     * Deletes an existing States_for_waybill model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete_one($id)
    {
        $model=$this->findModel_one($id);
        $model->deleted=1;
        $model->save();
    }
    public function actionCandelete_one($id)
    {
//        $roles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId());
//        if (array_key_exists('viewer', $roles))
//        {
//            $can=false;
//        }else
//        {
//        if((States_for_waybill::find()->where(['and',['id_waybill'=>$id,'deleted'=>0]])->all()) == null)
//        {
//            $can=true;
//        }
//        else{
//            $can=false;
//        }
        //}
        return $can=true;
    }

    /**
     * Finds the States_for_waybill model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return States_for_waybill the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel_one($id)
    {
        if (($model = States_for_waybill::findOne($id)) !== null) {
            return $model;
        }
    }
    public function actionView_routes()
    {
        $waybills=Waybill::find()->where(['deleted'=>0])->all();
        $waybills_for_push=[];
        usort($waybills, array($this, "compare"));
        $drivers=Drivers::find()->where(['deleted'=>0])->all();
        foreach ($drivers as $driver)
        {
            $drivers_new[$driver->id]['phone']=$driver->phone;
            $drivers_new[$driver->id]['name']=$driver->name;
        }
        $routes=ArrayHelper::map(Route::find()->all(),'id','name');
        foreach ($waybills as $waybill)
        {
            $waybills_for_push[$waybill->id]['phone']=$drivers_new[$waybill->id_driver]['phone'];
            $waybills_for_push[$waybill->id]['driver']=$drivers_new[$waybill->id_driver]['name'];
            $waybills_for_push[$waybill->id]['name']=$routes[$waybill->id_route];
            $waybills_for_push[$waybill->id]['date']=strftime("%d-%m-%Y",strtotime($waybill->date));
        }
        return $this->render('view_routes',['waybills_for_push'=>$waybills_for_push]);
    }



    public function actionView_add($id_waybill)
    {
        $searchModel = new ShopSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id_waybill);
        return $this->render('view_add', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id_waybill'=>$id_waybill,
        ]);
    }



    public function actionCreate_one($id_shop,$id_waybill)
    {
        $max=States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$id_waybill]])->max('NPP');
            for ($i=1;$i<$max+1;$i++)
            {
                $drop_down_count[$i]=$i;
            }

        $model = new \yii\base\DynamicModel([
            'products', 'NPP',
        ]);
        $model->addRule(['products','NPP'], 'required');
        $shop=Shop::find()->where(['id'=>$id_shop])->one();
        $entity=Entity::find()->where(['id'=>$shop->id_entity])->one();


        if ($model->load(Yii::$app->request->post()) ) {
            $models_change=States_for_waybill::find()->where(['and',['id_waybill'=>$id_waybill],
                ['>=','NPP',$model->NPP]
            ])->all();
            foreach ($models_change as $model_change)
            {
                $model_change->NPP++;
                $model_change->save();
            }
            $products=$model->products;
            foreach ($products as $product)
            {
                $model_push = new States_for_waybill();
                 $model_push->id_waybill=$id_waybill;
                 $model_push->id_shop=$id_shop;
                $model_push->NPP=$model->NPP;
                $model_push->id_product=$product;
                 $model_push->address=$shop->address;
                 $model_push->name_shop=$entity->name;
                 $model_push->type_of_payment=$shop->payment_method;

                 $model_push->price_for_one=Products_for_entity::find()->where(['and',
                     ['deleted'=>0,'id_entity'=>$shop->id_entity,'id_product'=>$product]])->one()->price;
                $model_push->save();
            }


            return $this->redirect(['view_add', 'id_waybill' => $id_waybill]);
        }

        return $this->render('create_one', [
            'model' => $model,
            'id_waybill'=>$id_waybill,
            'id_entity'=>$shop->id_entity,
            'drop_down_count'=>$drop_down_count,
        ]);
    }




    public function actionPdf($id,$phone,$driver,$date)
    {
        $waybill_for_push=States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$id]])->all();
        foreach ($waybill_for_push as $waybills_buy_shop)
        {
            if($waybills_buy_shop_push[$waybills_buy_shop->NPP]['shop'][$waybills_buy_shop->id_shop]==null)
            {
                $waybills_buy_shop_push[$waybills_buy_shop->NPP]['shop']=$waybills_buy_shop->id_shop;
            }
            if(
            ($waybills_buy_shop_push[$waybills_buy_shop->NPP]['shop'])!=null
            )
            {
                if($waybills_buy_shop->id_product==2)
                {
                    $waybills_buy_shop_push[$waybills_buy_shop->NPP]['Творог']=+$waybills_buy_shop->count;
                    $waybills_buy_shop_push[$waybills_buy_shop->NPP]['Творог_в']=+$waybills_buy_shop->returns;
                }
                if($waybills_buy_shop->id_product==3)
                {
                    $waybills_buy_shop_push[$waybills_buy_shop->NPP]['Сметана']=+$waybills_buy_shop->count;
                    $waybills_buy_shop_push[$waybills_buy_shop->NPP]['Сметана_в']=+$waybills_buy_shop->returns;
                }
                if($waybills_buy_shop->id_product==4)
                {
                    $waybills_buy_shop_push[$waybills_buy_shop->NPP]['молоко 0,9']=+$waybills_buy_shop->count;
                    $waybills_buy_shop_push[$waybills_buy_shop->NPP]['молоко 0,9_в']=+$waybills_buy_shop->returns;
                }
                if($waybills_buy_shop->id_product==5)
                {
                    $waybills_buy_shop_push[$waybills_buy_shop->NPP]['молоко 0,5']=+$waybills_buy_shop->count;
                    $waybills_buy_shop_push[$waybills_buy_shop->NPP]['молоко 0,5_в']=+$waybills_buy_shop->returns;
                }
                if($waybills_buy_shop->id_product==6)
                {
                    $waybills_buy_shop_push[$waybills_buy_shop->NPP]['варенец']=+$waybills_buy_shop->count;
                    $waybills_buy_shop_push[$waybills_buy_shop->NPP]['варенец_в']=+$waybills_buy_shop->returns;
                }
                if($waybills_buy_shop->id_product==7)
                {
                    $waybills_buy_shop_push[$waybills_buy_shop->NPP]['ряженка']=+$waybills_buy_shop->count;
                    $waybills_buy_shop_push[$waybills_buy_shop->NPP]['ряженка_в']=+$waybills_buy_shop->returns;
                }
                if($waybills_buy_shop->id_product==8)
                {
                    $waybills_buy_shop_push[$waybills_buy_shop->NPP]['кефир']=+$waybills_buy_shop->count;
                    $waybills_buy_shop_push[$waybills_buy_shop->NPP]['кефир_в']=+$waybills_buy_shop->returns;
                }
                if($waybills_buy_shop->id_product==9)
                {
                $waybills_buy_shop_push[$waybills_buy_shop->NPP]['Творог кг.']=+$waybills_buy_shop->count;
                    $waybills_buy_shop_push[$waybills_buy_shop->NPP]['Творог кг._в']=+$waybills_buy_shop->returns;
                }
                if($waybills_buy_shop->id_product==10)
                {
                    $waybills_buy_shop_push[$waybills_buy_shop->NPP]['Сметана кг.']=+$waybills_buy_shop->count;
                    $waybills_buy_shop_push[$waybills_buy_shop->NPP]['Сметана кг._в']=+$waybills_buy_shop->returns;
                }
            }
        }
        ksort($waybills_buy_shop_push);
        //Общий подсчет всей продукции
        $waybill_all_for_push=[];
        $waybill_all_for_push['Творог']=array_sum(States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$id,'id_product'=>2]])->select('count')->asArray()->column());
        $waybill_all_for_push['Сметана']=array_sum(States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$id,'id_product'=>3]])->select('count')->asArray()->column());
        $waybill_all_for_push['молоко 0,9']=array_sum(States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$id,'id_product'=>4]])->select('count')->asArray()->column());
        $waybill_all_for_push['молоко 0,5']=array_sum(States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$id,'id_product'=>5]])->select('count')->asArray()->column());
        $waybill_all_for_push['варенец']=array_sum(States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$id,'id_product'=>6]])->select('count')->asArray()->column());
        $waybill_all_for_push['ряженка']=array_sum(States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$id,'id_product'=>7]])->select('count')->asArray()->column());
        $waybill_all_for_push['кефир']=array_sum(States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$id,'id_product'=>8]])->select('count')->asArray()->column());
        $waybill_all_for_push['Творог кг.']=array_sum(States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$id,'id_product'=>9]])->select('count')->asArray()->column());
        $waybill_all_for_push['Сметана кг.']=array_sum(States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$id,'id_product'=>10]])->select('count')->asArray()->column());

        $waybill_all_for_push['Творог_в']=array_sum(States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$id,'id_product'=>2]])->select('returns')->asArray()->column());
        $waybill_all_for_push['Сметана_в']=array_sum(States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$id,'id_product'=>3]])->select('returns')->asArray()->column());
        $waybill_all_for_push['молоко 0,9_в']=array_sum(States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$id,'id_product'=>4]])->select('returns')->asArray()->column());
        $waybill_all_for_push['молоко 0,5_в']=array_sum(States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$id,'id_product'=>5]])->select('returns')->asArray()->column());
        $waybill_all_for_push['варенец_в']=array_sum(States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$id,'id_product'=>6]])->select('returns')->asArray()->column());
        $waybill_all_for_push['ряженка_в']=array_sum(States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$id,'id_product'=>7]])->select('returns')->asArray()->column());
        $waybill_all_for_push['кефир_в']=array_sum(States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$id,'id_product'=>8]])->select('returns')->asArray()->column());
        $waybill_all_for_push['Творог кг._в']=array_sum(States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$id,'id_product'=>9]])->select('returns')->asArray()->column());
        $waybill_all_for_push['Сметана кг._в']=array_sum(States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$id,'id_product'=>10]])->select('returns')->asArray()->column());
//        $this->render('pdf', [
//            'date'=>$date,'driver'=>$driver,
//            'phone'=>$phone,
//            'waybills_buy_shop_push'=>$waybills_buy_shop_push,
//            'waybill_all_for_push'=>$waybill_all_for_push,
//        ]);
        $mpdf= new Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        ob_start();
        require '../views/waybill/pdf.php';
        $html = ob_get_clean();
        $mpdf->WriteHTML($html);
        $mpdf->Output();
        exit();
    }
    public static function compare($a,$b)
    {
        if ($a->date == $b->date)
        {
            return 0;
        }
        return ($a->date > $b->date) ? -1 : +1;
    }

    public function actionReport($id,$name)
    {
        $all_waybills=Waybill::find()->where(['and',['deleted'=>0,'id_route'=>$id]])->all();
        usort($all_waybills, array($this, "compare"));
        $new_waybills=[];
        foreach ($all_waybills as $key=>$waybill)
        {
            $new_waybills[$key]['date']=$waybill->date;
            $all_states=States_for_waybill::find()->where(['and',['id_waybill'=>$waybill->id,'deleted'=>0]])->orderBy([
                'NPP'=>SORT_ASC,
            ])->all();
            foreach ($all_states as $all_state)
            {
                $id_entity=(Shop::find()->where(['id'=>$all_state->id_shop])->one())->id_entity;
                $price_for_products=ArrayHelper::map(Products_for_entity::find()->where(['and',['deleted'=>0,'id_entity'=>$id_entity]])->all(),'id_product','price');
                if($new_waybills[$key]['shops'][$all_state->id_shop]==null)
                {
                    $new_waybills[$key]['shops'][$all_state->id_shop];
                    $new_waybills[$key]['shops'][$all_state->id_shop]['name']=States_for_waybill::getShop_name_for_pdf($all_state->id_shop,0);
                    $new_waybills[$key]['shops'][$all_state->id_shop]['NPP']=$all_state->NPP;
                }
                $new_waybills[$key]['shops'][$all_state->id_shop]['products'][$all_state->id_product]['count']+=$all_state->count;
                $new_waybills[$key]['shops'][$all_state->id_shop]['products'][$all_state->id_product]['returns']+=$all_state->returns;
                $new_waybills[$key]['shops'][$all_state->id_shop]['products'][$all_state->id_product]['count_money']+=$all_state->count*$price_for_products[$all_state->id_product];
                $new_waybills[$key]['shops'][$all_state->id_shop]['products'][$all_state->id_product]['returns_money']+=$all_state->returns*$price_for_products[$all_state->id_product];


                $new_waybills[$key]['shops'][$all_state->id_shop]['count_all']+=$all_state->count;
                $new_waybills[$key]['shops'][$all_state->id_shop]['returns_all']+=$all_state->returns;
                $new_waybills[$key]['shops'][$all_state->id_shop]['count_all_money']+=$all_state->count*$price_for_products[$all_state->id_product];
                $new_waybills[$key]['shops'][$all_state->id_shop]['returns_all_money']+=$all_state->returns*$price_for_products[$all_state->id_product];



                if($new_waybills[$key]['shops'][$all_state->id_shop]['returns_all']!=0 && $new_waybills[$key]['shops'][$all_state->id_shop]['count_all']!=0)
                {
                    $new_waybills[$key]['shops'][$all_state->id_shop]['percent']=
                    round($new_waybills[$key]['shops'][$all_state->id_shop]['returns_all']
                        /
                        $new_waybills[$key]['shops'][$all_state->id_shop]['count_all'],3)*100;

                    $new_waybills[$key]['shops'][$all_state->id_shop]['percent_money']=
                        round($new_waybills[$key]['shops'][$all_state->id_shop]['returns_all_money']
                            /
                            $new_waybills[$key]['shops'][$all_state->id_shop]['count_all_money'],3)*100;

                }else {
                    if ($new_waybills[$key]['shops'][$all_state->id_shop]['returns_all'] == 0) {
                        $new_waybills[$key]['shops'][$all_state->id_shop]['percent'] = 0;
                        $new_waybills[$key]['shops'][$all_state->id_shop]['percent_money'] = 0;
                    }
                    if ($new_waybills[$key]['shops'][$all_state->id_shop]['count_all'] == 0) {
                        $new_waybills[$key]['shops'][$all_state->id_shop]['percent'] = "-";
                        $new_waybills[$key]['shops'][$all_state->id_shop]['percent_money'] = "-";
                    }
                }
            }
        }
        $order=ArrayHelper::map(Order_of_products::find()->all(),'order','id_product');
        ksort($order);
        foreach ($order as $one)
        {
            $order_push[$one]['count']=0;
            $order_push[$one]['returns']=0;
        }

        return $this->render('report', [
        'new_waybills'=>$new_waybills,
            'order_push'=>$order_push,
            'name'=>$name

        ]);
    }
//    public function actionReport_full1()// КОД ПАШИ { Музейный экспонат(друг только не расстраивайся)
//                                        // мне нужно было с ним работать, да и мой код лучше работает (смирись)
//    {
//        $model = new DynamicModel(['date_range']);
//        $model->addRule(['date_range'], 'string', ['max' => 128]);
//        Yii::$app->session['$model']=$model->date_range;
//
//        $all_routes=Route::find()->all();
//        $new_routes=[];
//        // $all_prices = ArrayHelper::map(Products_for_entity::find()->where(['deleted'=>0])->all(),'id_product','price');
//        $all_prices = Products_for_entity::find()->where(['deleted'=>0])->asArray()->all();
//        $prices = ArrayHelper::map($all_prices, 'id_product','price','id_entity');
//        $shops=Shop::find()->asArray()->all();
//        $states = [];
//        $all_states = States_for_waybill::find()->where(['deleted'=>0])->asArray()->all();
//
//        foreach($all_states as $state)
//        {
//            $states[$state['id_waybill']][$state['id_shop']][$state['id_product']] = $state;
//        }
//
//        // Yii::$app->session['states'] = $states;
//        foreach ($all_routes as $route)
//        {
//            $new_routes['waybill'][$route->id]['name']=$route->name;
//            $waybills=Waybill::find()->where(['and',['deleted'=>0,'id_route'=>$route->id]])->all();
//            foreach ($waybills as $waybill)
//            {
//                // $all_states=States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$waybill->id]])->all();
//                foreach ($states[$waybill->id] as $id_shop => $state)
//                {
//                    foreach ($shops as $shop)
//                        {
//                            if($shop['id']==$id_shop)
//                            {
//                                $id_entity=$shop['id_entity'];
//                                break;
//                            }
//                        }
//                        // Yii::$app->session['states_waybill' . ' ' . $id_shop] = $state;
//                    foreach($state as $product_id => $info)
//                    {
//                        $new_routes['waybill'][$route->id]['products'][$info['id_product']]['count']+=$info['count'];
//                        $new_routes['waybill'][$route->id]['products'][$info['id_product']]['returns']+=$info['returns'];
//                        $new_routes['waybill'][$route->id]['products'][$info['id_product']]['count_money']+=$info['count']*$prices[$id_entity][$info['id_product']];
//                        $new_routes['waybill'][$route->id]['products'][$info['id_product']]['returns_money']+=$info['returns']*$prices[$id_entity][$info['id_product']];
//
//
//                        $new_routes['waybill'][$route->id]['count_all_waybill']+=$info['count'];
//                        $new_routes['waybill'][$route->id]['returns_all_waybill']+=$info['returns'];
//
//                        $new_routes['waybill'][$route->id]['count_money_all_waybill']+=$info['count']*$prices[$id_entity][$info['id_product']];
//                        $new_routes['waybill'][$route->id]['returns_money_all_waybill']+=$info['returns']*$prices[$id_entity][$info['id_product']];
//
//                        if($new_routes['waybill'][$route->id]['returns_all_waybill']!=0 && $new_routes['waybill'][$route->id]['count_all_waybill']!=0)
//                        {
//                            $new_routes['waybill'][$route->id]['percent']=
//                                round($new_routes['waybill'][$route->id]['returns_all_waybill']
//                                    /
//                                    $new_routes['waybill'][$route->id]['count_all_waybill'],3)*100;
//
//                            $new_routes['waybill'][$route->id]['percent_money']=
//                                round($new_routes['waybill'][$route->id]['returns_money_all_waybill']
//                                    /
//                                    $new_routes['waybill'][$route->id]['count_money_all_waybill'],3)*100;
//
//                        }else {
//                            if ($new_routes['waybill'][$route->id]['returns_all_waybill'] == 0) {
//                                $new_routes['waybill'][$route->id]['percent'] = 0;
//                                $new_routes['waybill'][$route->id]['percent_money'] = 0;
//                            }
//                            if ($new_routes['waybill'][$route->id]['count_all_waybill'] == 0) {
//                                $new_routes['waybill'][$route->id]['percent'] = "-";
//                                $new_routes['waybill'][$route->id]['percent_money'] = "-";
//                            }
//                        }
//
//
//                        $new_routes['all_products'][$info['id_product']]['count_all']+=$info['count'];
//                        $new_routes['all_products'][$info['id_product']]['returns_all']+=$info['returns'];
//                        $new_routes['all_products'][$info['id_product']]['count_money_all']+=$info['count']*$prices[$id_entity][$info['id_product']];
//                        $new_routes['all_products'][$info['id_product']]['returns_money_all']+=$info['returns']*$prices[$id_entity][$info['id_product']];
//
//                        if($new_routes['all_products'][$info['id_product']]['returns_all']!=0 && $new_routes['all_products'][$info['id_product']]['count_all']!=0)
//                        {
//                            $new_routes['all_products'][$info['id_product']]['percent']=
//                                round($new_routes['all_products'][$info['id_product']]['returns_all']
//                                    /
//                                    $new_routes['all_products'][$info['id_product']]['count_all'],3)*100;
//
//                            $new_routes['all_products'][$info['id_product']]['percent_money']=
//                                round($new_routes['all_products'][$info['id_product']]['returns_money_all']
//                                    /
//                                    $new_routes['all_products'][$info['id_product']]['count_money_all'],3)*100;
//
//                        }
//                        else {
//                            if ($new_routes['all_products'][$info['id_product']]['returns_all'] == 0) {
//                                $new_routes['all_products'][$info['id_product']]['percent'] = 0;
//                                $new_routes['all_products'][$info['id_product']]['percent_money'] = 0;
//                            }
//                            if ($new_routes['all_products'][$info['id_product']]['count_all'] == 0) {
//                                $new_routes['all_products'][$info['id_product']]['percent'] = '-';
//                                $new_routes['all_products'][$info['id_product']]['percent_money'] = "-";
//                            }
//                        }
//
//
//                        $new_routes['count_final']+=$info['count'];
//                        $new_routes['returns_final']+=$info['returns'];
//                        $new_routes['count_money_final']+=$info['count']*$prices[$id_entity][$info['id_product']];
//                        $new_routes['returns_money_final']+=$info['returns']*$prices[$id_entity][$info['id_product']];
//
//
//
//                        if($new_routes['count_final']!=0 && $new_routes['returns_final']!=0)
//                        {
//                            $new_routes['percent_final']=
//                                round($new_routes['returns_final']
//                                    /
//                                    $new_routes['count_final'],3)*100;
//
//                            $new_routes['percent_money_final']=
//                                round($new_routes['returns_money_final']
//                                    /
//                                    $new_routes['count_money_final'],3)*100;
//
//                        }
//                        else {
//                            if ($new_routes['returns_final'] == 0) {
//                                $new_routes['percent_final'] = 0;
//                                $new_routes['percent_money_final'] = 0;
//                            }
//                            if ($new_routes['count_final'] == 0) {
//                                $new_routes['percent_final'] = '-';
//                                $new_routes['percent_money_final'] = "-";
//                            }
//                        }
//                    }
//
//                }
//            }
//        }
//
//
//
//        $order=ArrayHelper::map(Order_of_products::find()->all(),'order','id_product');
//        ksort($order);
//        foreach ($order as $one) {
//            $order_push[$one]['count'] = 0;
//            $order_push[$one]['returns'] = 0;
//        }
//        Yii::$app->session['new_routes']=$new_routes;
//        Yii::$app->session['order_push']=$order_push;
//        return $this->render('report_full', [
//            'new_routes'=>$new_routes,
//            'order_push'=>$order_push,
//            'model'=>$model
//
//        ]);
//    }



     public function actionReport_full() // КОД АНДРЕЯ ( МУЗЕЙНЫЙ ЭКСПОНАТ )
     {
         $model = new DynamicModel(['date_range']);
        $model->addRule(['date_range'], 'string', ['max' => 128]);
        $model->load(Yii::$app->request->post());
         $array_dates=explode(" до ",$model->date_range);
         $all_routes=Route::find()->all();
         $new_routes=[];
         //Магазины
         $shops_all=Shop::find()->asArray()->all();
         foreach ($shops_all as $shop)
         {
             $shops[(int)$shop['id']]=$shop;
         }
         //Стоимость продукции для каждого ЮР лица
         $price_for_products1=Products_for_entity::find()->where(['deleted'=>0])->asArray()->all();
         foreach ($price_for_products1 as $one)
         {
             $price_for_products_new[(int)$one['id_entity']][(int)$one['id_product']]=(float)$one['price'];
         }
         //Записи в путевом листе
         $all_states1=States_for_waybill::find()->where(['deleted'=>0])->asArray()->all();
         foreach ($all_states1 as $one)
         {
             $all_states_new[(int)$one['id_waybill']][(int)$one['id']]=$one;
//             $all_states_new[(int)$one['id_waybill']][(int)$one['id']]['id_shop']=(int)$one['id_shop'];
//             $all_states_new[(int)$one['id_waybill']][(int)$one['id']]['id_product']=(int)$one['id_product'];
//             $all_states_new[(int)$one['id_waybill']][(int)$one['id']]['returns']=(float)$one['returns'];
//             $all_states_new[(int)$one['id_waybill']][(int)$one['id']]['count']=(float)['count'];
         }

         //Путевые листы
         if($model->date_range!=null)
         {
             $waybills1=Waybill::find()->where(['and',['deleted'=>0],['between','date',$array_dates[0],$array_dates[1]]])->asArray()->all();
         }else{
             $waybills1=Waybill::find()->where(['deleted'=>0])->asArray()->all();
         }


         foreach ($waybills1 as $one)
         {
             $waybills_new[(int)$one['id_route']][(int)$one['id']]['id']=(int)$one['id'];
         }
         foreach ($all_routes as $route)
         {
             $new_routes['waybill'][$route->id]['name']=$route->name;
             $waybills=$waybills_new[$route->id];
             if($waybills!=null)
             foreach ($waybills as $waybill)
             {
                 //$all_states=States_for_waybill::find()->where(['and',['deleted'=>0,'id_waybill'=>$waybill->id]])->all();
                 $all_states=$all_states_new[(int)$waybill['id']];
                 foreach ($all_states as $state)
                 {
                     $id_entity=$shops[(int)$state['id_shop']]['id_entity'];
                     $price_for_products=$price_for_products_new[$id_entity];
                     $new_routes['waybill'][$route->id]['products'][(int)$state['id_product']]['count']+=(float)$state['count'];
                     $new_routes['waybill'][$route->id]['products'][(int)$state['id_product']]['returns']+=(float)$state['returns'];
                     $new_routes['waybill'][$route->id]['products'][(int)$state['id_product']]['count_money']+=(float)$state['count']*$price_for_products[(int)$state['id_product']];
                     $new_routes['waybill'][$route->id]['products'][(int)$state['id_product']]['returns_money']+=(float)$state['returns']*$price_for_products[(int)$state['id_product']];


                     $new_routes['waybill'][$route->id]['count_all_waybill']+=(float)$state['count'];
                     $new_routes['waybill'][$route->id]['returns_all_waybill']+=(float)$state['returns'];

                     $new_routes['waybill'][$route->id]['count_money_all_waybill']+=(float)$state['count']*$price_for_products[(int)$state['id_product']];
                     $new_routes['waybill'][$route->id]['returns_money_all_waybill']+=(float)$state['returns']*$price_for_products[(int)$state['id_product']];

                     if($new_routes['waybill'][$route->id]['returns_all_waybill']!=0 && $new_routes['waybill'][$route->id]['count_all_waybill']!=0)
                     {
                         $new_routes['waybill'][$route->id]['percent']=
                             round($new_routes['waybill'][$route->id]['returns_all_waybill']
                                 /
                                 $new_routes['waybill'][$route->id]['count_all_waybill'],3)*100;

                         $new_routes['waybill'][$route->id]['percent_money']=
                             round($new_routes['waybill'][$route->id]['returns_money_all_waybill']
                                 /
                                 $new_routes['waybill'][$route->id]['count_money_all_waybill'],3)*100;

                     }else {
                         if ($new_routes['waybill'][$route->id]['returns_all_waybill'] == 0) {
                             $new_routes['waybill'][$route->id]['percent'] = 0;
                             $new_routes['waybill'][$route->id]['percent_money'] = 0;
                         }
                         if ($new_routes['waybill'][$route->id]['count_all_waybill'] == 0) {
                             $new_routes['waybill'][$route->id]['percent'] = "-";
                             $new_routes['waybill'][$route->id]['percent_money'] = "-";
                         }
                     }


                     $new_routes['all_products'][(int)$state['id_product']]['count_all']+=(float)$state['count'];
                     $new_routes['all_products'][(int)$state['id_product']]['returns_all']+=(float)$state['returns'];
                     $new_routes['all_products'][(int)$state['id_product']]['count_money_all']+=(float)$state['count']*$price_for_products[(int)$state['id_product']];
                     $new_routes['all_products'][(int)$state['id_product']]['returns_money_all']+=(float)$state['returns']*$price_for_products[(int)$state['id_product']];

                     if($new_routes['all_products'][(int)$state['id_product']]['returns_all']!=0 && $new_routes['all_products'][(int)$state['id_product']]['count_all']!=0)
                     {
                         $new_routes['all_products'][(int)$state['id_product']]['percent']=
                             round($new_routes['all_products'][(int)$state['id_product']]['returns_all']
                                 /
                                 $new_routes['all_products'][(int)$state['id_product']]['count_all'],3)*100;

                         $new_routes['all_products'][(int)$state['id_product']]['percent_money']=
                             round($new_routes['all_products'][(int)$state['id_product']]['returns_money_all']
                                 /
                                 $new_routes['all_products'][(int)$state['id_product']]['count_money_all'],3)*100;

                     }
                     else {
                         if ($new_routes['all_products'][(int)$state['id_product']]['returns_all'] == 0) {
                             $new_routes['all_products'][(int)$state['id_product']]['percent'] = 0;
                             $new_routes['all_products'][(int)$state['id_product']]['percent_money'] = 0;
                         }
                         if ($new_routes['all_products'][(int)$state['id_product']]['count_all'] == 0) {
                             $new_routes['all_products'][(int)$state['id_product']]['percent'] = '-';
                             $new_routes['all_products'][(int)$state['id_product']]['percent_money'] = "-";
                         }
                     }


                     $new_routes['count_final']+=(float)$state['count'];
                     $new_routes['returns_final']+=(float)$state['returns'];
                     $new_routes['count_money_final']+=(float)$state['count']*$price_for_products[(int)$state['id_product']];
                     $new_routes['returns_money_final']+=(float)$state['returns']*$price_for_products[(int)$state['id_product']];



                     if($new_routes['count_final']!=0 && $new_routes['returns_final']!=0)
                     {
                         $new_routes['percent_final']=
                             round($new_routes['returns_final']
                                 /
                                 $new_routes['count_final'],3)*100;

                         $new_routes['percent_money_final']=
                             round($new_routes['returns_money_final']
                                 /
                                 $new_routes['count_money_final'],3)*100;

                     }
                     else {
                         if ($new_routes['returns_final'] == 0) {
                             $new_routes['percent_final'] = 0;
                             $new_routes['percent_money_final'] = 0;
                         }
                         if ($new_routes['count_final'] == 0) {
                             $new_routes['percent_final'] = '-';
                             $new_routes['percent_money_final'] = "-";
                         }
                     }
                 }
             }
         }



         $order=ArrayHelper::map(Order_of_products::find()->all(),'order','id_product');
         ksort($order);
         foreach ($order as $one) {
             $order_push[$one]['count'] = 0;
             $order_push[$one]['returns'] = 0;
         }

         return $this->render('report_full', [
             'new_routes'=>$new_routes,
             'order_push'=>$order_push,
             'model'=>$model
         ]);
     }

    public function actionExcel_full()
    {
        $spreadsheet = new Spreadsheet();
// Add some data
        $new_routes=Yii::$app->session['new_routes'];
        $order_push=Yii::$app->session['order_push'];
        $order_new=$order_push;
        $order_new_last=$order_push;
        $for_excel=[];
        $for_excel_money=[];
        $counter=1;
        foreach ($new_routes['waybill'] as $route)
        {
            $for_excel[$counter]['N']=$counter;
            $for_excel[$counter]['name']=$route['name'];
            if($route['products']!=null) {
                foreach ($route['products'] as $key => $product) {
                    $order_new[$key]['count'] = $product['count'];
                    $order_new[$key]['returns'] = $product['returns'];
                }
                foreach ($order_new as $key => $order)
                {
                    $for_excel[$counter][$key.'count']=$order['count'];
                    $for_excel[$counter][$key.'returns']=$order['returns'];
                }
                $for_excel[$counter]['count_all_waybill']=$route['count_all_waybill'];
                $for_excel[$counter]['returns_all_waybill']=$route['returns_all_waybill'];
                $for_excel[$counter]['percent']=$route['percent'];
            }

            $counter++;
    }
//Итого
                    $for_excel[$counter]['1']='';
                        $for_excel[$counter]['2']='Итого';
                     foreach ($new_routes['all_products'] as $key=>$product)
                     {
                        $order_new_last[$key]['count_all'] =$product['count_all'];
                        $order_new_last[$key]['returns_all'] =$product['returns_all'];
                     }

                         foreach ($order_new_last as $key=>$order)
                         {
                             $for_excel[$counter][$key.'count_all']=$order['count_all'];
                             $for_excel[$counter][$key.'returns_all']=$order['returns_all'];
                         }
                        $for_excel[$counter]['count_final']=$new_routes['count_final'];
                         $for_excel[$counter]['returns_final']=$new_routes['returns_final'];
                        $for_excel[$counter]['percent_final']=$new_routes['percent_final'];
                        $counter++;


        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'font'=> ['size'=>10],
        ];

        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth('25');
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth('16');
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth('16');
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth('16');
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth('16');
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth('16');
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth('16');
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth('16');
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth('16');
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth('16');
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth('16');
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth('16');
        $spreadsheet->getActiveSheet()->getColumnDimension('n')->setWidth('16');
        $spreadsheet->getActiveSheet()->getColumnDimension('o')->setWidth('16');
        $spreadsheet->getActiveSheet()->getColumnDimension('p')->setWidth('16');
        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth('16');
        $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth('16');
        $spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth('16');
        $spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth('16');
        $spreadsheet->getActiveSheet()->getColumnDimension('U')->setWidth('20');
        $spreadsheet->getActiveSheet()->getColumnDimension('V')->setWidth('20');
        $spreadsheet->getActiveSheet()->getColumnDimension('W')->setWidth('16');



        $spreadsheet->getActiveSheet()->
        setCellValue('a1', '№')->
        setCellValue('b1', 'Магазин')->
        setCellValue('c1', 'Мол 0.9')->
        setCellValue('d1', '')->
        setCellValue('e1', 'Мол 0,5')->
        setCellValue('f1', '')->
        setCellValue('g1', 'Твор')->
        setCellValue('h1', '')->
        setCellValue('i1', 'Смет')->
        setCellValue('j1', '')->
        setCellValue('k1', 'Вар')->
        setCellValue('l1', '')->
        setCellValue('m1', 'Ряж')->
        setCellValue('n1', '')->
        setCellValue('o1', 'Кеф')->
        setCellValue('p1', '')->
        setCellValue('q1', 'Твор кг.')->
        setCellValue('r1', '')->
        setCellValue('s1', 'Сме кг.')->
        setCellValue('t1', '')->
        setCellValue('u1', 'Итого реализованно')->
        setCellValue('v1', 'Итого возвратов')->
        setCellValue('w1', 'Отн. откл %');





        $spreadsheet->getActiveSheet()->mergeCells('C1:D1');
        $spreadsheet->getActiveSheet()->getStyle('C1:D1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->mergeCells('E1:F1');
        $spreadsheet->getActiveSheet()->getStyle('E1:F1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->mergeCells('G1:H1');
        $spreadsheet->getActiveSheet()->getStyle('G1:H1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->mergeCells('I1:J1');
        $spreadsheet->getActiveSheet()->getStyle('I1:J1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->mergeCells('K1:L1');
        $spreadsheet->getActiveSheet()->getStyle('K1:L1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->mergeCells('M1:N1');
        $spreadsheet->getActiveSheet()->getStyle('M1:N1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->mergeCells('O1:P1');
        $spreadsheet->getActiveSheet()->getStyle('O1:P1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->mergeCells('Q1:R1');
        $spreadsheet->getActiveSheet()->getStyle('Q1:R1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->mergeCells('S1:T1');
        $spreadsheet->getActiveSheet()->getStyle('S1:T1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);




        $spreadsheet->getActiveSheet()->getStyle('a1:W1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('a'.$counter.':W'.$counter)->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('U1:W'.$counter)->applyFromArray($styleArray);

        $spreadsheet->setActiveSheetIndex(0)
            ->fromArray(
                $for_excel,
                NULL,
                'A2'
            );



        //В денежном эквиваленте
        $counter_money=1;
        foreach ($new_routes['waybill'] as $route)
        {
            $for_excel_money[$counter_money]['N']=$counter_money;
            $for_excel_money[$counter_money]['name']=$route['name'];
            if($route['products']!=null) {
                foreach ($route['products'] as $key => $product) {
                    $order_new[$key]['count_money'] = $product['count_money'];
                    $order_new[$key]['returns_money'] = $product['returns_money'];
                }
                foreach ($order_new as $key => $order)
                {
                    $for_excel_money[$counter_money][$key.'count']=$order['count_money'];
                    $for_excel_money[$counter_money][$key.'returns']=$order['returns_money'];
                }
                $for_excel_money[$counter_money]['count_money_all_waybill']=$route['count_money_all_waybill'];
                $for_excel_money[$counter_money]['returns_money_all_waybill']=$route['returns_money_all_waybill'];
                $for_excel_money[$counter_money]['percent_money']=$route['percent_money'];
            }

            $counter_money++;
        }

//Итого в денежном эквиваленте
        $for_excel_money[$counter_money]['1']='';
        $for_excel_money[$counter_money]['2']='Итого';
        foreach ($new_routes['all_products'] as $key=>$product)
        {
            $order_new_last[$key]['count_money_all'] =$product['count_money_all'];
            $order_new_last[$key]['returns_money_all'] =$product['returns_money_all'];
        }

        foreach ($order_new_last as $key=>$order)
        {
            $for_excel_money[$counter_money][$key.'count_money_all']=$order['count_money_all'];
            $for_excel_money[$counter_money][$key.'returns_money_all']=$order['returns_money_all'];
        }
        $for_excel_money[$counter_money]['count_money_final']=$new_routes['count_money_final'];
        $for_excel_money[$counter_money]['returns_money_final']=$new_routes['returns_money_final'];
        $for_excel_money[$counter_money]['percent_money_final']=$new_routes['percent_money_final'];




        $counter+=10;

        $counter_head=$counter-1;
        $spreadsheet->getActiveSheet()->
        setCellValue('a'.$counter_head, '№')->
        setCellValue('b'.$counter_head, 'Магазин')->
        setCellValue('c'.$counter_head, 'Мол 0.9')->
        setCellValue('d'.$counter_head, '')->
        setCellValue('e'.$counter_head, 'Мол 0,5')->
        setCellValue('f'.$counter_head, '')->
        setCellValue('g'.$counter_head, 'Твор')->
        setCellValue('h'.$counter_head, '')->
        setCellValue('i'.$counter_head, 'Смет')->
        setCellValue('j'.$counter_head, '')->
        setCellValue('k'.$counter_head, 'Вар')->
        setCellValue('l'.$counter_head, '')->
        setCellValue('m'.$counter_head, 'Ряж')->
        setCellValue('n'.$counter_head, '')->
        setCellValue('o'.$counter_head, 'Кеф')->
        setCellValue('p'.$counter_head, '')->
        setCellValue('q'.$counter_head, 'Твор кг.')->
        setCellValue('r'.$counter_head, '')->
        setCellValue('s'.$counter_head, 'Сме кг.')->
        setCellValue('t'.$counter_head, '')->
        setCellValue('u'.$counter_head, 'Итого реализованно')->
        setCellValue('v'.$counter_head, 'Итого возвратов')->
        setCellValue('w'.$counter_head, 'Отн. откл %')
        ;

        $spreadsheet->getActiveSheet()->mergeCells('C'.$counter_head.':D'.$counter_head);
        $spreadsheet->getActiveSheet()->getStyle('C'.$counter_head.':D'.$counter_head)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->mergeCells('E'.$counter_head.':F'.$counter_head);
        $spreadsheet->getActiveSheet()->getStyle('E'.$counter_head.':F'.$counter_head)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->mergeCells('G'.$counter_head.':H'.$counter_head);
        $spreadsheet->getActiveSheet()->getStyle('G'.$counter_head.':H'.$counter_head)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->mergeCells('I'.$counter_head.':J'.$counter_head);
        $spreadsheet->getActiveSheet()->getStyle('I'.$counter_head.':J'.$counter_head)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->mergeCells('K'.$counter_head.':L'.$counter_head);
        $spreadsheet->getActiveSheet()->getStyle('K'.$counter_head.':L'.$counter_head)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->mergeCells('M'.$counter_head.':N'.$counter_head);
        $spreadsheet->getActiveSheet()->getStyle('M'.$counter_head.':N'.$counter_head)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->mergeCells('O'.$counter_head.':P'.$counter_head);
        $spreadsheet->getActiveSheet()->getStyle('O'.$counter_head.':P'.$counter_head)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->mergeCells('Q'.$counter_head.':R'.$counter_head);
        $spreadsheet->getActiveSheet()->getStyle('Q'.$counter_head.':R'.$counter_head)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->mergeCells('S'.$counter_head.':T'.$counter_head);
        $spreadsheet->getActiveSheet()->getStyle('S'.$counter_head.':T'.$counter_head)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $spreadsheet->getActiveSheet()->getStyle('a'.$counter_head.':W'.$counter_head)->applyFromArray($styleArray);
        $spreadsheet->setActiveSheetIndex(0)
            ->fromArray(
                $for_excel_money,
                NULL,
                'A'.$counter
            );
        $counter_money_final=$counter+$counter_money-1;
        $spreadsheet->getActiveSheet()->getStyle('a'.$counter_head.':W'.$counter_money_final)->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('a'.$counter_money_final.':W'.$counter_money_final)->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('U'.$counter_head.':W'.$counter_money_final)->applyFromArray($styleArray);
;
// Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Общий_отчет');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="01otchet.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
    public function actionExcel($id_route)
    {
        $id_route=1;
    }
}
