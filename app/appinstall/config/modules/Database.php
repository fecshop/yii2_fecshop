<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license/
 */
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
return [
    /**
     * Catalog 模块的配置，您可以在@appfront/config/fecshop_local_modules/Catalog.php 
     * 中进行配置，二开，或者重写该模块（在上面路径中如果文件不存在，自行新建配置文件。）
     */
    'database' => [
        'class' => '\fecshop\app\appinstall\modules\Database\Module',
        'params'=> [
        /*
            'productImgSize' => [
                'small_img_width'  => 80,  // 底部小图的宽度
                'small_img_height' => 110,  // 底部小图的高度
                'middle_img_width' => 400,  // 主图的宽度
            ],
            'category_breadcrumbs' => true,
            'product_breadcrumbs' => true,
        */
        ],
    ],
];
