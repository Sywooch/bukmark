<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ClientContact */

$this->title = $model->displayName;
$this->params['breadcrumbs'][] = ['label' => 'Clients', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->client->name, 'url' => ['view', 'id' => $model->client->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_contact-form', [
        'model' => $model,
    ]) ?>

</div>
