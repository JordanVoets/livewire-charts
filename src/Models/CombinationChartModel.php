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

    private $data;

    public function __construct()
    {
        parent::__construct();

        $this->opacity = 0.75;

        $this->data = collect();
    }

    public function stacked()
    {
        $this->isStacked = true;

        return $this;
    }

    public function setOpacity($opacity)
    {
        $this->opacity = $opacity;

        return $this;
    }

    public function addSeriesData($seriesName, $type, $title, $value)
    {
        $series = $this->data->get($seriesName, collect());

        $series->push([
            'seriesName' => $seriesName,
            'type' => $type,
            'title' => $title,
            'value' => $value,
        ]);

        $this->data->put($seriesName, $series);

        return $this;
    }


    public function toArray()
    {
        $array = array_merge(parent::toArray(), [
            'opacity' => $this->opacity,
            'isStacked' => $this->isStacked,
            'data' => $this->data->toArray()
        ]);
        return $array;
    }

    public function fromArray($array)
    {
        parent::fromArray($array);

        $this->opacity = data_get($array, 'opacity', 0.5);

        $this->isStacked = data_get($array, 'isStacked', false);

        $this->data = collect(data_get($array, 'data', []));
    }
}
