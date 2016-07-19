<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Client;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Clientes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear cliente', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

	<?php
	$columns = [
		[
			'attribute' => 'id',
			'options' => ['style' => 'width: 150px;'],
		],
		[
			'attribute' => 'name',
			'filter' => Html::activeDropDownList($searchModel, 'id', Client::getIdNameArray(), ['class' => 'form-control', 'prompt' => 'Nombre']),
		],
		'cuit',
		'delivery_address',
		'address',
		'payment_conditions',
	];
	?>
	
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => array_merge($columns, [['class' => 'yii\grid\ActionColumn']]),
    ]); ?>
	
	<?=
	ExportMenu::widget([
		'dataProvider' => $exportDataProvider,
		'target' => ExportMenu::TARGET_SELF,
		'showConfirmAlert' => false,
		'filename' => 'clientes',
		'columns' => $columns,
	]);
	?>

</div>
