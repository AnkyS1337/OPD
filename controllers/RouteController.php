<?php

namespace app\controllers;

use app\models\Order_of_Route;
use app\models\Order_of_RouteSearch;
use app\models\Shop;
use Yii;
use app\models\Route;
use app\models\RouteSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RouteController implements the CRUD actions for Route model.
 */
class RouteController extends Controller
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
                ],
            ],
        ];
    }

    /**
     * Lists all Route models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RouteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMap($id=1)
    {
        $route=Order_of_Route::find()->where(['and',['deleted'=>0,'id_route'=>$id]])->orderBy(['NPP'=>SORT_ASC])->select('id_shop')->asArray()->column();
        $all_shops=ArrayHelper::map(Shop::find()->where(['deleted'=>0])->all(),'id','coord');
        $counter=0;
        foreach ($route as $shop)
        {

            $map[$counter]=$all_shops[$shop];
            $counter++;
        }
        return $this->render('map',['map'=>$map]);
    }


    /**
     * Displays a single Route model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel= new Order_of_RouteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['id_route'=>$id]);
        $route=Order_of_Route::find()->where(['and',['deleted'=>0,'id_route'=>$id]])->orderBy(['NPP'=>SORT_ASC])->select('id_shop')->asArray()->column();
        $all_shops=ArrayHelper::map(Shop::find()->where(['deleted'=>0])->all(),'id','coord');
        $counter=0;
        foreach ($route as $shop)
        {

            $map[$counter]=$all_shops[$shop];
            $counter++;
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider,
            'map'=>$map
        ]);
    }

    /**
     * Creates a new Route model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Route();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Route model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Route model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

//        return $this->redirect(['index']);
    }

    public function actionCandelete($id)
    {
        if((Order_of_Route::find()->where(['and',['id_route'=>$id,'deleted'=>0]])->all())==null)
        {
            $can=true;
        }
        else{
            $can=false;
        }

        return $can ;
    }

    /**
     * Finds the Route model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Route the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Route::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
//    public function actionView_one($id)
//    {
//        return $this->render('view_one', [
//            'model' => $this->findModel_one($id),
//        ]);
//    }


    public function actionCreate_one($id_route)
    {
        $model = new Order_of_Route();
        $model->NPP=count(Order_of_Route::find()->where(['and',['deleted'=>0,'id_route'=>$id_route]])->all())+1;
        $model->id_route=$id_route;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => $model->id_route]);
        }

        return $this->render('create_one', [
            'model' => $model,
        ]);
    }


//    public function actionUpdate_one($id)
//    {
//        $model = $this->findModel_one($id);
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        }
//
//        return $this->render('update_one', [
//            'model' => $model,
//        ]);
//    }


    public function actionDelete_one($id)
    {
        $model=$this->findModel_one($id);
        $orders=Order_of_Route::find()->where(['and',['deleted'=>0],['id_route'=>$model->id_route],['>', 'NPP',$model->NPP],])->all();
        foreach ($orders as $order)
        {
            $model1=$this->findModel_one($order->id);
            $model1->NPP=($model1->NPP)-1;
            $model1->save();
        }
        $model->deleted=1;
        $model->save();

//        return $this->redirect(['view'],['id'=>$model->id_route]);
    }


    protected function findModel_one($id)
    {
        if (($model = Order_of_Route::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionChange_position_up($id)
    {

        $model1=$this->findModel_one($id);
        if(($model1->NPP)!=1)
        {
            $position1=$model1->NPP;
            $model2=Order_of_Route::find()->where(['and',['NPP'=>$position1-1,'deleted'=>0,'id_route'=>$model1->id_route]])->one();
            $position2=$model2->NPP;

            $model1->NPP=$position2;
            $model2->NPP=$position1;

            $model2->save();
            $model1->save();
        }

    }
    public function actionChange_position_down($id)
    {
        $model1=$this->findModel_one($id);
        $coun_all=count(Order_of_Route::find()->where(['and',['deleted'=>0,'id_route'=>$model1->id_route]])->all());
        if(($model1->NPP)!=$coun_all)
        {
            $position1=$model1->NPP;
            $model2=Order_of_Route::find()->where(['and',['NPP'=>$position1+1,'deleted'=>0,'id_route'=>$model1->id_route]])->one();
            $position2=$model2->NPP;

            $model1->NPP=$position2;
            $model2->NPP=$position1;

            $model2->save();
            $model1->save();
        }
    }
}
