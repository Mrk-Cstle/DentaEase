<div class="space-y-6">

    <!-- Temporary Upper Teeth -->
    <div>
        <h3 class="text-sm font-bold mb-2 text-center">Temporary Upper Teeth</h3>
        <div class="grid grid-cols-10 gap-1 border border-gray-400 p-1">
            @foreach (['55','54','53','52','51','61','62','63','64','65'] as $tooth)
                <div class="flex flex-col items-center" >
                    <select wire:model.change="chart.tooth_{{ $tooth }}_condition"
                            class="w-12 h-8 border border-gray-400 text-xs rounded"  @if(auth()->user()->account_type == 'patient') disabled @endif>
                        <option value="">--</option>
                        <option value="✓">✓ Present Teeth</option>
                        <option value="D">D - Decayed</option>
                        <option value="M">M - Missing (Caries)</option>
                        <option value="MO">MO - Missing (Other)</option>
                        <option value="Im">Im - Impacted</option>
                        <option value="Sp">Sp - Supernumerary</option>
                        <option value="Rf">Rf - Root Fragment</option>
                        <option value="Un">Un - Unerupted</option>
                    </select>

                    <select wire:model.change="chart.tooth_{{ $tooth }}_treatment"
                            class="w-12 h-8 border border-gray-400 text-xs rounded" @if(auth()->user()->account_type == 'patient') disabled @endif>
                        <option value="">--</option>
                        <option value="Am">Am - Amalgam Filling</option>
                        <option value="Co">Co - Composite Filling</option>
                        <option value="JC">JC - Jacket Crown</option>
                        <option value="Ab">Ab - Abutment</option>
                        <option value="Att">Att - Attachment</option>
                        <option value="P">P - Pontic</option>
                        <option value="In">In - Inlay</option>
                        <option value="Imp">Imp - Implant</option>
                        <option value="S">S - Sealants</option>
                        <option value="Rm">Rm - Removable Denture</option>
                        <option value="X">X - Extraction (Caries)</option>
                        <option value="XO">XO - Extraction (Other)</option>
                    </select>

                    <span class="text-xs mt-1">{{ $tooth }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Permanent Teeth (Upper & Lower Mirror) -->
<!-- Permanent Teeth (Upper & Lower Mirror) -->
<div >
    <h3 class="text-sm font-bold mb-2 text-center">Permanent Upper Teeth</h3>

    <div class="flex flex-col ">

        <!-- Upper Permanent Teeth -->
        <div class="flex justify-center space-x-1 border border-gray-400 p-2 justify-between">
            @foreach (['18','17','16','15','14','13','12','11','21','22','23','24','25','26','27','28'] as $tooth)
                <div class="flex flex-col items-center">
                    <select wire:model.change="chart.tooth_{{ $tooth }}_condition"
                            class="w-12 h-8 border border-gray-400 text-xs rounded" @if(auth()->user()->account_type == 'patient') disabled @endif>
                        <option value="">--</option>
                        <option value="✓">✓ Present Teeth</option>
                        <option value="D">D - Decayed</option>
                        <option value="M">M - Missing (Caries)</option>
                        <option value="MO">MO - Missing (Other)</option>
                        <option value="Im">Im - Impacted</option>
                        <option value="Sp">Sp - Supernumerary</option>
                        <option value="Rf">Rf - Root Fragment</option>
                        <option value="Un">Un - Unerupted</option>
                    </select>
                    <select wire:model.change="chart.tooth_{{ $tooth }}_treatment"
                            class="w-12 h-8 border border-gray-400 text-xs rounded" @if(auth()->user()->account_type == 'patient') disabled @endif>
                        <option value="">--</option>
                        <option value="Am">Am - Amalgam Filling</option>
                        <option value="Co">Co - Composite Filling</option>
                        <option value="JC">JC - Jacket Crown</option>
                        <option value="Ab">Ab - Abutment</option>
                        <option value="Att">Att - Attachment</option>
                        <option value="P">P - Pontic</option>
                        <option value="In">In - Inlay</option>
                        <option value="Imp">Imp - Implant</option>
                        <option value="S">S - Sealants</option>
                        <option value="Rm">Rm - Removable Denture</option>
                        <option value="X">X - Extraction (Caries)</option>
                        <option value="XO">XO - Extraction (Other)</option>
                    </select>
                    <span class="text-xs mt-1">{{ $tooth }}</span>
                </div>
            @endforeach
        </div>
        <h3 class="text-sm font-bold mb-2 text-center my-5">Permanent Lower Teeth</h3>
        <!-- Lower Permanent Teeth (mirrored below upper row) -->
        <div class="flex justify-center space-x-1 border border-gray-400 p-2 justify-between ">
            @foreach (['48','47','46','45','44','43','42','41','31','32','33','34','35','36','37','38'] as $tooth)
                <div class="flex flex-col items-center">
                    <select wire:model.change="chart.tooth_{{ $tooth }}_condition"
                            class="w-12 h-8 border border-gray-400 text-xs rounded" @if(auth()->user()->account_type == 'patient') disabled @endif>
                        <option value="">--</option>
                        <option value="✓">✓ Present Teeth</option>
                        <option value="D">D - Decayed</option>
                        <option value="M">M - Missing (Caries)</option>
                        <option value="MO">MO - Missing (Other)</option>
                        <option value="Im">Im - Impacted</option>
                        <option value="Sp">Sp - Supernumerary</option>
                        <option value="Rf">Rf - Root Fragment</option>
                        <option value="Un">Un - Unerupted</option>
                    </select>
                    <select wire:model.change="chart.tooth_{{ $tooth }}_treatment"
                            class="w-12 h-8 border border-gray-400 text-xs rounded" @if(auth()->user()->account_type == 'patient') disabled @endif>
                        <option value="">--</option>
                        <option value="Am">Am - Amalgam Filling</option>
                        <option value="Co">Co - Composite Filling</option>
                        <option value="JC">JC - Jacket Crown</option>
                        <option value="Ab">Ab - Abutment</option>
                        <option value="Att">Att - Attachment</option>
                        <option value="P">P - Pontic</option>
                        <option value="In">In - Inlay</option>
                        <option value="Imp">Imp - Implant</option>
                        <option value="S">S - Sealants</option>
                        <option value="Rm">Rm - Removable Denture</option>
                        <option value="X">X - Extraction (Caries)</option>
                        <option value="XO">XO - Extraction (Other)</option>
                    </select>
                    <span class="text-xs mt-1">{{ $tooth }}</span>
                </div>
            @endforeach
        </div>

    </div>
</div>


    <!-- Temporary Lower Teeth -->
    <div>
        <h3 class="text-sm font-bold mb-2 text-center">Temporary Lower Teeth</h3>
        <div class="grid grid-cols-10 gap-1 border border-gray-400 p-1">
            @foreach (['85','84','83','82','81','71','72','73','74','75'] as $tooth)
                <div class="flex flex-col items-center">
                    <select wire:model.change="chart.tooth_{{ $tooth }}_condition"
                            class="w-12 h-8 border border-gray-400 text-xs rounded" @if(auth()->user()->account_type == 'patient') disabled @endif>
                        <option value="">--</option>
                        <option value="✓">✓ Present Teeth</option>
                        <option value="D">D - Decayed</option>
                        <option value="M">M - Missing (Caries)</option>
                        <option value="MO">MO - Missing (Other)</option>
                        <option value="Im">Im - Impacted</option>
                        <option value="Sp">Sp - Supernumerary</option>
                        <option value="Rf">Rf - Root Fragment</option>
                        <option value="Un">Un - Unerupted</option>
                    </select>

                    <select wire:model.change="chart.tooth_{{ $tooth }}_treatment"
                            class="w-12 h-8 border border-gray-400 text-xs rounded" @if(auth()->user()->account_type == 'patient') disabled @endif>
                        <option value="">--</option>
                        <option value="Am">Am - Amalgam Filling</option>
                        <option value="Co">Co - Composite Filling</option>
                        <option value="JC">JC - Jacket Crown</option>
                        <option value="Ab">Ab - Abutment</option>
                        <option value="Att">Att - Attachment</option>
                        <option value="P">P - Pontic</option>
                        <option value="In">In - Inlay</option>
                        <option value="Imp">Imp - Implant</option>
                        <option value="S">S - Sealants</option>
                        <option value="Rm">Rm - Removable Denture</option>
                        <option value="X">X - Extraction (Caries)</option>
                        <option value="XO">XO - Extraction (Other)</option>
                    </select>

                    <span class="text-xs mt-1">{{ $tooth }}</span>
                </div>
            @endforeach
        </div>
    </div>
    <div class="mt-6 p-4 border rounded-lg bg-gray-50 flex flex-col">
        <h3 class="text-sm font-bold mb-3">Legend</h3>
        <div class="flex flex-row  justify-between">
              <!-- Condition -->  
        <div class="mb-4">
            <h4 class="text-xs font-semibold mb-1">Condition</h4>
            <ul class="text-xs space-y-1">
                <li>✓ - Present Teeth</li>
                <li>D - Decayed (Caries Indicated for Filling)</li>
                <li>M - Missing due to Caries</li>
                <li>MO - Missing due to Other Causes</li>
                <li>Im - Impacted Tooth</li>
                <li>Sp - Supernumerary Tooth</li>
                <li>Rf - Root Fragment</li>
                <li>Un - Unerupted</li>
            </ul>
        </div>
    
        <!-- Restorations & Prosthetics -->
        <div class="mb-4">
            <h4 class="text-xs font-semibold mb-1">Restorations &amp; Prosthetics</h4>
            <ul class="text-xs space-y-1">
                <li>Am - Amalgam Filling</li>
                <li>Co - Composite Filling</li>
                <li>JC - Jacket Crown</li>
                <li>Ab - Abutment</li>
                <li>Att - Attachment</li>
                <li>P - Pontic</li>
                <li>In - Inlay</li>
                <li>Imp - Implant</li>
                <li>S - Sealants</li>
                <li>Rm - Removable Denture</li>
            </ul>
        </div>
    
        <!-- Surgery -->
        <div>
            <h4 class="text-xs font-semibold mb-1">Surgery</h4>
            <ul class="text-xs space-y-1">
                <li>X - Extraction due to Caries</li>
                <li>XO - Extraction due to Other Causes</li>
            </ul>
        </div>
        </div>
      
    </div>
    
    <div class="mt-6 space-y-4">

        <!-- Periodontal Screening -->
        <h3 class="text-sm font-bold">Periodontal Screening</h3>
        <div class="grid grid-cols-2 gap-2">
            <label>
                <input type="checkbox" wire:model.change="chart.gingivitis" @if(auth()->user()->account_type == 'patient') disabled @endif> Gingivitis
            </label>
            <label>
                <input type="checkbox" wire:model.change="chart.early_periodontitis" @if(auth()->user()->account_type == 'patient') disabled @endif> Early Periodontitis
            </label>
            <label>
                <input type="checkbox" wire:model.change="chart.moderate_periodontitis" @if(auth()->user()->account_type == 'patient') disabled @endif> Moderate Periodontitis
            </label>
            <label>
                <input type="checkbox" wire:model.change="chart.advanced_periodontitis" @if(auth()->user()->account_type == 'patient') disabled @endif> Advanced Periodontitis
            </label>
        </div>
    
        <!-- Occlusion -->
        <h3 class="text-sm font-bold mt-4">Occlusion</h3>
        <div class="grid grid-cols-2 gap-2">
            <label>
                <input type="checkbox" wire:model.change="chart.occlusion_class_molar" @if(auth()->user()->account_type == 'patient') disabled @endif> Class Molar
            </label>
            <label>
                <input type="checkbox" wire:model.change="chart.overjet" @if(auth()->user()->account_type == 'patient') disabled @endif> Overjet
            </label>
            <label>
                <input type="checkbox" wire:model.change="chart.overbite" @if(auth()->user()->account_type == 'patient') disabled @endif> Overbite
            </label>
            <label>
                <input type="checkbox" wire:model.change="chart.midline_deviation" @if(auth()->user()->account_type == 'patient') disabled @endif> Midline Deviation
            </label>
            <label>
                <input type="checkbox" wire:model.change="chart.crossbite" @if(auth()->user()->account_type == 'patient') disabled @endif> Crossbite
            </label>
        </div>
    
        <!-- Appliances -->
        <h3 class="text-sm font-bold mt-4">Appliances</h3>
        <div class="grid grid-cols-2 gap-2">
            <label>
                <input type="checkbox" wire:model.change="chart.appliance_orthodontic" @if(auth()->user()->account_type == 'patient') disabled @endif> Orthodontic
            </label>
            <label>
                <input type="checkbox" wire:model.change="chart.appliance_stayplate" @if(auth()->user()->account_type == 'patient') disabled @endif> Stayplate
            </label>
            {{-- <label>
                <input type="checkbox" wire:model.change="chart.appliance_others"> Others
            </label> --}}
        </div>
    
        <!-- TMD -->
        <h3 class="text-sm font-bold mt-4">TMD</h3>
        <div class="grid grid-cols-2 gap-2">
            <label>
                <input type="checkbox" wire:model.change="chart.tmd_clenching" @if(auth()->user()->account_type == 'patient') disabled @endif> Clenching
            </label>
            <label>
                <input type="checkbox" wire:model.change="chart.tmd_clicking" @if(auth()->user()->account_type == 'patient') disabled @endif> Clicking
            </label>
            <label>
                <input type="checkbox" wire:model.change="chart.tmd_trismus" @if(auth()->user()->account_type == 'patient') disabled @endif> Trismus
            </label>
            <label>
                <input type="checkbox" wire:model.change="chart.tmd_muscle_spasm" @if(auth()->user()->account_type == 'patient') disabled @endif> Muscle Spasm
            </label>
        </div>
    
    </div>
    
</div>
   