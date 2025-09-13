{{-- @extends('layout.navigation')

@section('title','Patient Record')
@section('main-content') --}}

<div class="max-w-5xl mx-auto p-6 bg-white shadow-lg rounded-lg">

      @if (auth()->user()->account_type == 'admin')
         <button onclick="printDiv('printable-patient')" 
    class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 print:hidden">
    Print Patient Info
</button>
    @endif
   
    <h2 class="text-2xl font-bold mb-6">Patient Information Record</h2>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-200 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form id="patient-form" class="space-y-6">

        @csrf

        <!-- Personal Information -->
        <div>
            <div>
                <input value="{{$patientinfo->user_id}}" name="user_id" id="user_id" hidden>
    
                <p><strong>Name: </strong>
                    <span class="print-value">{{ $patientinfo->user->lastname }}, {{ $patientinfo->user->name }} {{ $patientinfo->user->middlename }} {{ $patientinfo->user->suffix ?? '' }}</span>
                   
                </p>
                
                <p><strong>Address: </strong>
                    <span class="print-value">{{ $patientinfo->user->current_address }}</span>
                  
                </p>
                
                <p><strong>Birthdate: </strong>
                    <span class="print-value">{{ $patientinfo->user->birth_date }}</span>
                   
                </p>
                
                <p><strong>Contact Number: </strong>
                    <span class="print-value">{{ $patientinfo->user->contact_number }}</span>
                   
                </p>
                
                <p><strong>Email: </strong>
                    <span class="print-value">{{ $patientinfo->user->email }}</span>
                  
                </p>
                
            </div>
            <input value="{{$patientinfo->user_id}}" name="user_id" id="user_id" hidden>
            {{-- <h4 class="text-lg font-semibold mb-4">Personal Information</h4> --}}
            {{-- <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="last_name" placeholder="Last Name" required class="input" 
                    value="{{ old('last_name', $patient->last_name ?? '') }}">
                <input type="text" name="first_name" placeholder="First Name" required class="input"
                    value="{{ old('first_name', $patient->first_name ?? '') }}">
                <input type="text" name="middle_name" placeholder="Middle Name" class="input"
                    value="{{ old('middle_name', $patient->middle_name ?? '') }}">
                <input type="date" name="birthdate" class="input"
                    value="{{ old('birthdate', isset($patient->birthdate) ? $patient->birthdate->format('Y-m-d') : '') }}">
                <select name="sex" class="input">
                    <option value="">Select Sex</option>
                    <option value="M" {{ old('sex', $patient->sex ?? '') == 'M' ? 'selected' : '' }}>Male</option>
                    <option value="F" {{ old('sex', $patient->sex ?? '') == 'F' ? 'selected' : '' }}>Female</option>
                </select>
                <input type="text" name="nationality" placeholder="Nationality" class="input"
                    value="{{ old('nationality', $patient->nationality ?? '') }}">
                <input type="text" name="religion" placeholder="Religion" class="input"
                    value="{{ old('religion', $patient->religion ?? '') }}">
                <input type="text" name="occupation" placeholder="Occupation" class="input"
                    value="{{ old('occupation', $patient->occupation ?? '') }}">
                <input type="text" name="home_address" placeholder="Home Address" class="input col-span-2"
                    value="{{ old('home_address', $patient->home_address ?? '') }}">
                <input type="text" name="office_address" placeholder="Office Address" class="input col-span-2"
                    value="{{ old('office_address', $patient->office_address ?? '') }}">
                <input type="text" name="contact_number" placeholder="Contact Number" class="input"
                    value="{{ old('contact_number', $patient->contact_number ?? '') }}">
                <input type="email" name="email" placeholder="Email" class="input col-span-2" required
                    value="{{ old('email', $patient->email ?? '') }}">
            </div> --}}
        </div>

        <!-- Dental History -->
        <div>
            <h4 class="text-lg font-semibold mb-4">Dental History</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="referred_by" placeholder="Referred By" class="input"
                    value="{{ old('referred_by', $patientinfo->referred_by ?? '') }}">
                <input type="text" name="reason_for_consultation" placeholder="Reason for Consultation" class="input"
                    value="{{ old('reason_for_consultation', $patientinfo->reason_for_consultation ?? '') }}">
                <input type="text" name="previous_dentist" placeholder="Previous Dentist" class="input"
                    value="{{ old('previous_dentist', $patientinfo->previous_dentist ?? '') }}">
                <input type="text" name="last_dental_visit" placeholder="Last Dental Visit" class="input"
                    value="{{ old('last_dental_visit', $patientinfo->last_dental_visit ?? '') }}">
            </div>
        </div>

        <!-- Medical History -->
        <div>
            <h4 class="text-lg font-semibold mb-4">Medical History</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="physician_name" placeholder="Physician Name" class="input"
                    value="{{ old('physician_name', $patientinfo->physician_name ?? '') }}">
                <input type="text" name="physician_specialty" placeholder="Specialty" class="input"
                    value="{{ old('physician_specialty', $patientinfo->physician_specialty ?? '') }}">
                <input type="text" name="physician_contact" placeholder="Contact Number" class="input"
                    value="{{ old('physician_contact', $patientinfo->physician_contact ?? '') }}">
            </div>

            <div class="mt-4 space-y-2">
                <h4 class="text-lg font-semibold mb-4">Do you have or have you had any of the following? (Check which apply)</h4>
                @foreach([
                    'in_good_health' => 'In good health?',
                    'under_treatment' => 'Under medical treatment?',
                    'had_illness_operation' => 'Had illness/operation?',
                    'hospitalized' => 'Ever hospitalized?',
                    'taking_medication' => 'Taking medication?',
                    'allergic' => 'Allergic to drugs/medicine?',
                    'bleeding_time' => 'Prolonged bleeding?',
                    'pregnant' => 'Pregnant?',
                    'nursing' => 'Nursing?',
                    'birth_control_pills' => 'Taking birth control pills?'
                ] as $field => $label)
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="{{ $field }}" value="1" class="checkbox" 
                        {{ old($field, $patientinfo->$field ?? false) ? 'checked' : '' }}>
                    <span>{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Blood Type -->
        <div>
            <h4 class="text-lg font-semibold mb-4">Blood Type</h4>
            <input type="text" name="blood_type" placeholder="Blood Type" class="input w-40"
                value="{{ old('blood_type', $patientinfo->blood_type ?? '') }}">
        </div>

        <!-- Health Conditions -->
        <div>
            <h4 class="text-lg font-semibold mb-4">Health Conditions</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                @foreach(['High Blood Pressure','Heart Disease','Diabetes','Cancer'] as $condition)
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="health_conditions[]" value="{{ $condition }}" class="checkbox"
                        {{ in_array($condition, old('health_conditions', $patientinfo->health_conditions ?? [])) ? 'checked' : '' }}>
                    <span>{{ $condition }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Medical Conditions -->
        <div>
            <h4 class="text-lg font-semibold mb-4">Do you have or have you had any of the following? (Check which apply)</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach([
                    "High Blood Pressure","Low Blood Pressure","Epilepsy / Convulsions",
                    "AIDS or HIV Infection","Sexually Transmitted Disease","Stomach Troubles / Ulcers",
                    "Fainting Spells","Rapid Weight Loss","Radiation Therapy","Joint Replacement / Implant",
                    "Heart Surgery","Heart Attack","Thyroid Problem","Heart Disease","Heart Murmur",
                    "Hepatitis / Liver Disease","Rheumatic Fever","Hay Fever / Allergies","Respiratory Problems",
                    "Hepatitis / Jaundice","Tuberculosis","Swollen Ankles","Kidney Disease","Diabetes",
                    "Chest Pain","Stroke","Cancer / Tumors","Anemia","Angina","Asthma","Emphysema",
                    "Bleeding Problems","Blood Diseases","Head Injuries","Arthritis / Rheumatism","Other"
                ] as $condition)
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="medical_conditions[]" value="{{ $condition }}" class="checkbox"
                        {{ in_array($condition, old('medical_conditions', $patientinfo->medical_conditions ?? [])) ? 'checked' : '' }}>
                    <span>{{ $condition }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Submit -->
        <div class="pt-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700">
                Save
            </button>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById('patient-form');

        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Stop normal form submission

            const formData = new FormData(form);

            // Send AJAX request using Axios
            axios.post("{{ route('patient-records') }}", formData)
                .then(function (response) {
                    if (response.data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Saved!',
                            text: response.data.message,
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(function (error) {
                    let message = 'Something went wrong.';
                    if (error.response && error.response.data && error.response.data.message) {
                        message = error.response.data.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: message,
                        confirmButtonText: 'OK'
                    });
                });
        });
    });
</script>

{{-- @endsection --}}

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .input {
        @apply w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none;
    }
    .checkbox {
        @apply w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500;
    }
</style>

@endpush
