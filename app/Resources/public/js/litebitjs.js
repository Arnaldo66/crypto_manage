function significant(num, n) {
    if(num == 0) {
        return 0;
    }

    var d = Math.ceil(Math.log10(num < 0 ? -num: num));
    var power = n - d;

    var magnitude = Math.pow(10, power);
    var shifted = Math.round(num * magnitude);

    return shifted/magnitude;
}

function stock_graph(coin, translations) {

    $.getJSON('https://www.litebit.eu/cache/graphs/advanced/' + coin + '.json', function (data) {
        // Create the chart
        Highcharts.stockChart('graphcourse', {

            plotOptions : {
                line : {
                    states: {
                        hover: {
                            enabled: true,
                            lineWidth: 2
                        }
                    },
                    color: '#2196F3'
                }
            },
            chart: {
                zoomType: 'x',
                height: 400,
                type: 'line',
                backgroundColor: 'none',
                events: {
                    load: function(event) {

                        this.yAxis[0].removePlotLine('plot-avg');
                        this.yAxis[0].removePlotLine('plot-high');
                        this.yAxis[0].removePlotLine('plot-low');

                        var avg, high, low;
                        var rows = event.target.series[0].processedYData;
                        var sum = rows.reduce(function(a, b) { return a + b; });

                        avg = sum/rows.length;
                        high = event.target.yAxis[0].dataMax;
                        low = event.target.yAxis[0].dataMin;

                        this.yAxis[0].addPlotLine({
                            value: avg,
                            color: 'orange',
                            id: 'plot-avg',
                            dashStyle: 'shortdash',
                            width: 1,
                            label: {
                                text: translations.average + '<br /> € ' + significant(avg, 6),
                                align : 'right'
                            }
                        });

                        this.yAxis[0].addPlotLine({
                            value: high,
                            color: 'green',
                            id: 'plot-high',
                            dashStyle: 'shortdash',
                            width: 1,
                            label: {
                                text: translations.high + '<br /> € ' + significant(high, 6),
                                align : 'right'
                            }
                        });

                        this.yAxis[0].addPlotLine({
                            value: low,
                            color: 'red',
                            id: 'plot-low',
                            dashStyle: 'shortdash',
                            width: 1,
                            label: {
                                text: translations.low + '<br /> € ' + significant(low, 6),
                                align : 'right'
                            }
                        });

                    },
                    redraw: function(event) {

                        this.yAxis[0].removePlotLine('plot-avg');
                        this.yAxis[0].removePlotLine('plot-high');
                        this.yAxis[0].removePlotLine('plot-low');

                        var avg, high, low;
                        var rows = event.target.series[0].processedYData;
                        var sum = rows.reduce(function(a, b) { return a + b; });

                        avg = sum/rows.length;
                        high = event.target.yAxis[0].dataMax;
                        low = event.target.yAxis[0].dataMin;

                        this.yAxis[0].addPlotLine({
                            value: avg,
                            color: 'orange',
                            id: 'plot-avg',
                            dashStyle: 'shortdash',
                            width: 1,
                            label: {
                                text: translations.average + ' € ' + significant(avg, 6),
                                align : 'right'
                            }
                        });

                        this.yAxis[0].addPlotLine({
                            value: high,
                            color: 'green',
                            id: 'plot-high',
                            dashStyle: 'shortdash',
                            width: 1,
                            label: {
                                text: translations.high + ' € ' + significant(high, 6),
                                align : 'right'
                            }
                        });

                        this.yAxis[0].addPlotLine({
                            value: low,
                            color: 'red',
                            id: 'plot-low',
                            dashStyle: 'shortdash',
                            width: 1,
                            label: {
                                text: translations.low + ' € ' + significant(low, 6),
                                align : 'right'
                            }
                        });
                    }
                }
            },
            scrollbar: {
                enabled: false
            },
            yAxis: {
                opposite: false
            },
            legend: {
                enabled : false
            },
            xAxis: {
                type: 'datetime',
                ordinal: false,
                opposite: false,
                gridLineWidth: 0,
                gridZIndex: 0
            },
            tooltip: {
                headerFormat: '<strong>1 ' + coin + '</strong><br/>',
                pointFormat: '{point.x:%e %B %H:%M}  - {point.y:.8f} ',
                backgroundColor: '#fff',
                borderWidth: 0,
                color: '#000',
                borderRadius: 0
            },
            navigator: {
                series: {
                    lineColor: '#2196F3',
                    lineWidth: 2
                },
                type: 'spline',
                height: 30,
                maskFill: 'rgba(33, 150, 243, 0.2)'
            },
            rangeSelector: {
                selected: 3,
                inputEnabled: false,
                buttonTheme: { // styles for the buttons
                    stroke: 'none',
                    width: null,
                    padding: 6,
                    align: 'left',
                    style: {
                        color: '#4c4a4c',
                        fill: '#ccc8cb'
                    },
                    states: {
                        hover: {
                        },
                        select: {
                            fill: '#2196F3',
                            style: {
                                color: 'white'
                            }
                        }
                        // disabled: { ... }
                    }
                },
                labelStyle: {
                    display: 'none'
                },
                buttons: [{
                    type: 'hour',
                    count: 1,
                    text: translations.hour
                }, {
                    type: 'day',
                    count: 1,
                    text: translations.day
                }, {
                    type: 'week',
                    count: 1,
                    text: translations.week
                }, {
                    type: 'month',
                    count: 1,
                    text: translations.month
                }, {
                    type: 'year',
                    count: 1,
                    text: translations.year
                }, {
                    type: 'all',
                    text: translations.all
                }]
            },
            series: [{
                type: 'line',
                data: data,
                fillColor:  'rgba(33, 150, 243, 0.2)',
                color: '#2196f3',
                lineWidth: 2,
                tooltip: { valueDecimals: 8 }
            }]
        });
    });


}
