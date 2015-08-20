<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ClientContact */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->displayName;
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->client->name, 'url' => ['view', 'id' => $model->client->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
		<?= Html::a('Editar', ['update-contact', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?=
		Html::a('Eliminar', ['delete-contact', 'id' => $model->id], [
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
			'first_name',
			'last_name',
			'email',
			'phone',
			[
				'attribute' => 'birthdate',
				'format' => 'date',
			],
		],
	])
	?>

</div>
