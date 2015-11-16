<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Receipt;
use app\models\Client;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel app\models\ReceiptSearch */

$this->title = 'Facturas';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1>Filtrar por fecha</h1>

<div class="receipt-form">

	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($searchModel, 'from_date')->widget(\yii\jui\DatePicker::classname(), ['options' => ['class' => 'form-control']]) ?>

	<?= $form->field($searchModel, 'to_date')->widget(\yii\jui\DatePicker::classname(), ['options' => ['class' => 'form-control']]) ?>

    <div class="form-group">
		<?= Html::submitButton('Filtrar', ['class' => 'btn btn-primary']) ?>
    </div>

	<?php ActiveForm::end(); ?>

</div>

<div class="receipt-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
        'columns' => [
            [
				'attribute' => 'id',
				'options' => ['style' => 'width: 200px;'],
			],
			[
				'label' => 'Cliente',
				'value' => 'estimate.client.name',
				'filter' => Html::activeDropDownList($searchModel, 'client_id', Client::getIdNameArray(), ['class'=>'form-control', 'prompt' => 'Nombre']),
			],
            [
				'label' => 'Presupuesto',
				'value' => 'estimate.title',
			],
            [
				'label' => 'Estado',
				'value' => 'statusLabel',
				'filter' => Html::activeDropDownList($searchModel, 'status', Receipt::statusLabels(), ['class'=>'form-control', 'prompt' => 'Estado']),
			],
            [
				'attribute' => 'created_date',
				'format' => 'date',
				'filter' => false,
			],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
