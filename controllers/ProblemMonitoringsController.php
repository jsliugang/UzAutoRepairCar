<?php

namespace app\controllers;

use app\models\TagAssign;
use Yii;
use app\models\Tag;
use app\models\ProblemMonitorings;
use app\models\Ponno;
use app\models\ProblemMonitoringsSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\ServicePrice;

/**
 * ProblemMonitoringsController implements the CRUD actions for ProblemMonitorings model.
 */
class ProblemMonitoringsController extends Controller
{
    /**
     * {@inheritdoc}
     */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['login', 'error'],
						'allow' => true,
					],
					[
//						'actions' => ['logout', '*'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}

    /**
     * Lists all ProblemMonitorings models.
     * @return mixed
     */

    public function actionList($query)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $items = [];
        $query = urldecode(mb_convert_encoding($query, "UTF-8"));
	        foreach (\app\models\Tag::find()->where(['like', 'id', $query])->asArray()->all() as $tag) {
            $items[] = ['keyword' => $tag['id']];
        }
        return $items;
    }

    public function actionIndex()
    {
        $searchModel = new ProblemMonitoringsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       
        $model = new ponno(); 
        if ($model->load(Yii::$app->request->post())) {
            return $this->redirect(['create', 'ponno' => $model->ponno]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single ProblemMonitorings model.
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
     * Creates a new ProblemMonitorings model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */



    public function actionCreate()
    {
        $model = new ProblemMonitorings();
	    $model->scenario = ProblemMonitorings::SCENARIO_CREATE;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
	       $tagsArray[] =ProblemMonitorings::SplitTagsString($model->tag);
	 /*      echo "<pre>";
	        var_dump($tagsArray);
	        die();*/


	       $api = Yii::$app->request->get('ponno');
        	$carData = ProblemMonitorings::FindCarData($api,$model->search);
	        $arrayed = explode(' ',$carData);
         	$getPrice = ProblemMonitorings::CalculatePrice($model->sector,$arrayed[2],$model->repair_case,$model->spent_on);

	        $model->model = $arrayed[2];
	        $model->PO = $arrayed[0];
	        $model->winno = $arrayed[4];
	        $model->user_id = Yii::$app->user->id;
	        $model->money_spent=$getPrice;
	        $model->save(false);

	        if (isset($model->tag) and !empty($model->tag)) {
		        $tags = ProblemMonitorings::SplitTagsString($model->tag);
		        foreach ($tags as $tag) {

			          /*check if tag exist in database*/
			        $check_tag = Tag::find()->where(['like', 'id', $tag])->one();
			        if($check_tag!==null){ /*if founnd*/
				        $model2 = new \app\models\TagAssign();
				        $model2->post_id = $model->id;
				        $model2->tag_id = $check_tag->id;
				        $model2->model = $model->model;
				        $model2->sector = $model->sector;
				        $model2->shift = $model->shift;
				        $model2->date = $model->created_at;
				        $model2->department = $model->department;
				        $model2->money_spent = $model->money_spent;
				        $model2->save(false);
			        }else{
				        $model3 = new Tag();
				        $model3->name = $tag;
				        $model3->save(false);

				        $model2 = new \app\models\TagAssign();
				        $model2->post_id = $model->id;
				        $model2->tag_id = $model3->id;
				        $model2->model = $model->model;
				        $model2->sector = $model->sector;
				        $model2->shift = $model->shift;
				        $model2->date = $model->created_at;
				        $model2->department = $model->department;
				        $model2->money_spent = $model->money_spent;
				        $model2->save(false);
			        }
		        }
	        }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


/*    public function actionPriceDevition()
    {
        $prices = ServicePrice::find()->all();

        foreach($prices as $price)
        {
            $model = ServicePrice::find()->where(['id'=>$price->id])->one();
            $model->is_little = $price->is_little/10;
            $model->is_medium = $price->is_medium/10;
            $model->is_large = $price->is_large/10;
            $model->save(false);
        }
}
*/
    /**
     * Updates an existing ProblemMonitorings model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
	        if (isset($model->tag) and !empty($model->tag)) {
		        TagAssign::deleteAll(['post_id'=>$model->id]);
		        $tags = ProblemMonitorings::SplitTagsString($model->tag);
		        foreach ($tags as $tag) {
			        $check_tag = Tag::find()->where(['like', 'id', $tag])->one();
			        if($check_tag!==null){
				        $model2 = new TagAssign();
				        $model2->post_id = $model->id;
				        $model2->tag_id = $check_tag->id;
				        $model2->save(false);
			        }else{
				        $model3 = new Tag();
				        $model3->name = $tag;
				        $model3->save(false);

				        $model2 = new TagAssign();
				        $model2->post_id = $model->id;
				        $model2->tag_id = $model3->id;
				        $model2->save(false);
			        }
		        }
	        }else{
		        TagAssign::deleteAll(['post_id'=>$model->id]);
	        }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ProblemMonitorings model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ProblemMonitorings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProblemMonitorings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProblemMonitorings::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
