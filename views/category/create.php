<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Category */

$this->title = 'Crear categoría';
$this->params['breadcrumbs'][] = ['label' => 'Productos', 'url' => ['product/index']];
$this->params['breadcrumbs'][] = ['label' => 'Categorías', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
