<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\widgets;


use dmstr\modules\publication\models\crud\PublicationItem;
use dmstr\modules\publication\models\crud\PublicationItemXTag;
use yii\helpers\ArrayHelper;

/**
 * Class TaggedPublication
 * @package dmstr\modules\publication\widgets
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property int|array tagId
 */
class TaggedPublication extends BasePublication
{
    public $tagId;

    public function run()
    {
        $itemXTags = PublicationItemXTag::find()->where(['tag_id' => $this->tagId])->all();
        $items = PublicationItem::find()->where([PublicationItem::tableName() . '.id' => ArrayHelper::map($itemXTags, 'item_id', 'item_id')])->published()->limit($this->limit)->orderBy(['release_date' => SORT_DESC])->all();

        $html = '';
        foreach ($items as $item) {
            $html .= $this->renderHtmlByPublicationItem($item, $item->category);
        }
        return "<div class='publication-widget publication-item-tagged'>" . $html . '</div>';
    }
}