<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Massbuy */
/* @var $product app\models\Product */

$this->title = 'Agregar descuento por cantidad';
$this->params['breadcrumbs'][] = ['label' => 'Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $product->id, 'url' => ['view', 'id' => $product->id]];
$this->params['breadcrumbs'][] = $model->id;
?>
<div class="variant-create">

    <h1><?= Html::encode($this->title) ?></h1>

	<?=
	$this->render('_massbuy-form', [
		'model' => $model,
	])
	?>

</div>
