<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sectors */

$this->title = 'Uchastka kiritish';
$this->params['breadcrumbs'][] = ['label' => 'Sectors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sectors-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
