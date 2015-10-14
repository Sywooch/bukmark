<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
		<?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?=
		Html::a('Eliminar', ['delete', 'id' => $model->id], [
			'class' => 'btn btn-danger',
			'data' => [
				'confirm' => '¿Está seguro de eliminar este elemento?',
				'method' => 'post',
			],
		])
		?>
    </p>

	<?=
	DetailView::widget([
		'model' => $model,
		'attributes' => [
			'id',
			'title',
			['label' => 'Categoría', 'value' => $model->category->title],
			['label' => 'Proveedor', 'value' => $model->supplier->name],
			'supplier_code',
			'bukmark_code',
			['label' => 'Imagen', 'value' => $model->imageUrl, 'format' => ['image', ['height' => '100']]],
			'description:ntext',
		],
	])
	?>
</div>
