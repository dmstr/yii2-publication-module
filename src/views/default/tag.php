<?php
/**
 * --- VARIABLES ---
 *
 * @var $tagId integer
 * @var $categoryId integer
 * @var $limit integer
 */

use dmstr\modules\publication\widgets\TaggedPublication;
use hrzg\widget\widgets\Cell;

$this->title = Yii::$app->settings->get('site', 'publication.default-tag') ?: Yii::t('publication', 'Overview');
?>
<div class="publication-default-tag publication-default-index">
    <?= Cell::widget(['id' => 'publication_tag_top', 'requestParam' => 'publication_tag_top']) ?>
    <?= TaggedPublication::widget(['tagId' => $tagId,'categoryId' => $categoryId, 'limit' => $limit,'pagination' => true]) ?>
    <?= Cell::widget(['id' => 'publication_tag_bottom', 'requestParam' => 'publication_tag_bottom']) ?>
</div>
