@extends('layout.navigation')

@section('title','Patient Record')
@section('main-content')
<div class="max-w-5xl mx-auto p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-bold mb-6">Patient Information Record</h2>

    <form action="{{ route('patient-records') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Personal Information -->
        <div>
            <h4 class="text-lg font-semibold mb-4">Personal Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="last_name" placeholder="Last Name" required class="input">
                <input type="text" name="first_name" placeholder="First Name" required class="input">
                <input type="text" name="middle_name" placeholder="Middle Name" class="input">
                <input type="date" name="birthdate" class="input">
                <select name="sex" class="input">
                    <option value="">Select Sex</option>
                    <option value="M">Male</option>
                    <option value="F">Female</option>
                </select>
                <input type="text" name="nationality" placeholder="Nationality" class="input">
                <input type="text" name="religion" placeholder="Religion" class="input">
                <input type="text" name="occupation" placeholder="Occupation" class="input">
                <input type="text" name="home_address" placeholder="Home Address" class="input col-span-2">
                <input type="text" name="office_address" placeholder="Office Address" class="input col-span-2">
                <input type="text" name="contact_number" placeholder="Contact Number" class="input">
                <input type="email" name="email" placeholder="Email" class="input col-span-2">
            </div>
        </div>

        <!-- Dental History -->
        <div>
            <h4 class="text-lg font-semibold mb-4">Dental History</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="referred_by" placeholder="Referred By" class="input">
                <input type="text" name="reason_for_consultation" placeholder="Reason for Consultation" class="input">
                <input type="text" name="previous_dentist" placeholder="Previous Dentist" class="input">
                <input type="text" name="last_dental_visit" placeholder="Last Dental Visit" class="input">
            </div>
        </div>

        <!-- Medical History -->
        <div>
            <h4 class="text-lg font-semibold mb-4">Medical History</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="physician_name" placeholder="Physician Name" class="input">
                <input type="text" name="physician_specialty" placeholder="Specialty" class="input">
                <input type="text" name="physician_contact" placeholder="Contact Number" class="input">
            </div>

            <div class="mt-4 space-y-2">
                <h4 class="text-lg font-semibold mb-4">
                    Do you have or have you had any of the following? (Check which apply)
                            </h4>
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
                    <input type="checkbox" name="{{ $field }}" value="1" class="checkbox">
                    <span>{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Blood Type -->
        <div>
            <h4 class="text-lg font-semibold mb-4">Blood Type</h4>
            <input type="text" name="blood_type" placeholder="Blood Type" class="input w-40">
        </div>

        <!-- Health Conditions -->
        <div>
            <h4 class="text-lg font-semibold mb-4">Health Conditions</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                @foreach(['High Blood Pressure','Heart Disease','Diabetes','Cancer'] as $condition)
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="health_conditions[]" value="{{ $condition }}" class="checkbox">
                    <span>{{ $condition }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Medical Conditions -->
        <div>
            <h4 class="text-lg font-semibold mb-4">
    Do you have or have you had any of the following? (Check which apply)
            </h4>
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
                    <input type="checkbox" name="medical_conditions[]" value="{{ $condition }}" class="checkbox">
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

@endsection

<!-- Tailwind helper classes -->
@push('styles')
<style>
    .input {
        @apply w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none;
    }
    .checkbox {
        @apply w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500;
    }
</style>
@endpush
