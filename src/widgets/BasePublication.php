<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\widgets;


use dmstr\modules\publication\components\PublicationHelper;
use dmstr\modules\publication\models\crud\PublicationItem;
use dmstr\modules\publication\models\crud\PublicationItemTranslation;
use dmstr\modules\publication\models\crud\PublicationItemXTag;
use dmstr\modules\publication\models\crud\query\PublicationItemQuery;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use yii\base\InvalidConfigException;
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
 * @property PublicationItemQuery itemsQuery
 * @property int categoryId
 */
abstract class BasePublication extends Widget
{

    public $teaser = true;
    public $limit;
    protected $itemsQuery;

    /**
     * can be:
     * - null (no category constraint)
     * - 'all' (explicitly no category constraint)
     * - positiv integer (select items IN category ID)
     * - negativ integer (select items NOT IN category ID)
     * - array of positiv|negativ integers (same IN/NOT IN rules as for simple integer)
     * @var mixed
     */
    public $categoryId;

    public $tagId;


    public function init()
    {
        $this->itemsQuery = PublicationItem::find()
            ->published()
            ->limit($this->limit);

        foreach ($this->filterConditions('categoryId', 'category_id') as $condition) {
            $this->itemsQuery->andWhere($condition);
        }

        if (!empty($this->tagId)) {
            $this->itemsQuery->leftJoin(['x' => PublicationItemXTag::tableName()], PublicationItem::tableName() . '.id = x.item_id');
            $this->itemsQuery->groupBy(PublicationItem::tableName() . '.id');

            foreach ($this->filterConditions('tagId', 'tag_id') as $condition) {
                $this->itemsQuery->andWhere($condition);
            }
        }


        $this->itemsQuery->orderBy(['release_date' => SORT_DESC]);

//        var_dump($this->itemsQuery->createCommand()->rawSql);
    }

    /**
     * @param string $attributeName
     * @param string $columnName
     *
     * @return array
     *
     * if we get one or a list of category_ids build constraint
     * to exclude IDs they can be dafined as negative int
     */
    protected function filterConditions(string $attributeName, string $columnName): array
    {
        $conditions = [];
        if (!empty($this->$attributeName) && $this->$attributeName !== PublicationHelper::ALL) {
            $inIds = [];
            $notInIds = [];
            if (is_array($this->$attributeName)) {
                foreach ($this->$attributeName as $id) {
                    if (is_numeric($id)) {
                        (int)$id < 0 ? $notInIds[] = -(int)$id : $inIds[] = (int)$id;
                    }
                }
            } else {
                (int)$this->$attributeName < 0 ? $notInIds[] = -(int)$this->$attributeName : $inIds[] = (int)$this->$attributeName;
            }

            if (!empty($inIds)) {
                $conditions[] = [$columnName => $inIds];
            }

            if (!empty($notInIds)) {
                $conditions[] = ['NOT', [$columnName => $notInIds]];

            }
        }
        return $conditions;
    }


    /**
     * @param PublicationItem $publicationItem
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws InvalidConfigException
     * @return string
     */
    protected function renderHtmlByPublicationItem($publicationItem)
    {
        if ($this->teaser) {
            $properties = Json::decode($publicationItem->teaser_widget_json);
            // allow usage of content variables in teaser
            $properties['content'] = Json::decode($publicationItem->content_widget_json);
        } else {
            $properties = Json::decode($publicationItem->content_widget_json);
            $properties['teaser'] = Json::decode($publicationItem->teaser_widget_json);
        }

        $properties['model'] = $publicationItem instanceof PublicationItemTranslation ? $publicationItem->item : $publicationItem;

        $publicationWidget = '';

        $publicationWidget .= $publicationItem->category->render((array)$properties, $this->teaser);

        if ($this->teaser) {
            $itemId = $publicationItem instanceof PublicationItem ? $publicationItem->id : $publicationItem->item_id;
            $urlParts = ['/publication/default/detail', 'itemId' => $itemId];
            !empty($publicationItem->title) ? $urlParts['title'] = $publicationItem->title : null;
            $publicationWidget = Html::a($publicationWidget, $urlParts, ['class' => 'publication-detail-link']);
        }

        return $publicationWidget;
    }
}