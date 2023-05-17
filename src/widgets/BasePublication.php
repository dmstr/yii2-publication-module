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
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\LinkPager;

/**
 * Class BasePublication
 * @package dmstr\modules\publication\widgets
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 */
abstract class BasePublication extends Widget
{

    /**
     * @var PublicationItem|null
     */
    public $item;

    /**
     * en|disable pagination in list views
     *
     * @var bool
     */
    public $pagination = false;

    /**
     * next label for pagination links
     * @var string
     */
    public $paginationNextLabel = '&raquo;';

    /**
     * prev label for pagination links
     * @var string
     */
    public $paginationPrevLabel = '&laquo;';

    /**
     * page size for paginated lists
     * @var int
     */
    public $pageSize = 60;

    /**
     * Css class used within the wrapper container
     * @var string
     */
    public $wrapperCssClass = 'publication-widget publication-item-index';

    /**
     * @var bool
     */
    public $teaser = true;

    /**
     * Limit num items
     * @var int|null
     */
    public $limit;

    /**
     * The ActiveQuery for pubItem search
     * @var PublicationItemQuery
     */
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

    /**
     * can be:
     * - null (no category constraint)
     * - 'all' (explicitly no category constraint)
     * - positiv integer (select items IN category ID)
     * - negativ integer (select items NOT IN category ID)
     * - array of positiv|negativ integers (same IN/NOT IN rules as for simple integer)
     * @var mixed
     */
    public $tagId;

    /**
     * can be:
     * - null (no NOT IN item-id constraint)
     * - integer|integer[] (select items NOT IN item-ids)
     *
     * @var mixed
     */
    public $excludeId;

    /**
     * Column and direction to be ordered by
     * Columns can be specified in either a string (e.g. `"item_start_date ASC"`) or an array
     * (e.g. `['item_start_date' => SORT_ASC]`).
     *
     * @var array
     */
    public $sortOrder = ['release_date' => SORT_DESC];

    public function init()
    {
        $this->itemsQuery = PublicationItem::find()
            ->published()
            ->limit($this->limit);

        foreach ($this->filterConditions('categoryId', 'category_id') as $condition) {
            $this->itemsQuery->andWhere($condition);
        }

        if (!empty($this->tagId)) {
            $this->itemsQuery->innerJoin(['x' => PublicationItemXTag::tableName()], PublicationItem::tableName() . '.id = x.item_id');
            $this->itemsQuery->groupBy(PublicationItem::tableName() . '.id');

            foreach ($this->filterConditions('tagId', 'tag_id') as $condition) {
                $this->itemsQuery->andWhere($condition);
            }
        }

        if (!empty($this->excludeId)) {
            // make all given ids negative integers to trigger NOT IN inside self:filterConditions()
            $this->excludeId = array_map(function($id) { return (int)$id > 0 ? -(int)$id : $id; }, is_array($this->excludeId) ? $this->excludeId : [$this->excludeId]);
            foreach ($this->filterConditions('excludeId', PublicationItem::tableName() . '.id') as $condition) {
                $this->itemsQuery->andWhere($condition);
            }
        }

        $this->itemsQuery->orderBy($this->sortOrder);

    }

    /**
     * @throws Exception
     * @return PublicationItem|string
     */
    public function run()
    {

        if ($this->item) {
            return Html::tag('div', $this->renderHtmlByPublicationItem($this->item), ['class' => $this->wrapperCssClass]);
        }

        $publicationItemsQuery = $this->itemsQuery;

        $paginationHtml = null;

        if ($this->pagination) {
            $count = $publicationItemsQuery->count();
            $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->pageSize]);
            $publicationItemsQuery->offset($pagination->offset);
            $publicationItemsQuery->limit($pagination->limit);
            $paginationHtml = Html::beginTag('div', ['class' => 'publication-pagination']) . LinkPager::widget(
                    [
                        'pagination' => $pagination,
                        'nextPageLabel' => $this->paginationNextLabel,
                        'prevPageLabel' => $this->paginationPrevLabel,
                        // 'firstPageLabel' => $this->paginationPrevLabel,
                        // 'lastPageLabel' => $this->paginationNextLabel,
                    ]
                ) . Html::endTag('div');
        }

        return Html::tag('div', $this->renderItemsHtml($publicationItemsQuery->all()), ['class' => $this->wrapperCssClass]) . $paginationHtml;
    }

    protected function renderItemsHtml($publicationItems = [])
    {
        $html = '';
        /** @var PublicationItem[] $publicationItems */
        foreach ($publicationItems as $publicationItem) {
            try {
                $html .= $this->renderHtmlByPublicationItem($publicationItem);
            } catch (Exception $e) {
                Yii::error($e->getMessage(), __METHOD__);
            }
        }
        return $html;
    }

    /**
     * @param string $attributeName
     * @param string $columnName
     *
     * @return array
     *
     * if we get one or a list of category_ids build constraint
     * to exclude IDs they can be defined as negative int
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

            // Check if properties is an array
            if (empty($properties) || !is_array($properties)) {
                $properties = [];
            }

            // allow usage of content variables in teaser
            $properties['content'] = Json::decode($publicationItem->content_widget_json);
        } else {
            $properties = Json::decode($publicationItem->content_widget_json);

            // Check if properties is an array
            if (empty($properties) || !is_array($properties)) {
                $properties = [];
            }

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
