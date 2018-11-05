<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use insolita\wgadminlte\SmallBox;

?>

<div class="row">
    <?php if (Yii::$app->user->can('publication_crud_publication-category_index') || Yii::$app->user->can('publication_crud_publication-category')): ?>
        <div class="col-xs-12 col-md-6">
            <?= SmallBox::widget([
                'head' => Yii::t('publication', 'Categories'),
                'footer' => Yii::t('publication', 'Manage categories'),
                'footer_link' => ['/publication/crud/publication-category/index'],
            ]) ?>
        </div>
    <?php endif; ?>
    <?php if (Yii::$app->user->can('publication_crud_publication-item_index') || Yii::$app->user->can('publication_crud_publication-item')): ?>
        <div class="col-xs-12 col-md-6">
            <?= SmallBox::widget([
                'head' => Yii::t('publication', 'Items'),
                'footer' => Yii::t('publication', 'Manage items'),
                'footer_link' => ['/publication/crud/publication-item/index'],
                'type' => SmallBox::TYPE_RED
            ]) ?>
        </div>
    <?php endif; ?>
    <?php if (Yii::$app->user->can('publication_crud_publication-tag_index') || Yii::$app->user->can('publication_crud_publication-tag')): ?>
        <div class="col-xs-12 col-md-6">
            <?= SmallBox::widget([
                'head' => Yii::t('publication', 'Tags'),
                'footer' => Yii::t('publication', 'Manage tags'),
                'footer_link' => ['/publication/crud/publication-tag/index'],
                'type' => SmallBox::TYPE_GREEN
            ]) ?>
        </div>
    <?php endif; ?>
</div>