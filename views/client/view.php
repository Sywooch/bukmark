<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Client */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Está seguro de eliminar este elemento?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'cuit',
			'delivery_address',
            'address',
            'payment_conditions',
            'notes:ntext',
        ],
    ]) ?>
	
	<h2>Contacto</h2>
	
	<?=
	GridView::widget([
		'dataProvider' => $dataProvider,
		'columns' => [
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

<?= Html::a('Agregar contacto', ['add-contact', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

</div>
