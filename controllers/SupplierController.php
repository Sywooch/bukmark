<?php

namespace app\controllers;

use Yii;
use app\models\Supplier;
use app\models\Contact;
use app\models\SupplierSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * SupplierController implements the CRUD actions for Supplier model.
 */
class SupplierController extends Controller {

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
					'delete-contact' => ['post'],
					'delete' => ['post'],
				],
			],
		];
	}

	/**
	 * Lists all Supplier models.
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new SupplierSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
	 * Displays a single Supplier model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id) {
		$model = $this->findModel($id);

		$dataProvider = new ActiveDataProvider([
			'query' => $model->getContacts(),
		]);

		return $this->render('view', [
					'model' => $model,
					'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Creates a new Supplier model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new Supplier();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
						'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing Supplier model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id) {
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('update', [
						'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing Supplier model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id) {
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Add a contact to an existing Supplier.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionAddContact($id) {

		$supplier = $this->findModel($id);
		
		$model = new Contact();
		$model->supplier_id = $supplier->id;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $supplier->id]);
		} else {
			return $this->render('add-contact', [
						'model' => $model,
						'supplier' => $supplier,
			]);
		}
	}
	
	/**
	 * Displays a single Contact model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionViewContact($id) {
		$model = $this->findContactModel($id);

		return $this->render('view-contact', [
					'model' => $model,
		]);
	}
	
	/**
	 * Updates an existing Contact model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdateContact($id) {
		$model = $this->findContactModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->supplier->id]);
		} else {
			return $this->render('update-contact', [
						'model' => $model,
			]);
		}
	}
	
	/**
	 * Deletes an existing Contact model.
	 * If deletion is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDeleteContact($id) {
		$model = $this->findContactModel($id);
		$model->delete();

		return $this->redirect(['view', 'id' => $model->supplier->id]);
	}

	/**
	 * Finds the Supplier model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Supplier the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = Supplier::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

	/**
	 * Finds the Contact model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Contact the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findContactModel($id) {
		if (($model = Contact::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
