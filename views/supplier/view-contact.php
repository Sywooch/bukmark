<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Contact */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->displayName;
$this->params['breadcrumbs'][] = ['label' => 'Suppliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->supplier->name, 'url' => ['view', 'id' => $model->supplier->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
		<?= Html::a('Update', ['update-contact', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?=
		Html::a('Delete', ['delete-contact', 'id' => $model->id], [
			'class' => 'btn btn-danger',
			'data' => [
				'confirm' => 'Are you sure you want to delete this item?',
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
			'supplier_id',
			'first_name',
			'last_name',
			'email',
			'phone',
		],
	])
	?>

</div>
