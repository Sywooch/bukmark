<?php

namespace app\assets;

use yii\web\AssetBundle;

class EstimateIndexAsset extends AssetBundle {

	public $basePath = '@webroot';
	public $baseUrl = '@web';
	
	public $js = [
		'js/estimate-index.js',
	];
	
	public $depends = [
        'app\assets\AppAsset',
    ];
}
