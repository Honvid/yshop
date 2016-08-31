<?php
namespace home\controllers;

use Yii;
use common\models\MallCategory;
use common\models\MallBrand;
use common\models\MallSuppliers;
use common\models\MallUserRank;

/**
 * Class 前台首页
 * @package home\controllers
 */
class SiteController extends BaseController
{
    public function actionIndex()
    {
        $category_list = MallCategory::category_list();
        $brand_list = MallBrand::get_brand_list();
        $suppliers_list = MallSuppliers::get_suppliers_list();
        $rank_list = MallUserRank::get_rank_list();
        return $this->render('index', [
                'category_list'  => $category_list,
                'brand_list'     => $brand_list,
                'suppliers_list' => $suppliers_list,
                'rank_list'      => $rank_list,
            ]);
    }
}

