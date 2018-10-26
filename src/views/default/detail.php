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
<div class="publication-default-detail">
    <?=Cell::widget(['id' => 'publication_detail_top', 'requestParam' => 'publication_detail_top'])?>
    <?=Cell::widget(['id' => 'publication_detail_top_' . $item->id , 'requestParam' => 'publication_detail_top_' . $item->id])?>
    <?= Publication::widget(['item' => $item,'teaser' => false])?>
    <?=Cell::widget(['id' => 'publication_detail_bottom_' . $item->id , 'requestParam' => 'publication_detail_bottom_' . $item->id])?>
    <?=Cell::widget(['id' => 'publication_detail_bottom', 'requestParam' => 'publication_detail_bottom'])?>
</div>
