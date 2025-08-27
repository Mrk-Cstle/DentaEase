<?php

namespace App\Livewire;

use App\Models\DentalChart;
use App\Models\User;
use Livewire\Component;

class DentalChartBoard extends Component
{
    public $patient;
    public $chart = [];
    public function mount(User $patient)
    {
        $this->patient = $patient;

        // load chart as array for Livewire binding
        $this->chart = DentalChart::firstOrCreate([
            'patient_id' => $patient->id,
        ])->toArray();
    }

   public function updated($propertyName, $value)
{
  

    $data = collect($this->chart)
        ->only((new DentalChart)->getFillable())
        ->toArray();

    $data['patient_id'] = $this->patient->id;

    DentalChart::updateOrCreate(
        ['patient_id' => $this->patient->id],
        $data
    );
}
    public function render()
    {
        return view('livewire.dental-chart-board');
    }
}
