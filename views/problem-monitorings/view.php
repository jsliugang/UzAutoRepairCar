<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ProblemMonitorings */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Problem Monitorings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="problem-monitorings-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'sector',
            'shift',
            'date',
            'model',
            'department',
            'PO',
            'problem:ntext',
            'spent_on',
            'comment:ntext',
            'winno',
            'user_id',
            'created_at',
        ],
    ]) ?>

</div>