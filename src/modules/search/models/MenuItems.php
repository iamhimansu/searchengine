<?php

namespace uims\searchengine\modules\search\models;

use Yii;
use yii\base\Model;

class MenuItems extends Model
{

    public static function getItems($employee_id = null, $academic = null)
    {

        $items = [];
        array_push($items,
            [
                'label' => '<i class="mdi mdi-search mr-2"></i>Search Engine',
                'url' => '#',
                'visible' => true,
                'options' => [
                    'class' => 'parent',
                ],
                'class' => 'submenu',
                'items' => [
                    ['label' => '<i class="mdi mdi-layout"></i>Dashboard', 'url' => ['/search']],
                    ['label' => 'Crawler', 'url' => ['/search/crawler']],
                    ['label' => 'Crawl', 'url' => ['/crawl']],
                ],
            ]
        );

        return $items;
    }

}