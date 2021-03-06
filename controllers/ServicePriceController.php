<?php

namespace app\controllers;

use Yii;
use app\models\ServicePrice;
use app\models\ServicePriceSearch;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ServicePriceController implements the CRUD actions for ServicePrice model.
 */
class ServicePriceController extends Controller
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
     * Lists all ServicePrice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ServicePriceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ServicePrice model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ServicePrice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

	    if(Yii::$app->user->identity->id!==51){
		    throw new ForbiddenHttpException('Sizda ushbu amal uchun ruxsat mavjud emas!');
	    }

        $model = new ServicePrice();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	$model->created_by = Yii::$app->user->id;
        	$model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ServicePrice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
	    if(Yii::$app->user->identity->id!==51){
            throw new ForbiddenHttpException('Sizda ushbu amal uchun ruxsat mavjud emas!');
        }
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
	        $model->last_updated_by = Yii::$app->user->id;
	        $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ServicePrice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
          if(Yii::$app->user->identity->id!==51){
            throw new ForbiddenHttpException('Sizda ushbu amal uchun ruxsat mavjud emas!');
        }
	   
        $model = $this->findModel($id);
        $model->is_active=0;
        $model->save(false);

        return $this->redirect(['index']);
    }

    /**
     * Finds the ServicePrice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ServicePrice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ServicePrice::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
