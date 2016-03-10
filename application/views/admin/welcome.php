<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<style type="text/css">
.highcharts-div{padding-top: 30px;}
</style>
<div class="pd-20">
    <p class="f-20 text-success">
        欢迎使用互汇宝 <span class="f-14">v1.0</span>商家管理系统！
    </p>
    <p>登录次数：<?php echo $login_times; ?></p>
    <p>上次登录IP：<?php echo $last_login_ip; ?> 上次登录时间：<?php echo $last_login_time; ?></p>
    <div id="sales-count-stats" class="highcharts-div" style="min-width: 700px; height: 400px;"></div>
    <div id="order-count-stats" class="highcharts-div" style="min-width: 700px; height: 400px;"></div>
</div>
<script type="text/javascript">
    /**
    * Grid theme for Highcharts JS
    * @author XieBiao<hhxsv5@sina.com>
    */
    Highcharts.theme = {
        colors: ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
        chart: {
            backgroundColor: {
                linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 },
                stops: [
                    [0, 'rgb(255, 255, 255)'],
                    [1, 'rgb(240, 240, 255)']
                ]
            },
            borderWidth: 2,
            plotBackgroundColor: 'rgba(255, 255, 255, 0.9)',
            plotShadow: true,
            plotBorderWidth: 1
        },
        lang: {
            printChart: '打印图表',
            downloadPNG: '下载 PNG 图片',
            downloadJPEG: '下载 JPEG 图片',
            downloadPDF: '下载 PDF 文档',
            downloadSVG: '下载 SVG 矢量图',
            contextButtonTitle: '图表右键菜单'
        },
        credits:{
            enabled:false // 禁用版权信息
        },
        title: {
            style: {
                color: '#000',
                font: "bold 16px 'Helvetica Neue', Helvetica, 'Microsoft Yahei', 'Hiragino Sans GB', 'WenQuanYi Micro Hei', 'sans-serif'"
            }
        },
        subtitle: {
            style: {
                color: '#666',
                font: "bold 12px 'Helvetica Neue', Helvetica, 'Microsoft Yahei', 'Hiragino Sans GB', 'WenQuanYi Micro Hei', 'sans-serif'"
            }
        },
        xAxis: {
            gridLineWidth: 1,
            lineColor: '#000',
            tickColor: '#000',
            labels: {
                style: {
                    color: '#000',
                    font: "11px 'Helvetica Neue', Helvetica, 'Microsoft Yahei', 'Hiragino Sans GB', 'WenQuanYi Micro Hei', 'sans-serif'"
                }
          
            },
            title: {
                style: {
                    color: '#333',
                    fontWeight: 'bold',
                    fontSize: '12px',
                    fontFamily: "'Helvetica Neue', Helvetica, 'Microsoft Yahei', 'Hiragino Sans GB', 'WenQuanYi Micro Hei', 'sans-serif'"
                }
            }
        },
        yAxis: {
            minorTickInterval: 'auto',
            lineColor: '#000',
            lineWidth: 1,
            tickWidth: 1,
            tickColor: '#000',
            labels: {
                style: {
                    color: '#000',
                    font: "11px 'Helvetica Neue', Helvetica, 'Microsoft Yahei', 'Hiragino Sans GB', 'WenQuanYi Micro Hei', 'sans-serif'"
                }
            },
            title: {
                style: {
                    color: '#333',
                    fontWeight: 'bold',
                    fontSize: '12px',
                    fontFamily: "'Helvetica Neue', Helvetica, 'Microsoft Yahei', 'Hiragino Sans GB', 'WenQuanYi Micro Hei', 'sans-serif'"
                }
            }
        },
        legend: {
            itemStyle: {
                font: "9px 'Helvetica Neue', Helvetica, 'Microsoft Yahei', 'Hiragino Sans GB', 'WenQuanYi Micro Hei', 'sans-serif'",
                color: 'black'
    
            },
            itemHoverStyle: {
                color: '#039'
            },
            itemHiddenStyle: {
                color: 'gray'
            }
        },
        labels: {
            style: {
                color: '#99b'
            }
        },
        navigation: {
            buttonOptions: {
                theme: {
                    stroke: '#CCC'
                }
            }
        }
    };
    // Apply the theme
    var highchartsOptions = Highcharts.setOptions(Highcharts.theme);

    ﻿$(function () {
        function initSalesCountByCategoryCharts(data) {
            $('#sales-count-stats').highcharts({
                chart: {
                    type: 'column',
                    options3d: {
                        enabled : true,
                        alpha : 0,
                        beta : 0,
                        depth: 50,
                        viewDistance: 25
                    }/*,
                    margin: [50, 50, 100, 80]*/
                },
                title: {
                    text: '商品销量(按分类)'
                },
                subtitle: {
                    text: ('总计：' + data.salesCountTotal)
                },
                column: {
                    depth: 25
                },
                xAxis: {
                    categories: data.categories,
                    labels: {
                        rotation: 0,
                        align: 'center'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: '商品销量(件)'
                    }
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    enabled: true,
                    formatter: function() {
                        return this.series.name + '<br>' + this.x +': <b>'+ this.y + '</b>';
                    }
                },
                series: [{
                    name: '商品销量',
                    data: data.saleCounts,
                    dataLabels: {
                        enabled: true,
                        rotation: 0,
                        color: '#FFF',
                        align: 'center',
                        x: 0,//标题离左端x距离
                        y: 16,//标题离上端y距离
                        style: {
                            fontSize: '14px',
                            fontFamily: "'Helvetica Neue', Helvetica, 'Microsoft Yahei', 'Hiragino Sans GB', 'WenQuanYi Micro Hei', 'sans-serif'",
                            textShadow: '0 0 3px black'
                        }
                    }
                }]
            });
        }

        function loadSalesCountByCategoryData() {
            $.getJSON('<?php echo get_instance()->createUrl('admin/store/stats/salesCountByCategory'); ?>', { start_date: $('#startDate').val(), end_date: $('#endDate').val() }, function(data){
                data.code === 0 && initSalesCountByCategoryCharts(data.data) && $('#sales-count-stats').fadeIn();
            });
        }
        
        function initOrderCountByCategoryCharts(data) {
            $('#order-count-stats').highcharts({
                chart: {
                    type: 'column',
                    options3d: {
                        enabled : true,
                        alpha : 0,
                        beta : 0,
                        depth: 50,
                        viewDistance: 25
                    }/*,
                    margin: [50, 50, 100, 80]*/
                },
                title: {
                    text: '订单量(按分类)'
                },
                subtitle: {
                    text: ('总计：' + data.orderCountTotal)
                },
                column: {
                    depth: 25
                },
                xAxis: {
                    categories: data.categories,
                    labels: {
                        rotation: 0,
                        align: 'center'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: '订单量(个)'
                    }
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    enabled: true,
                    formatter: function() {
                        return this.series.name + '<br>' + this.x +': <b>'+ this.y + '</b>';
                    }
                },
                series: [{
                    name: '订单量',
                    data: data.orderCounts,
                    dataLabels: {
                        enabled: true,
                        rotation: 0,
                        color: '#FFF',
                        align: 'center',
                        x: 0,//标题离左端x距离
                        y: 16,//标题离上端y距离
                        style: {
                            fontSize: '14px',
                            fontFamily: "'Helvetica Neue', Helvetica, 'Microsoft Yahei', 'Hiragino Sans GB', 'WenQuanYi Micro Hei', 'sans-serif'",
                            textShadow: '0 0 3px black'
                        }
                    }
                }]
            });
        }

        function loadOrderCountByCategoryData() {
            $.getJSON('<?php echo get_instance()->createUrl('admin/store/stats/orderCountByCategory'); ?>', { start_date: $('#startDate').val(), end_date: $('#endDate').val() }, function(data){
                data.code === 0 && initOrderCountByCategoryCharts(data.data) && $('#order-count-stats').fadeIn();
            });
        }

         var $highcharts = $('#sales-count-stats').highcharts();
         $highcharts && $highcharts.destroy();
         //$('.highcharts-div').hide();
         loadSalesCountByCategoryData();

         $highcharts = $('#order-count-stats').highcharts();
         $highcharts && $highcharts.destroy();
         //$('.highcharts-div').hide();
         loadOrderCountByCategoryData();
    });
</script>