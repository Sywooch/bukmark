<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Supplier;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SupplierSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Proveedores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear proveedor', ['create'], ['class' => 'btn btn-success']) ?>
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
				'filter' => Html::activeDropDownList($searchModel, 'id', Supplier::getIdNameArray(), ['class'=>'form-control', 'prompt' => 'Nombre']),
			],
			'contactPhone',
            'website',
            'address',
            // 'notes:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
