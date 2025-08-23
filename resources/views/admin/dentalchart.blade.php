@extends('layout.navigation')

@section('title','New User Verification')
@section('main-content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Dental Chart</h1>
                    <p class="text-gray-600">Patient: {{ $dentalChart->patient->name ?? 'N/A' }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('dental-charts.show', $dentalChart->id) }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        View Chart
                    </a>
                    <a href="{{ route('dental-charts.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Back to List
                    </a>
                </div>
            </div>

            <form action="{{ route('dental-charts.update', $dentalChart->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Permanent Teeth Chart --}}
                <div class="mb-8">
                    <h2 class="text-lg font-semibold mb-4 text-center">PERMANENT TEETH</h2>
                    <div class="dental-chart bg-gray-50 p-6 rounded-lg">
                        {{-- Upper Teeth --}}
                        <div class="upper-teeth mb-8">
                            <div class="flex justify-center mb-4">
                                <span class="text-sm font-semibold text-gray-600">UPPER</span>
                            </div>
                            
                            <div class="flex justify-center space-x-8">
                                {{-- Upper Right --}}
                                <div class="upper-right">
                                    <div class="text-center mb-2">
                                        <span class="text-xs text-gray-500">RIGHT</span>
                                    </div>
                                    <div class="flex space-x-1">
                                        @foreach($toothLayout['permanent']['upper_right'] as $toothNum)
                                            <div class="tooth-container w-12">
                                                <div class="tooth-number text-xs text-center mb-1">{{ $toothNum }}</div>
                                                @include('dental-charts.tooth-input', ['toothNumber' => $toothNum, 'dentalChart' => $dentalChart])
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Upper Left --}}
                                <div class="upper-left">
                                    <div class="text-center mb-2">
                                        <span class="text-xs text-gray-500">LEFT</span>
                                    </div>
                                    <div class="flex space-x-1">
                                        @foreach($toothLayout['permanent']['upper_left'] as $toothNum)
                                            <div class="tooth-container w-12">
                                                <div class="tooth-number text-xs text-center mb-1">{{ $toothNum }}</div>
                                                @include('dental-charts.tooth-input', ['toothNumber' => $toothNum, 'dentalChart' => $dentalChart])
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Separator --}}
                        <div class="separator border-t-2 border-gray-400 my-6"></div>

                        {{-- Lower Teeth --}}
                        <div class="lower-teeth">
                            <div class="flex justify-center space-x-8">
                                {{-- Lower Left --}}
                                <div class="lower-left">
                                    <div class="flex space-x-1">
                                        @foreach($toothLayout['permanent']['lower_left'] as $toothNum)
                                            <div class="tooth-container w-12">
                                                @include('dental-charts.tooth-input', ['toothNumber' => $toothNum, 'dentalChart' => $dentalChart])
                                                <div class="tooth-number text-xs text-center mt-1">{{ $toothNum }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="text-center mt-2">
                                        <span class="text-xs text-gray-500">LEFT</span>
                                    </div>
                                </div>

                                {{-- Lower Right --}}
                                <div class="lower-right">
                                    <div class="flex space-x-1">
                                        @foreach($toothLayout['permanent']['lower_right'] as $toothNum)
                                            <div class="tooth-container">
                                                @include('dental-charts.tooth-input', ['toothNumber' => $toothNum, 'dentalChart' => $dentalChart])
                                                <div class="tooth-number text-xs text-center mt-1">{{ $toothNum }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="text-center mt-2">
                                        <span class="text-xs text-gray-500">RIGHT</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-center mt-4">
                                <span class="text-sm font-semibold text-gray-600">LOWER</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Temporary Teeth Chart --}}
                <div class="mb-8">
                    <h2 class="text-lg font-semibold mb-4 text-center">TEMPORARY TEETH</h2>
                    <div class="dental-chart bg-blue-50 p-6 rounded-lg">
                        {{-- Upper Temporary Teeth --}}
                        <div class="upper-teeth mb-8">
                            <div class="flex justify-center mb-4">
                                <span class="text-sm font-semibold text-blue-600">UPPER</span>
                            </div>
                            
                            <div class="flex justify-center space-x-8">
                                {{-- Upper Right Temporary --}}
                                <div class="upper-right">
                                    <div class="text-center mb-2">
                                        <span class="text-xs text-blue-500">RIGHT</span>
                                    </div>
                                    <div class="flex space-x-1">
                                        @foreach($toothLayout['temporary']['upper_right'] as $toothNum)
                                            <div class="tooth-container w-12">
                                                <div class="tooth-number text-xs text-center mb-1">{{ $toothNum }}</div>
                                                @include('dental-charts.tooth-input', ['toothNumber' => $toothNum, 'dentalChart' => $dentalChart])
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Upper Left Temporary --}}
                                <div class="upper-left">
                                    <div class="text-center mb-2">
                                        <span class="text-xs text-blue-500">LEFT</span>
                                    </div>
                                    <div class="flex space-x-1">
                                        @foreach($toothLayout['temporary']['upper_left'] as $toothNum)
                                            <div class="tooth-container w-12">
                                                <div class="tooth-number text-xs text-center mb-1">{{ $toothNum }}</div>
                                                @include('dental-charts.tooth-input', ['toothNumber' => $toothNum, 'dentalChart' => $dentalChart])
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Separator --}}
                        <div class="separator border-t-2 border-blue-400 my-6"></div>

                        {{-- Lower Temporary Teeth --}}
                        <div class="lower-teeth">
                            <div class="flex justify-center space-x-8">
                                {{-- Lower Left Temporary --}}
                                <div class="lower-left">
                                    <div class="flex space-x-1">
                                        @foreach($toothLayout['temporary']['lower_left'] as $toothNum)
                                            <div class="tooth-container w-12">
                                                @include('dental-charts.tooth-input', ['toothNumber' => $toothNum, 'dentalChart' => $dentalChart])
                                                <div class="tooth-number text-xs text-center mt-1">{{ $toothNum }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="text-center mt-2">
                                        <span class="text-xs text-blue-500">LEFT</span>
                                    </div>
                                </div>

                                {{-- Lower Right Temporary --}}
                                <div class="lower-right">
                                    <div class="flex space-x-1">
                                        @foreach($toothLayout['temporary']['lower_right'] as $toothNum)
                                            <div class="tooth-container w-12">
                                                @include('dental-charts.tooth-input', ['toothNumber' => $toothNum, 'dentalChart' => $dentalChart])
                                                <div class="tooth-number text-xs text-center mt-1">{{ $toothNum }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="text-center mt-2">
                                        <span class="text-xs text-blue-500">RIGHT</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-center mt-4">
                                <span class="text-sm font-semibold text-blue-600">LOWER</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Additional Assessments --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    {{-- Periodontal Screening --}}
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <h3 class="font-semibold mb-3 text-yellow-800">Periodontal Screening</h3>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="periodontal[gingivitis]" value="1" 
                                       {{ $dentalChart->gingivitis ? 'checked' : '' }} class="mr-2">
                                <span class="text-sm">Gingivitis</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="periodontal[early_periodontitis]" value="1" 
                                       {{ $dentalChart->early_periodontitis ? 'checked' : '' }} class="mr-2">
                                <span class="text-sm">Early Periodontitis</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="periodontal[moderate_periodontitis]" value="1" 
                                       {{ $dentalChart->moderate_periodontitis ? 'checked' : '' }} class="mr-2">
                                <span class="text-sm">Moderate Periodontitis</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="periodontal[advanced_periodontitis]" value="1" 
                                       {{ $dentalChart->advanced_periodontitis ? 'checked' : '' }} class="mr-2">
                                <span class="text-sm">Advanced Periodontitis</span>
                            </label>
                        </div>
                    </div>

                    {{-- Occlusion --}}
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h3 class="font-semibold mb-3 text-green-800">Occlusion</h3>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="occlusion[occlusion_class_molar]" value="1" 
                                       {{ $dentalChart->occlusion_class_molar ? 'checked' : '' }} class="mr-2">
                                <span class="text-sm">Class Molar</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="occlusion[overjet]" value="1" 
                                       {{ $dentalChart->overjet ? 'checked' : '' }} class="mr-2">
                                <span class="text-sm">Overjet</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="occlusion[overbite]" value="1" 
                                       {{ $dentalChart->overbite ? 'checked' : '' }} class="mr-2">
                                <span class="text-sm">Overbite</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="occlusion[midline_deviation]" value="1" 
                                       {{ $dentalChart->midline_deviation ? 'checked' : '' }} class="mr-2">
                                <span class="text-sm">Midline Deviation</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="occlusion[crossbite]" value="1" 
                                       {{ $dentalChart->crossbite ? 'checked' : '' }} class="mr-2">
                                <span class="text-sm">Crossbite</span>
                            </label>
                        </div>
                    </div>

                    {{-- Appliances --}}
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <h3 class="font-semibold mb-3 text-purple-800">Appliances</h3>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="appliances[appliance_orthodontic]" value="1" 
                                       {{ $dentalChart->appliance_orthodontic ? 'checked' : '' }} class="mr-2">
                                <span class="text-sm">Orthodontic</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="appliances[appliance_stayplate]" value="1" 
                                       {{ $dentalChart->appliance_stayplate ? 'checked' : '' }} class="mr-2">
                                <span class="text-sm">Stayplate</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="appliances[appliance_others]" value="1" 
                                       {{ $dentalChart->appliance_others ? 'checked' : '' }} class="mr-2">
                                <span class="text-sm">Others</span>
                            </label>
                        </div>
                    </div>

                    {{-- TMD --}}
                    <div class="bg-red-50 p-4 rounded-lg">
                        <h3 class="font-semibold mb-3 text-red-800">TMD</h3>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="tmd[tmd_clenching]" value="1" 
                                       {{ $dentalChart->tmd_clenching ? 'checked' : '' }} class="mr-2">
                                <span class="text-sm">Clenching</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="tmd[tmd_clicking]" value="1" 
                                       {{ $dentalChart->tmd_clicking ? 'checked' : '' }} class="mr-2">
                                <span class="text-sm">Clicking</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="tmd[tmd_trismus]" value="1" 
                                       {{ $dentalChart->tmd_trismus ? 'checked' : '' }} class="mr-2">
                                <span class="text-sm">Trismus</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="tmd[tmd_muscle_spasm]" value="1" 
                                       {{ $dentalChart->tmd_muscle_spasm ? 'checked' : '' }} class="mr-2">
                                <span class="text-sm">Muscle Spasm</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Legend --}}
                <div class="legend mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="condition-legend">
                        <h3 class="text-lg font-semibold mb-3">Condition Codes</h3>
                        <div class="grid grid-cols-1 gap-1 text-sm">
                            @foreach(\App\Http\Controllers\DentalChartController::getConditionOptions() as $code => $description)
                                <div class="flex">
                                    <span class="font-mono font-bold w-8">{{ $code }}:</span>
                                    <span>{{ $description }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="treatment-legend">
                        <h3 class="text-lg font-semibold mb-3">Treatment Codes</h3>
                        <div class="grid grid-cols-1 gap-1 text-sm">
                            @foreach(\App\Http\Controllers\DentalChartController::getTreatmentOptions() as $code => $description)
                                <div class="flex">
                                    <span class="font-mono font-bold w-8">{{ $code }}:</span>
                                    <span>{{ $description }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Save Button --}}
                <div class="flex justify-center mt-8">
                    <button type="submit" class="px-6 py-3 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                        Save Dental Chart
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- All styling handled by Tailwind classes --}}

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-uppercase input
    const inputs = document.querySelectorAll('input[name*="teeth"]');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    });
});
</script>
@endsection

{{-- resources/views/dental-charts/tooth-input.blade.php --}}
<div class="tooth-inputs">
    {{-- Condition Input --}}
    <input type="text" 
           name="teeth[{{ $toothNumber }}][condition]" 
           value="{{ $dentalChart->{"tooth_{$toothNumber}_condition"} ?? '' }}" 
           class="w-12 h-8 border border-gray-300 text-center text-xs font-semibold rounded-sm mb-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
           maxlength="3"
           placeholder="Cond"
           title="Condition: ✓, D, M, MO, Im, Sp, Rf, Un">
    
    {{-- Treatment Input --}}
    <input type="text" 
           name="teeth[{{ $toothNumber }}][treatment]" 
           value="{{ $dentalChart->{"tooth_{$toothNumber}_treatment"} ?? '' }}" 
           class="w-12 h-8 border border-gray-300 text-center text-xs font-semibold rounded-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
           maxlength="3"
           placeholder="Treat"
           title="Treatment: Am, Co, JC, Ab, Att, P, In, Imp, S, Rm, X, XO">
</div>
@endsection
