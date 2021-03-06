<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProblemMonitoringsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tamirga kirgan mashinalarning monitoringi';
$this->params['breadcrumbs'][] = $this->title;
?>


    <?php $form = ActiveForm::begin(); ?>
    <div class="row" style='margin:40px 0 0 20px'>
    <div class="col-md-3">
            <?= $form->field($model, 'ponno')->textinput()->label('P/O raqamini kiriting');?>
        </div>
        <div class="col-md-1">
            <label for="test"></label>
            <button class='btn btn-primary'>So'rovni yuborish</button>
        </div>
        </div>
<?php ActiveForm::end(); ?>
<div style="width:95%">

<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function ($model) {
           $url = "problem-monitorings/update?id=".$model->id;
            if ($model > 0) {
                return [
                    'class' => ($model->problem_status == 0)? 'danger':'black',
                    'style' => 'cursor:pointer',
                    'title'=>$model->PO,
                    'onclick' => "window.location.href='{$url}'",
                ];
            }
	       
        },
        'filterModel' => $searchModel,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
	            'header' =>  'Uchastka',
	            'attribute' => 'uchastka.name',
            ],
            'shift',
            [
	            'attribute' => 'date',
	            'format' => ['date', 'php:d/m/Y']
            ],
            'model',
            [
	            'header' =>  'Bo\'lim',
	            'attribute' =>'bolim.name',
            ],

            'PO',
            'spent_on',
            //'comment:ntext',
            'winno',

            'money_spent',
             [
                'header' =>  'Kiritgan foydalanuvchi',
                'attribute' => 'userinfo.fullname',
            ],
            //'user_id',
            //'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?>

<style>
.danger {
    color: red !important;
}
/*.black {
    color : white;
    background: black;
}*/

</style>

</div>
