<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Client;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Clientes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear cliente', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
				'attribute' => 'id',
				'options' => ['style' => 'width: 150px;'],
			],
            [
				'attribute' => 'name',
				'filter' => Html::activeDropDownList($searchModel, 'id', Client::getIdNameArray(), ['class'=>'form-control', 'prompt' => 'Nombre']),
			],
            'cuit',
			'delivery_address',
            'address',
            'payment_conditions',
            // 'notes:ntext',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
