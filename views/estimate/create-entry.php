<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EstimateEntry */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Agregar producto';
$this->params['breadcrumbs'][] = ['label' => 'Presupuestos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->estimate->title, 'url' => ['view', 'id' => $model->estimate->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="estimate-entry-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_entry-form', [
        'model' => $model,
		'searchModel' => $searchModel,
		'dataProvider' => $dataProvider,
    ]) ?>

</div>
