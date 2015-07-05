define(['jquery', 'mylibs', 'highchart', 'qtip'], function($, mylibs, chart, qtip) {
    var Methods;
    return Methods = {
        add_chart: function() {
            $(document).ready(function(){

            });


            $('.vgtablegraph').qtip({
                content: {
                    text: function(e) {
                        $('.chart').highcharts({
                            chart: {
                                plotBackgroundColor: null,
                                plotBorderWidth: null,
                                plotShadow: false,
                                type: 'pie'
                            },
                            title: {
                                text: 'Volume Group'
                            },
                            tooltip: {
                                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                            },
                            plotOptions: {
                                pie: {
                                    allowPointSelect: true,
                                    cursor: 'pointer',
                                    dataLabels: {
                                        enabled: true,
                                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                        style: {
                                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                        }
                                    }
                                }
                            },
                            series: [{
                                name : 'Random data',
                                data : (function () {
                                    var $this = $(this);
                                    var vgname = $this.closest('tr').find('.vgname').text();

                                    // generate an array of random data
                                    //var data = [], time = (new Date()).getTime(), i;

                                    //for (i = -999; i <= 0; i += 1) {
                                    //    data.push([
                                    //        time + i * 1000,
                                     ///       Math.round(Math.random() * 100)
                                     //   ]);
                                   //}
                                    return "test";
                                }())
                            }]
                        });
                    }
                },
                style: {
                    classes: 'qtip-youtube chart',
                    height: 370,
                    width: 625
                },
                position: {
                    target: 'mouse'
                }
            });
        }
    };
});