<?php
/**
 * @var string $label
 * @var array $versions
 * @var array $relations
*/

use dmstr\modules\publication\widgets\ModelVersions;
use yii\helpers\Html;
use yii\helpers\VarDumper;

if (!empty($label)) {
//    echo Html::tag('div', $label, ['tag' => 'h1']);
}
foreach ($versions as $version) {
    foreach ($version as $versionDetail) {
        echo $versionDetail['auditEntry']->created;
        echo VarDumper::dumpAsString($versionDetail['attributes'], 10, true);
    }

}

foreach ($relations as $id => $config) {
    echo ModelVersions::widget($config);
}

