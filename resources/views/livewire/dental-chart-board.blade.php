<div class="space-y-6">

    <!-- Temporary Upper Teeth -->
    <div>
        <h3 class="text-sm font-bold mb-2 text-center">Temporary Upper Teeth</h3>
        <div class="grid grid-cols-10 gap-1 border border-gray-400 p-1">
            @foreach (['55','54','53','52','51','61','62','63','64','65'] as $tooth)
                <div class="flex flex-col items-center">
                    <select wire:model.change="chart.tooth_{{ $tooth }}_condition"
                            class="w-12 h-8 border border-gray-400 text-xs rounded">
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
                            class="w-12 h-8 border border-gray-400 text-xs rounded">
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
                            class="w-12 h-8 border border-gray-400 text-xs rounded">
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
                            class="w-12 h-8 border border-gray-400 text-xs rounded">
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
                            class="w-12 h-8 border border-gray-400 text-xs rounded">
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
                            class="w-12 h-8 border border-gray-400 text-xs rounded">
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
                            class="w-12 h-8 border border-gray-400 text-xs rounded">
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
                            class="w-12 h-8 border border-gray-400 text-xs rounded">
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

    <div class="mt-6 space-y-4">

        <!-- Periodontal Screening -->
        <h3 class="text-sm font-bold">Periodontal Screening</h3>
        <div class="grid grid-cols-2 gap-2">
            <label>
                <input type="checkbox" wire:model.change="chart.gingivitis"> Gingivitis
            </label>
            <label>
                <input type="checkbox" wire:model.change="chart.early_periodontitis"> Early Periodontitis
            </label>
            <label>
                <input type="checkbox" wire:model.change="chart.moderate_periodontitis"> Moderate Periodontitis
            </label>
            <label>
                <input type="checkbox" wire:model.change="chart.advanced_periodontitis"> Advanced Periodontitis
            </label>
        </div>
    
        <!-- Occlusion -->
        <h3 class="text-sm font-bold mt-4">Occlusion</h3>
        <div class="grid grid-cols-2 gap-2">
            <label>
                <input type="checkbox" wire:model.change="chart.occlusion_class_molar"> Class Molar
            </label>
            <label>
                <input type="checkbox" wire:model.change="chart.overjet"> Overjet
            </label>
            <label>
                <input type="checkbox" wire:model.change="chart.overbite"> Overbite
            </label>
            <label>
                <input type="checkbox" wire:model.change="chart.midline_deviation"> Midline Deviation
            </label>
            <label>
                <input type="checkbox" wire:model.change="chart.crossbite"> Crossbite
            </label>
        </div>
    
        <!-- Appliances -->
        <h3 class="text-sm font-bold mt-4">Appliances</h3>
        <div class="grid grid-cols-2 gap-2">
            <label>
                <input type="checkbox" wire:model.change="chart.appliance_orthodontic"> Orthodontic
            </label>
            <label>
                <input type="checkbox" wire:model.change="chart.appliance_stayplate"> Stayplate
            </label>
            {{-- <label>
                <input type="checkbox" wire:model.change="chart.appliance_others"> Others
            </label> --}}
        </div>
    
        <!-- TMD -->
        <h3 class="text-sm font-bold mt-4">TMD</h3>
        <div class="grid grid-cols-2 gap-2">
            <label>
                <input type="checkbox" wire:model.change="chart.tmd_clenching"> Clenching
            </label>
            <label>
                <input type="checkbox" wire:model.change="chart.tmd_clicking"> Clicking
            </label>
            <label>
                <input type="checkbox" wire:model.change="chart.tmd_trismus"> Trismus
            </label>
            <label>
                <input type="checkbox" wire:model.change="chart.tmd_muscle_spasm"> Muscle Spasm
            </label>
        </div>
    
    </div>
    
</div>
   