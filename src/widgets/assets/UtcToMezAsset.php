<?php

namespace dmstr\modules\publication\widgets\assets;

use dmstr\web\AssetBundle;
use yii\web\JqueryAsset;

class UtcToMezAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/web/utc-to-mez';

    public $js = [
        'utc-to-mez.js'
    ];

    public $depends = [
        JqueryAsset::class
    ];
}
