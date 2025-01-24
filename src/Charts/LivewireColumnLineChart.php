<?php

namespace Asantibanez\LivewireCharts\Charts;

use Asantibanez\LivewireCharts\Models\ColumnLineChartModel;
use Livewire\Component;

/**
 * Class LivewireColumnLineChart
 * @package Asantibanez\LivewireCharts\Charts
 */
class LivewireColumnLineChart extends Component
{
    public $columnLineChartModel;

    public function mount(ColumnLineChartModel $columnLineChartModel)
    {
        $this->columnLineChartModel = $columnLineChartModel->toArray();
    }

    public function onColumnClick($column)
    {
        $onColumnClickEventName = data_get($this->columnLineChartModel, 'onColumnClickEventName', null);

        if ($onColumnClickEventName === null) {
            return;
        }

        $this->dispatch($onColumnClickEventName, $column);
    }

    public function render()
    {
        return view('livewire-charts::livewire-multi-column-multi-line-chart');
    }
}
