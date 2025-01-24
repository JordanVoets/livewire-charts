import { mergedOptionsWithJsonConfig } from "./helpers";

const multiColumnMultiLineChart = () => {
    return {
        chart: null,

        init() {
            setTimeout(() => {
                this.drawChart(this.$wire)
            }, 0);
        },

        drawChart(component) {
            if (this.chart) {
                this.chart.destroy()
            }

            const title = component.get("columnLineChartModel.title");
            const stacked = component.get("columnLineChartModel.isStacked");
            const animated = component.get("columnLineChartModel.animated") || false;
            const dataLabels = component.get("columnLineChartModel.dataLabels") || {};
            const columnsData = component.get("columnLineChartModel.columnsData") || [];
            const linesData = component.get("columnLineChartModel.linesData") || [];
            const onPointClickEventName = component.get("columnLineChartModel.onPointClickEventName",);
            const onColumnClickEventName = component.get("columnLineChartModel.onColumnClickEventName");
            const sparkline = component.get("columnLineChartModel.sparkline");
            const legend = component.get('columnLineChartModel.legend');
            const grid = component.get('columnLineChartModel.grid');
            const columnWidth = component.get('columnLineChartModel.columnWidth');
            const jsonConfig = component.get("columnLineChartModel.jsonConfig");

            const series = Object.keys(columnsData).map(function (seriesName) {
              return {
                  name: seriesName,
                  type: "column",
                  data: columnsData[seriesName].map(function (item) {
                    const date = Date.parse(item.title)

                    if (isNaN(date)) {
                      return { x: item.title, y: item.value }
                    }
                    else {
                      return { x: date, y: item.value }
                    }
                })
              }
            }).concat(
            Object.keys(linesData).map(function (seriesName) {
                return {
                  name: seriesName,
                  type: "line",
                  data: linesData[seriesName].map(function (item) {
                    const date = Date.parse(item.title)

                    if (isNaN(date)) {
                      return { x: item.title, y: item.value }
                    }
                    else {
                      return { x: date, y: item.value }
                    }
                })
                }
              })
            )

            const columnTitles = component.get("columnLineChartModel.xAxis.categories").length > 0
              ? component.get("columnLineChartModel.xAxis.categories")
              : columnsData[series[0].name] && columnsData[series[0].name].length > 0
              ? columnsData[series[0].name].map(function (item) {
                const date = Date.parse(item.title)

                if (isNaN(date)) {
                  return item.title
                }
                else {
                  return date
                }
              })
              : [];

            const lineTitles = component.get("columnLineChartModel.xAxis.categories").length > 0
              ? component.get("columnLineChartModel.xAxis.categories")
              : linesData[series[0].name] && linesData[series[0].name].length > 0
              ? linesData[series[0].name].map(function (item) {
                const date = Date.parse(item.title)

                if (isNaN(date)) {
                  return item.title
                }
                else {
                  return date
                }
              })
              : [];

            const categories = component.get("columnLineChartModel.xAxis.categories").length > 0
              ? component.get("columnLineChartModel.xAxis.categories")
              : columnTitles.sort((a, b) => a - b);

            const options = {
                series: series,

                chart: {
                    stacked: stacked,

                    ...sparkline,

                    toolbar: { show: false },

                    animations: { enabled: animated },

                    zoom: { enabled: false },

                    events: {
                        dataPointSelection: function(event, chartContext, {seriesIndex, dataPointIndex}) {
                            if (!onColumnClickEventName) {
                                return
                            }

                            const column = data[series[seriesIndex].name][dataPointIndex]
                            component.call('onColumnClick', column)
                        },
                          markerClick: function(event, chartContext, { dataPointIndex }) {
                            if (!onPointClickEventName) {
                                return
                            }

                            const point = data[dataPointIndex]
                            component.call('onPointClick', point)
                        }
                    }
                },

                legend: legend,

                grid: grid,

                plotOptions: columnWidth != null ? {
                    bar: {
                        columnWidth: `${columnWidth}%`,
                    },
                } : {},

                dataLabels: dataLabels,

                stroke: component.get("columnLineChartModel.stroke") || {},

                theme: component.get("columnLineChartModel.theme") || {},

                title: {
                    text: title,
                    
                    align: "center",
                },

                xaxis: {
                    labels: component.get("columnLineChartModel.xAxis.labels"),

                    type: categories.every(function (item) {return !isNaN(item);}) ? "datetime" : "category"
                },

                yaxis: component.get("columnLineChartModel.yAxis") || {},

                fill: {
                  opacity: component.get('columnLineChartModel.opacity'),
                },

                theme: component.get('columnLineChartModel.theme') || {},

            };

            const colors = component.get("columnLineChartModel.colors");

            if (colors && colors.length > 0) {
              options['colors'] = colors
            }

            this.chart = new ApexCharts(this.$refs.container, mergedOptionsWithJsonConfig(options, jsonConfig));
            this.chart.render();
        }
    }
}

export default multiColumnMultiLineChart
