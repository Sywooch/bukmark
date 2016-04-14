<?php

namespace app\controllers;

use Yii;
use app\models\SummarySearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * SummaryController.
 */
class SummaryController extends Controller
{
    public function behaviors()
    {
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
     * Get a summary of receipts.
     * @return mixed
     */
    public function actionIndex()
    {
		$searchModel = new SummarySearch();
		$searchModel->load(Yii::$app->request->post());
		
		// data provider used for gridview
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->sort = ['defaultOrder' => ['id' => SORT_DESC]];
		
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
}
