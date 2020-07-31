<?php
/**
 * --- VARIABLES ---
 *
 * @var $categoryId integer
 * @var $tagId integer
 * @var $limit integer
 */

use dmstr\modules\publication\widgets\Publication;
use hrzg\widget\widgets\Cell;

$this->title = Yii::$app->settings->get('site', 'publication.default-index') ?: Yii::t('publication', 'Overview');
?>
<div class="publication-default-index">
    <?= Cell::widget(['id' => 'publication_index_top', 'requestParam' => 'publication_index_top']) ?>
    <?= Publication::widget(['categoryId' => $categoryId, 'tagId' => $tagId, 'limit' => $limit,'pagination' => true]) ?>
    <?= Cell::widget(['id' => 'publication_index_bottom', 'requestParam' => 'publication_index_bottom']) ?>
</div>