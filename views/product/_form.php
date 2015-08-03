<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Category;
use app\models\Supplier;
use app\models\Product;
use app\models\Currency;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Product */
/* @var $form yii\widgets\ActiveForm */

$categories = Category::find()->all();
$suppliers = Supplier::find()->all();
$currencies = Currency::labels();

?>

<div class="product-form">

	<?php $form = ActiveForm::begin(['enableClientValidation' => false, 'options' => ['enctype' => 'multipart/form-data']]); ?>

	<?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map($categories, 'id', 'title')) ?>

	<?= $form->field($model, 'supplier_id')->dropDownList(ArrayHelper::map($suppliers, 'id', 'name')) ?>

	<?= $form->field($model, 'supplier_code')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'bukmark_code')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'imageFile')->fileInput() ?>

	<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

	<?= $form->field($model, 'price')->textInput() ?>

	<?= $form->field($model, 'currency')->dropDownList($currencies) ?>
	
	<?= $form->field($model, 'utility')->textInput() ?>

    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

	<?php ActiveForm::end(); ?>

</div>
