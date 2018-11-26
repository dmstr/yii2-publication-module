<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\widgets;


use dmstr\modules\publication\models\crud\PublicationCategory;
use dmstr\modules\publication\models\crud\PublicationItem;
use dmstr\modules\publication\models\crud\PublicationItemTranslation;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Class BasePublication
 * @package dmstr\modules\publication\widgets
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property bool teaser
 * @property int|null limit
 */
abstract class BasePublication extends Widget
{

    public $teaser = true;
    public $limit = null;

    /**
     * @param PublicationItem $publicationItem
     * @param PublicationCategory $publicationCategory
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \yii\base\InvalidConfigException
     */
    protected function renderHtmlByPublicationItem($publicationItem)
    {
        if ($this->teaser) {
            $properties = Json::decode($publicationItem->teaser_widget_json);
            // allow usage of content variables in teaser
            $properties['content'] = Json::decode($publicationItem->content_widget_json);
        } else {
            $properties = Json::decode($publicationItem->content_widget_json);
        }

        $properties['model'] = $publicationItem instanceof PublicationItemTranslation ? $publicationItem->item : $publicationItem;

        $publicationWidget = '';

        $publicationWidget .= $publicationItem->category->render((array)$properties, $this->teaser);

        if ($this->teaser) {
            $itemId = $publicationItem instanceof PublicationItem ? $publicationItem->id : $publicationItem->item_id;
            $publicationWidget = Html::a($publicationWidget, ['/publication/default/detail', 'itemId' => $itemId], ['class' => 'publication-detail-link']);
        }

        return $publicationWidget;
    }
}