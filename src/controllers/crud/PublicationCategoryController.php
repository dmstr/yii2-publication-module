<?php

namespace dmstr\modules\publication\controllers\crud;

use dmstr\modules\publication\models\crud\PublicationCategory;
use dmstr\modules\publication\models\crud\search\PublicationCategory as PublicationCategorySearch;

/**
 * This is the class for controller "PublicationCategoryController".
 */
class PublicationCategoryController extends BaseController
{

    public $model = PublicationCategory::class;
    public $searchModel = PublicationCategorySearch::class;
}
