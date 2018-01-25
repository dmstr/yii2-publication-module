<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use yii\helpers\Html;

echo Html::a(Yii::t('publication','Category'),['/publication/crud/publication-category/index'],['class' => 'btn btn-primary']);
echo '</br>';
echo Html::a(Yii::t('publication','Item'),['/publication/crud/publication-item/index'],['class' => 'btn btn-primary']);
