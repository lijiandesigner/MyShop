<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // 生成应用公共文件
    '__file__' => ['common.php', 'config.php', 'database.php'],

    // Admin 模块
    'admin'     => [
        '__file__'   => ['common.php'],
        '__dir__'    => ['behavior', 'controller', 'model', 'view','lang'],
        'controller' => ['ProductModel','GoodsInfo','GoodsBrand','Category'],
        'model'      => ['Spec','Goods','GoodsCategory','GoodsType','Brand','GoodsModel','GoodsAttr','SpecItem'],
        'view'       => ['index/index','GoodsInfo/ShowGoods','GoodsInfo/GoodsManage'],
    ],
    // 其他更多的模块定义
];
