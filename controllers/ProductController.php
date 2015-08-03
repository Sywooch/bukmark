<?php

namespace app\controllers;

use Yii;
use app\models\Product;
use app\models\ProductSearch;
use app\models\Variant;
use app\models\Massbuy;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller {

	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
		];
	}

	/**
	 * Lists all Product models.
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new ProductSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
					'searchModel' => $searchModel,
					'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Product model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id) {
		$model = $this->findModel($id);
		
		$variantDataProvider = new ActiveDataProvider([
			'query' => $model->getVariants(),
		]);
		
		$massbuyDataProvider = new ActiveDataProvider([
			'query' => $model->getMassbuys(),
		]);
		return $this->render('view', [
			'model' => $model,
			'variantDataProvider' => $variantDataProvider,
			'massbuyDataProvider' => $massbuyDataProvider,
		]);
	}

	/**
	 * Creates a new Product model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new Product();

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$model->uploadImage();
			if ($model->save(false)) {
				return $this->redirect(['view', 'id' => $model->id]);
			}
		}
		return $this->render('create', [
					'model' => $model,
		]);
	}

	/**
	 * Updates an existing Product model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id) {
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$model->uploadImage();
			if ($model->save(false)) {
				return $this->redirect(['view', 'id' => $model->id]);
			}
		}
		return $this->render('update', [
					'model' => $model,
		]);
	}

	/**
	 * Deletes an existing Product model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id) {
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}
	
	/**
	 * Add a variant to an existing Product.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionAddVariant($id) {

		$product = $this->findModel($id);

		$model = new Variant();
		$model->product_id = $product->id;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $product->id]);
		} else {
			return $this->render('add-variant', [
						'model' => $model,
						'product' => $product,
			]);
		}
	}
	
	/**
	 * Deletes an existing Variant model.
	 * If deletion is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDeleteVariant($id) {
		$model = $this->findVariantModel($id);
		$model->delete();

		return $this->redirect(['view', 'id' => $model->product->id]);
	}
	
	/**
	 * Add a massbuy to an existing Product.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionAddMassbuy($id) {

		$product = $this->findModel($id);

		$model = new Massbuy();
		$model->product_id = $product->id;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $product->id]);
		} else {
			return $this->render('add-massbuy', [
						'model' => $model,
						'product' => $product,
			]);
		}
	}

	/**
	 * Deletes an existing Massbuy model.
	 * If deletion is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDeleteMassbuy($id) {
		$model = $this->findMassbuyModel($id);
		$model->delete();

		return $this->redirect(['view', 'id' => $model->product->id]);
	}

	/**
	 * Finds the Product model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Product the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = Product::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

	/**
	 * Finds the Variant model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Variant the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findVariantModel($id) {
		if (($model = Variant::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	/**
	 * Finds the Massbuy model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Massbuy the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findMassbuyModel($id) {
		if (($model = Massbuy::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
