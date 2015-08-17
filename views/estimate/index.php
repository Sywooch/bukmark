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
			['class' => 'yii\grid\SerialColumn'],
			'id',
			[
				'attribute' => 'total',
				'format' => ['decimal', 2]
			],
			[
				'attribute' => 'cost',
				'format' => ['decimal', 2]
			],
			[
				'attribute' => 'total_checked',
				'format' => ['decimal', 2]
			],
			[
				'attribute' => 'cost_checked',
				'format' => ['decimal', 2]
			],
			[
				'attribute' => 'us',
				'format' => ['decimal', 2]
			],
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view}{delete}'
			],
		],
	]);
	?>

</div>
