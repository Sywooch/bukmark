<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Supplier */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Suppliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
		<?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?=
		Html::a('Delete', ['delete', 'id' => $model->id], [
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
			'code',
			'name',
			'website',
			'address',
			'notes:ntext',
		],
	])
	?>

	<?=
	GridView::widget([
		'dataProvider' => $dataProvider,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			'id',
			'first_name',
			'last_name',
			'email',
			'phone',
			[
				'class' => 'yii\grid\ActionColumn',
				'urlCreator' => function($action, $model, $key, $index) {
					$url = '';
					switch ($action) {
						case 'view':
							$url = 'view-contact';
							break;
						case 'update':
							$url = 'update-contact';
							break;
						case 'delete':
							$url = 'delete-contact';
							break;
					}
					return Url::to([$url, 'id' => $model->id]);
				},
			],
		],
	]);
	?>

<?= Html::a('Add Contact', ['add-contact', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

</div>
