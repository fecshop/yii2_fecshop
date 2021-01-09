<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\helper;

use fecshop\services\Service;

/**
 * Format services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */

/**
 * 二次开发说明：
 * 本文件是对echart的封装，将数据传递进函数，出来echart的html 格式，可以很方便的使用。
 * 使用方法：Yii::$service->helper->echart->xxx()，  xxx代表该类下的方法。
 * 使用该方法前，您需要加载echart js，对于appadmin已经加载，可以参看文件：@fecshop/app/appadmin/theme/base/default/layouts/dashboard.php 41行处
 * echart3 下载地址：http://echarts.baidu.com/download3.html
 * appadmin因为是后台，在上面下载的是完整的
 * 如果您想在前台入口，譬如appfront, apphtml5使用，可以选择比较小的js文件下载
 * 目前只有appadmin是默认加载了js，如果您想在前天入口，譬如appfront, apphtml5使用，您可以自行加载
 * 自行加载：进入到模板路径，将下载的js文件放到./assets/js下面，在layouts文件夹找到相应的layouts文件，添加设置即可，具体的方式您可以参考：@fecshop/app/appadmin/theme/base/default/layouts/dashboard.php
 */
class Echart extends Service
{
    protected $i = 0;
    
    public function setBaseI($i)
    {
        $this->i = $i;
    }
    /**
     * 用于登陆后台的dashboard页面的几个趋势图，的初始化。
     */
    public function setDashboardBaseI()
    {
        $this->setBaseI(10000);
    }
    /**
     *  @param $data | Array, 用来展示图标的数据。 - 折线
     *  $data = [
     * '最高气温' => [
     *     '周1' => 11,
     *     '周2' => 3,
     *     '周3' => 15,
     *     '周4' => 55,
     *     '周5' => 43,
     *     '周6' => 77,
     *     '周7' => 11,
     * ],
     * '最低气温' => [
     *     '周1' => 1,
     *     '周2' => 3,
     *     '周3' => 5,
     *     '周4' => 5,
     *     '周5' => 3,
     *     '周6' => 7,
     *     '周7' => 1,
     * ],
     *
     *  ];
     * @param $yPrex | String, 在Y轴的值加一个后缀，譬如问题加 °C
     * @param $legend | boolean, 是否显示 legend
     * @param $title | String，标题
     * @param $width | Int，图表的长度
     * @param $height | Int，图标的高度
     * @param $isShow | boolean, 是否默认显示曲线
     * @return string 返回X-Y线性图表
     *
     */
    public function getLine($data, $legend = false, $yPrex = '', $width = '100%', $height = '400px', $title = '', $isShow=true)
    {
        $this->i++;
        $div_id = "main_".$this->i;
        $legendArr = [];
        $showArr = [];
        $xAxis = [];
        $series = [];
        $legendStr = '';
        $showStr = '';
        $isShow = $isShow ? 'true' : 'false';
        
        if (is_array($data)) {
            foreach ($data as $key => $info) {
                $legendArr[] = '\''.\Yii::$service->page->translate->__($key).'\'';
                $showArr[] = '\''.$key.'\':'.$isShow;
                if (is_array($info)) {
                    foreach ($info as $x => $y) {
                        $xAxis[] = $x;
                    }
                }
            }
        }
        $legendStr = implode(',', $legendArr);
        $showStr = implode(',', $showArr);
        
        
        $xAxis = array_unique($xAxis);
        sort($xAxis);
        $xAxisArr = [];
        foreach ($xAxis as $s) {
            $xAxisArr[] =  '\''.$s.'\'';
        }
        $xAxisStr = implode(',', $xAxisArr);
        // 计算series
        $seriesArr = [];
        if (is_array($data)) {
            foreach ($data as $key => $info) {
                if (is_array($info)) {
                    $arr = [];
                    foreach ($xAxis as $s) {
                        if (isset($info[$s]) && $info[$s]) {
                            $arr[] = $info[$s];
                        } else {
                            $arr[] = 0;
                        }
                    }
                    $s = implode(',', $arr);
                    $seriesArr[] = "
                    {
                        name:'".$key."',
                        type:'line',
                        data:[".$s."],
                        smooth: true,
                        markPoint: {
                            data: [
                                {type: 'max', name: '最大值'},
                                {type: 'min', name: '最小值'}
                            ]
                        },
                        markLine: {
                            data: [
                                {type: 'average', name: '平均值'}
                            ]
                        }
                    }
                    ";
                }
            }
        }
        $seriesArr = implode(',', $seriesArr);
        $str2 = '';
        if ($width == '100%') {
            $str2 = "w = document.body.clientWidth - 200;  $('.echart_line_containter').css('width', w);";
        }
        $str = "
            <div class='echart_line_containter' id='".$div_id."' style='width: ".$width.";height:".$height.";'></div>
            <script type=\"text/javascript\">
            // 基于准备好的dom，初始化echarts实例
            $(document).ready(function(){ 
            ".$str2."
            var myChart = echarts.init(document.getElementById('".$div_id."'));

            // 指定图表的配置项和数据  
            // 自定义颜色63b2ee - 76da91 - f8cb7f - f89588 - 7cd6cf - 9192ab - 7898e1 - efa666 - eddd86 - 9987ce - 63b2ee - 76da91
            var option = {
                title: {
                    text: '".$title."'
                },
                color: [\"#00aeff\", \"#76da91\", \"#f8cb7f\", \"#f89588\", \"#7cd6cf\", \"#00aeff\", \"#eddd86\"],
                
                tooltip: {
                    trigger: 'axis'
                },";
        if ($legend) {
            $str .= "
            legend: {
                data:[".$legendStr."],
                selected:{".$showStr."}
            },
            ";
        }
        $str .="
            toolbox: {
                    show: false,
                    feature: {
                        dataZoom: {
                            yAxisIndex: 'none'
                        },
                        dataView: {readOnly: false},
                        magicType: {type: ['line', 'bar']},
                        restore: {},
                        saveAsImage: {}
                    }
                },
                dataZoom: [{}, { type: 'inside' }],
                xAxis:  {
                    type: 'category',
                    boundaryGap: false,
                    data: [".$xAxisStr."]
                },
                yAxis: {
                    type: 'value',
                    axisLabel: {
                        formatter: '{value} ".$yPrex."'
                    }
                },
                series: [
                    ".$seriesArr."
                ]
            };
            // 使用刚指定的配置项和数据显示图表。
            myChart.setOption(option);
            });
            </script>
        ";
        
        return $str;
    }
    
    /**
     * @param $data | array，example
     * [
     *    ['name'=> '张三', 'value'=>'12'],
     *    ['name'=> 'li三', 'value'=>'1']
     *    ['name'=> 'wang五', 'value'=>'62']
     * ]
     * @param $width | int, 饼图长度
     * @param $height | int, 饼图高度
     * @param $title | string, 标题
     * 通过该数组，快速的返回echart的饼图，将函数返回的值，直接输出即可显示饼图
     */
    public function getPie($data,  $isShow=true, $width=1200, $height=600, $title='')
    {
        $this->i++;
        $div_id = "main_".$this->i;
        $legendArr = [];
        $showArr = [];
        $seriesArr = [];
        $legendStr = '';
        $showStr = '';
        $seriesStr = [];
        $isShow = $isShow ? 'true' : 'false';
        
        if (is_array($data)) {
            foreach ($data as $one) {
                $name = $one['name'];
                $val = $one['value'];
                $legendArr[] = '\''.$name.'\'';
                $showArr[] = '\''.$name.'\':'.$isShow;
                $seriesArr[] = '{\'name\':\''.$name.'\',\'value\':\''.$val.'\'}';
            }
        }
        $legendStr = implode(',', $legendArr);
        $showStr = implode(',', $showArr);
        $seriesStr = implode(',', $seriesArr);
        $str = "
            <div class='echart_line_containter' id='".$div_id."' style='width:".$width."px;height:".$height."px;'></div>
            <script type=\"text/javascript\">
            $(document).ready(function(){ 
            var myChart".$div_id." = echarts.init(document.getElementById('".$div_id."'));
            var option = {
                title: {
                    text: '".$title."',
                    subtext: '',
                    left: 'center'
                },
                tooltip: {
                    trigger: 'item',
                    formatter: '{a} <br/>{b} : {c} ({d}%)'
                },
                legend: {
                    type: 'scroll',
                    orient: 'vertical',
                    left: 10,
                    top: 20,
                    bottom: 20,
                    data: [".$legendStr."],
                    selected: {".$showStr."}
                },
                series: [
                    {
                        name: '姓名',
                        type: 'pie',
                        radius: '55%',
                        center: ['50%', '50%'],
                        data: [".$seriesStr."],
                        emphasis: {
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }
                ]
            };
            myChart".$div_id.".setOption(option);
            });
            </script>
        ";
        
        return $str;
    }
    
    
}
