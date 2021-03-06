<?php

namespace app\controllers;

use Yii;
use app\models\Product;
use app\models\ProductSearch;
use app\models\EstimateEntry;
use app\models\Variant;
use app\models\Massbuy;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use zxbodya\yii2\galleryManager\GalleryManagerAction;
use yii\web\Response;

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
		$dataProvider->sort = ['defaultOrder' => ['title' => SORT_ASC]];

		// data provider used for export
		$exportDataProvider = $dataProvider;
		if (Yii::$app->request->isPost) {
			$exportDataProvider = $searchModel->search(Yii::$app->request->queryParams);
			$exportDataProvider->sort = ['defaultOrder' => ['id' => SORT_DESC]];
			$exportDataProvider->pagination->pageSize = 0;
		}

		
		return $this->render('index', [
					'searchModel' => $searchModel,
					'dataProvider' => $dataProvider,
					'exportDataProvider' => $exportDataProvider,
		]);
	}

	/**
	 * Displays a single Product model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id) {
		$model = $this->findModel($id);

		return $this->render('view', [
					'model' => $model,
		]);
	}

	/**
	 * Creates a new Product model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new Product();
		$attributes = Yii::$app->session->get(Product::className());
		if ($attributes) {
			$model->attributes = $attributes;
		}
		
		if ($model->load(Yii::$app->request->post())) {
			if (Yii::$app->request->getBodyParam('action')) {
				Yii::$app->session->set(Product::className(), $model->attributes);
				return $this->redirect(["supplier/create"]);
			} else {
				Yii::$app->session->remove(Product::className());
			}
			if ($model->save()) {
				$estimateEntry = Yii::$app->session->get(EstimateEntry::className());
				if ($estimateEntry) {
					return $this->redirect(['estimate/create-entry', 'id' => $estimateEntry['estimate_id']]);
				}
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

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		}
		return $this->render('update', [
					'model' => $model,
		]);
	}

	/**
	 * Override gallery api actions to remove the deleted image ids from the
	 * estimate entries.
	 * @param string $action
	 * @return mixed
	 */
	public function actionGalleryApi($action) {
		if ($action == 'delete') {
			$ids = Yii::$app->request->post('id');
			$entries = EstimateEntry::find()->where(['product_image_id' => $ids])->all();
			foreach ($entries as $entry) {
				$entry->product_image_id = null;
				$entry->save();
			}
		}
		$apiActionCofing = [
			'class' => GalleryManagerAction::className(),
			// mappings between type names and model classes (should be the same as in behaviour)
			'types' => [
				Product::GALLERY_IMAGE_TYPE => Product::className(),
			],
		];
		$apiAction = Yii::createObject($apiActionCofing, ['gallery-api', $this]);
		return $apiAction->run($action);
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
	 * Returns a JSON array of product image URLs and their ids
	 * @param integer $id
	 * @return mixed
	 */
	public function actionGetImages($id) {
		Yii::$app->response->format = Response::FORMAT_JSON;
		$model = $this->findModel($id);
		$images = [];
		foreach ($model->getBehavior(Product::GALLERY_IMAGE_BEHAVIOR)->getImages() as $image) {
			$images[] = ['id' => $image->id, 'url' => $image->getUrl('small')];
		}
		return $images;
	}
	
	/**
	 * Returns a JSON containg the supplier of the product of the given id
	 * @param integer $id
	 * @return mixed
	 */
	public function actionGetSupplier($id) {
		Yii::$app->response->format = Response::FORMAT_JSON;
		$model = $this->findModel($id);
		return $model->supplier;
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

}
