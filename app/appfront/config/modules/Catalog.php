<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
	'catalog' => [
		'class' => '\fecshop\app\appfront\modules\Catalog\Module',
		'params'=> [
			'category_breadcrumbs' => false, # 是否显示分类的面包屑导航。
			/**
			 * 注意：做侧栏分类产品过滤的属性，必须是select类型的，其他的类型请不要用，
			 * 对于select类型，目前不支持多语言数据库存储，select类型的各个值是通过前端翻译文件来实现翻译的、
			 * 对于color  size 对应的保存值，只可以使用 '数字','字符','空格','&','-','_' 这6类字符
			 */
			'category_filter_attr' =>[
				'color','size',
			], 
			
			'category_filter_category' 	=> true,
			'category_filter_price' 	=> true,
			'category_query' =>[
				# 放到第一个的就是默认值，譬如下面的30
				'numPerPage' => [4,30,60,90],		# 产品显示个数的列举
				# 放到第一个的就是默认值，譬如下面的hot
				'sort' => [						# 所有排序方式
					# 下面的譬如hot  new  low-to-high 只能用 字母，数组，-，_ 这4种字符。 
					'hot' => [
						'label'   	=> 'Hot',   # 显示的字符
						'db_columns'=> 'score', # 对应数据库的字段
						'direction'	=> 'desc',  # 排序方式
					],
					'new' => [
						'label'   	=> 'New',
						'db_columns'=> 'created_at',
						'direction'	=> 'desc',
					],
					'low-to-high' => [
						'label'   	=> '$ Low to High',
						'db_columns'=> 'price',
						'direction'	=> 'asc',
					],
					'high-to-low' => [
						'label'   	=> '$ High to Low',
						'db_columns'=> 'price',
						'direction'	=> 'desc',
					],
				],
				'price_range' => [
					'0-10',
					'10-20',
					'20-30',
					'30-50',
					'50-100',
					'100-150',
					'150-300',
					'300-500',
					'500-1000',
					'1000-',
				],
			],
			# 产品部分的设置
			# 产品页面图片的设置
			'productImgSize' => [
				'small_img_width'  => 80,  # 底部小图的宽度
				'small_img_height' => 110,  # 底部小图的高度
				'middle_img_width' => 400,  # 主图的宽度
			],
			'productImgMagnifier' => false, # 是否已放大镜的方式显示，如果否，则是内窥的方式查看
		],
	],
];




