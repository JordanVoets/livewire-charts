<?php


namespace Asantibanez\LivewireCharts\Models;

/**
 * Class ColumnLineChartModel
 * @package Asantibanez\LivewireCharts\Models
 * @property boolean $isMultiColumn
 * @property boolean $isStacked
 */
class ColumnLineChartModel extends BaseChartModel
{
    private $opacity;

    private $columnWidth;

    private $isStacked;

    private $onColumnClickEventName;

    private $onPointClickEventName;

    private $columnsData;

    private $linesData;

    public function __construct()
    {
        parent::__construct();

        $this->onColumnClickEventName = null;

        $this->onPointClickEventName = null;

        $this->opacity = 0.75;

        $this->columnWidth = null;

        $this->isStacked = false;

        $this->columnsData = collect();

        $this->linesData = collect();
    }

    public function stacked()
    {
        $this->isStacked = true;

        return $this;
    }

    public function setColumnWidth($value)
    {
        $this->columnWidth = $value;

        return $this;
    }

    public function setOpacity($opacity)
    {
        $this->opacity = $opacity;

        return $this;
    }

    public function withOnColumnClickEventName($onColumnClickEventName)
    {
        $this->onColumnClickEventName = $onColumnClickEventName;

        return $this;
    }

    public function withOnPointClickEventName($onPointClickEventName)
    {
        $this->onPointClickEventName = $onPointClickEventName;

        return $this;
    }

    /**
     * Adds a column to the chart.
     *
     * @param string $seriesName The name of the series.
     * @param string $title The title of the column.
     * @param mixed $value The value of the column.
     * @param array $extras Optional additional data for the column.
     * @return $this
     */
    public function addSeriesColumn($seriesName, $title, $value, $extras = [])
    {
        $series = $this->columnsData->get($seriesName, collect());

        $series->push([
            'seriesName' => $seriesName,
            'title' => $title,
            'value' => $value,
            'extras' => $extras,
        ]);

        $this->columnsData->put($seriesName, $series);

        return $this;
    }

    /**
     * Adds a line point to chart.
     *
     * @param string $seriesName The name of the series to which the point will be added.
     * @param string $title The key for the point.
     * @param mixed $value The value of the point.
     * @param array $extras Optional. Additional data or attributes for the point.
     *
     * @return $this
     */
    public function addSeriesPoint($seriesName, $title, $value, $extras = [])
    {
        $series = $this->linesData->get($seriesName, collect());

        $series->push([
            'seriesName' => $seriesName,
            'title' => $title,
            'value' => $value,
            'extras' => $extras,
        ]);

        $this->linesData->put($seriesName, $series);

        return $this;
    }


    public function toArray()
    {
        $array = array_merge(parent::toArray(), [
            'onColumnClickEventName' => $this->onColumnClickEventName,
            'onPointClickEventName' => $this->onPointClickEventName,
            'opacity' => $this->opacity,
            'columnWidth' => $this->columnWidth,
            'isStacked' => $this->isStacked,
            'columnsData' => $this->columnsData->toArray(),
            'linesData' => $this->linesData->toArray(),
        ]);
        return $array;
    }

    public function fromArray($array)
    {
        parent::fromArray($array);

        $this->onColumnClickEventName = data_get($array, 'onColumnClickEventName', null);

        $this->onPointClickEventName = data_get($array, 'onPointClickEventName', null);

        $this->opacity = data_get($array, 'opacity', 0.5);

        $this->columnWidth = data_get($array, 'columnWidth');

        $this->isStacked = data_get($array, 'isStacked', false);

        $this->columnsData = collect(data_get($array, 'columnsData', []));

        $this->linesData = collect(data_get($array, 'linesData', []));
    }
}
