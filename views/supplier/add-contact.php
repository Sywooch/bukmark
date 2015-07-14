<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Contact */
/* @var $supplier app\models\Supplier */

$this->title = 'Agregar contacto';
$this->params['breadcrumbs'][] = ['label' => 'Proveedores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $supplier->name, 'url' => ['view', 'id' => $supplier->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-create">

    <h1><?= Html::encode($this->title) ?></h1>

	<?=
	$this->render('_contact-form', [
		'model' => $model,
	])
	?>

</div>
