<?php
/**
 * --- VARIABLES ---
 *
 * @var $item \dmstr\modules\publication\models\crud\PublicationItem
 */

use dmstr\modules\publication\widgets\Publication;
use hrzg\widget\widgets\Cell;

$showWidgetCells = $this->context->module->showWidgetCellsInDetailView;

if (!empty($item->title)) {
    $title  = $item->title;
} else {
    $title = Yii::$app->settings->get('site', 'publication.default-detail') ?: Yii::t('publication', 'Detail');
}
$this->title = htmlspecialchars($title, ENT_NOQUOTES, Yii::$app ? Yii::$app->charset : 'UTF-8', false);
?>
<div class="publication-default-detail">

    <?php
    if ($showWidgetCells) {
        echo Cell::widget(['id' => 'publication_detail_top', 'requestParam' => 'publication_detail_top']);
        echo Cell::widget(['id' => 'publication_detail_top_' . $item->id, 'requestParam' => 'publication_detail_top_' . $item->id]);
    }
    echo Publication::widget(['item' => $item, 'teaser' => false]);
    if ($showWidgetCells) {
        echo Cell::widget(['id' => 'publication_detail_bottom_' . $item->id, 'requestParam' => 'publication_detail_bottom_' . $item->id]);
        echo Cell::widget(['id' => 'publication_detail_bottom', 'requestParam' => 'publication_detail_bottom']);
    }
    ?>
</div>
