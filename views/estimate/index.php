<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Presupuestos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="estimate-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
		<?= Html::a('Crear presupuesto', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	
	<?=
	GridView::widget([
		'dataProvider' => $dataProvider,
		'columns' => [
			[
				'attribute' => 'id',
				'options' => ['style' => 'width: 200px;'],
			],
			[
				'label' => 'Cliente',
				'value' => 'client.name',
			],
			'title',
			[
				'label' => 'Estado',
				'value' => 'statusLabel',
			],
			[
				'attribute' => 'request_date',
				'format' => 'date',
			],
			[
				'attribute' => 'sent_date',
				'format' => 'date',
			],
			[
				'class' => 'yii\grid\ActionColumn',
			],
		],
	]);
	?>

</div>
