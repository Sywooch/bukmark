<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ClientContact */
/* @var $supplier app\models\Client */

$this->title = 'Add Contact';
$this->params['breadcrumbs'][] = ['label' => 'Clients', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $client->name, 'url' => ['view', 'id' => $client->id]];
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
