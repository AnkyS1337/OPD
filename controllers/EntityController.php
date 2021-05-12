<?php

namespace app\controllers;

use app\models\Entity;
use app\models\EntitySearch;
use app\models\Products_for_entity;
use app\models\Products_for_entitySearch;
use app\models\Shop;
use Yii;
use app\models\Product;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class EntityController extends Controller
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
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EntitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel= new Products_for_entitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['id_entity'=>$id]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider'=>$dataProvider,
            'searchModel'=>$searchModel
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Entity();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Product model.
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
     * Deletes an existing Product model.
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
        if((Shop::find()->where(['and',['id_entity'=>$id,'deleted'=>0]])->all())==null)
        {
            $can=true;
        }
        else{
            $can=false;
        }

        return $can ;
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Entity::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    //////////
    ///
    ///
    ///
    ///
    ///
    ///
    public function actionView_one($id)
    {
        return $this->render('view_one', [
            'model' => $this->findModel_one($id),
        ]);
    }

    /**
     * Creates a new Products_for_entity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate_one($id_entity)
    {
        $model = new Products_for_entity();
        $model->id_entity=$id_entity;

        if ($model->load(Yii::$app->request->post())) {
            $string_price=strtr((string)$model->price_dot,",",".");
            $model->price= (float)$string_price;
            $model->save();
            return $this->redirect(['view', 'id' => $model->id_entity]);
        }

        return $this->render('create_one', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Products_for_entity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate_one($id)
    {
        $model = $this->findModel_one($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view_one', 'id' => $model->id]);
        }

        return $this->render('update_one', [
            'model' => $model,
        ]);
    }
    public function actionUpdate_price()
    {
        $post=Yii::$app->request->post();
        $model= $this->findModel_one($post['id']);
        $string_price=strtr((string)$post['price'],",",".");
        $model->price= (float)$string_price;
        $model->save();
    }

    /**
     * Deletes an existing Products_for_entity model.
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

    /**
     * Finds the Products_for_entity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Products_for_entity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel_one($id)
    {
        if (($model = Products_for_entity::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

