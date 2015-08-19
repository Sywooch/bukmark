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
			'id',
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view}{delete}'
			],
		],
	]);
	?>

</div>
