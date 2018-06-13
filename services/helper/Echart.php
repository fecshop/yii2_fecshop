<?php
/**
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
// use \fecshop\services\helper\Format;
class Echart extends Service
{
    protected $i = 0;
    /**
     *  @property $data | Array, 用来展示图标的数据。
     *  $data = [
            '最高气温' => [
                '周1' => 11,
                '周2' => 3,
                '周3' => 15,
                '周4' => 55,
                '周5' => 43,
                '周6' => 77,
                '周7' => 11,
            ],
            '最低气温' => [
                '周1' => 1,
                '周2' => 3,
                '周3' => 5,
                '周4' => 5,
                '周5' => 3,
                '周6' => 7,
                '周7' => 1,
            ],
        
     *  ];
     * @property $legend | boolean, 是否显示 legend
     * @property $title | String，标题
     * @property $width | Int，图表的长度
     * @property $height | Int，图标的高度
     * @return String，返回X-Y线性图表
     * 
     */
    public function getLine($data, $legend = false, $title = '', $width = 600, $height = 400)
    {
        $this->i++;
        $div_id = "main_".$this->i;
        
        $legend = [];
        $xAxis = [];
        $series = [];
        $legendStr = '';
        if (is_array($data)) {
            foreach ($data as $key => $info) {
                $legend[] = '\''.$key.'\'';
                if (is_array($info)) {
                    foreach ($info as $x => $y) {
                        $xAxis[] = $x;
                    }
                    
                }
                
            }
        }
        $legendStr = implode(',', $legend);
        $xAxis = array_unique($xAxis);
        sort($xAxis);
        $xAxisArr = [];
        foreach($xAxis as $s){
            $xAxisArr[] =  '\''.$s.'\'';
        }
        $xAxisStr = implode(',',$xAxisArr);
        
        // 计算series
        $seriesArr = [];
        if (is_array($data)) {
            foreach ($data as $key => $info) {
                if (is_array($info)) {
                    $arr = [];
                    foreach($xAxis as $s){
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
        
        $str = "
            <div id='".$div_id."' style='width: ".$width."px;height:".$height."px;'></div>
            <script type=\"text/javascript\">
            // 基于准备好的dom，初始化echarts实例
            var myChart = echarts.init(document.getElementById('".$div_id."'));

            // 指定图表的配置项和数据
            var option = {
                title: {
                    text: '".$title."'
                },
                tooltip: {
                    trigger: 'axis'
                },";
        if ($legend) {
            $str .= "
            legend: {
                data:[".$legendStr."]
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
                        formatter: '{value} °C'
                    }
                },
                series: [
                    ".$seriesArr."
                ]
            };
            // 使用刚指定的配置项和数据显示图表。
            myChart.setOption(option);
            </script>
        ";
        
        return $str;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
