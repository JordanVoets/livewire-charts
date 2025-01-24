<?php


namespace Asantibanez\LivewireCharts\Models;

/**
 * Class CombinationChartModel
 * @package Asantibanez\LivewireCharts\Models
 * @property boolean $isMultiColumn
 * @property boolean $isStacked
 */
class CombinationChartModel extends BaseChartModel
{
    private $opacity;

    private $columnWidth;

    private $isStacked;

    private $onColumnClickEventName;

    private $onPointClickEventName;

    private $data;

    public function __construct()
    {
        parent::__construct();

        $this->onColumnClickEventName = null;

        $this->onPointClickEventName = null;

        $this->opacity = 0.75;

        $this->columnWidth = null;

        $this->isStacked = false;

        $this->data = collect();
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
     * Adds a data series to the chart.
     *
     * @param string $seriesName The name of the series.
     * @param string $type The type of the series.
     * @param string $title The title of the series.
     * @param mixed $value The value of the column.
     * @param array $extras Optional additional data for the column.
     * @return $this
     */
    public function addSeries($seriesName, $type, $title, $value, $extras = [])
    {
        $series = $this->data->get($seriesName, collect());

        $series->push([
            'seriesName' => $seriesName,
            'type' => $type,
            'title' => $title,
            'value' => $value,
            'extras' => $extras,
        ]);

        $this->data->put($seriesName, $series);

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
            'data' => $this->data->toArray()
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

        $this->data = collect(data_get($array, 'data', []));
    }
}
