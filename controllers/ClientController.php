<?php

namespace app\controllers;

use Yii;
use app\models\Client;
use app\models\ClientSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\ClientContact;

/**
 * ClientController implements the CRUD actions for Client model.
 */
class ClientController extends Controller {

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
	 * Lists all Client models.
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new ClientSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->sort = ['defaultOrder' => ['name' => SORT_ASC]];

		return $this->render('index', [
					'searchModel' => $searchModel,
					'dataProvider' => $dataProvider,
				'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Client model.
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
	 * Creates a new Client model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new Client();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
						'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing Client model.
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
	 * Deletes an existing Client model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id) {
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Add a contact to an existing Client.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionAddContact($id) {

		$client = $this->findModel($id);

		$model = new ClientContact();
		$model->client_id = $client->id;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $client->id]);
		} else {
			return $this->render('add-contact', [
						'model' => $model,
						'client' => $client,
			]);
		}
	}

	/**
	 * Displays a single ClientContact model.
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
	 * Updates an existing ClientContact model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdateContact($id) {
		$model = $this->findContactModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->client->id]);
		} else {
			return $this->render('update-contact', [
						'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing ClientContact model.
	 * If deletion is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDeleteContact($id) {
		$model = $this->findContactModel($id);
		$model->delete();

		return $this->redirect(['view', 'id' => $model->client->id]);
	}

	/**
	 * Finds the Client model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Client the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = Client::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

	/**
	 * Finds the Contact model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ClientContact the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findContactModel($id) {
		if (($model = ClientContact::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

}
