<?php
/**
 * --- VARIABLES ---
 *
 * @var $item \dmstr\modules\publication\models\crud\PublicationItem
 */
use dmstr\modules\publication\widgets\Publication;
use hrzg\widget\widgets\Cell;

$this->title = Yii::$app->settings->get('site','publication.default-detail')?: Yii::t('publication','Detail');
?>
<div class="publication-default-index">
    <?=Cell::widget(['id' => 'publication_detail_top', 'requestParam' => 'publication_detail_top'])?>
    <?= Publication::widget(['item' => $item,'teaser' => false])?>
    <?=Cell::widget(['id' => 'publication_detail_bottom', 'requestParam' => 'publication_detail_bottom'])?>
</div>
