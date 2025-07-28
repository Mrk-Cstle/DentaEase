@php
  $layout = auth()->user()->formstatus == false ? 'layout.auth' : 'layout.cnav';
  $section = auth()->user()->formstatus == false ? 'auth-content' : 'main-content';
@endphp

@extends($layout)

@section('title', 'Booking')

@section($section)
  <form action="{{ route('medical-form.store') }}" method="POST">
    @csrf

    <!-- MEDICAL HISTORY -->
    <div>
      @if (auth()->user()->formstatus == false )
          <h2 class="text-xl font-bold mb-4 flex items-center gap-2"><span>📜</span>Medical Form</h2>
      @else
      <h2 class="text-xl font-bold mb-4 flex items-center gap-2"><span>📜</span> Medical History Update</h2>
        
      @endif
   
      <div class="overflow-x-auto">
        <table class="min-w-full border text-sm">
          <thead class="bg-[#5D5CFF] text-white">
            <tr>
              <th class="px-4 py-2 text-left">Condition</th>
              <th class="px-4 py-2">Yes</th>
              <th class="px-4 py-2">No</th>
              <th class="px-4 py-2 text-left">Details</th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-t">
              <td class="px-4 py-2">Allergies</td>
              <td class="text-center"><input type="radio" name="allergies" value="1" required></td>
              <td class="text-center"><input type="radio" name="allergies" value="0" required></td>
              <td class="px-4 py-2"><input type="text" name="allergies_details" class="w-full p-1 border rounded" placeholder="Details..."></td>
            </tr>
            <tr class="border-t">
              <td class="px-4 py-2">Heart Condition</td>
              <td class="text-center"><input type="radio" name="heart_condition" value="1" required></td>
              <td class="text-center"><input type="radio" name="heart_condition" value="0" required></td>
              <td class="px-4 py-2"><input type="text" name="heart_condition_details" class="w-full p-1 border rounded" placeholder="Details..."></td>
            </tr>
            <tr class="border-t">
              <td class="px-4 py-2">Asthma</td>
              <td class="text-center"><input type="radio" name="asthma" value="1" required></td>
              <td class="text-center"><input type="radio" name="asthma" value="0" required></td>
              <td class="px-4 py-2"><input type="text" name="asthma_details" class="w-full p-1 border rounded" placeholder="Details..."></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- PAST SURGERIES -->
    <div>
      <h2 class="text-xl font-bold mb-4 flex items-center gap-2"><span>📄</span>PAST SURGERIES</h2>
      <div class="flex gap-4 mb-4">
        <label><input type="radio" name="had_surgeries" value="0" class="mr-2" required>No previous surgeries</label>
        <label><input type="radio" name="had_surgeries" value="1" class="mr-2" required>Yes, please list:</label>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full border text-sm">
          <thead class="bg-[#5D5CFF] text-white">
            <tr>
              <th class="px-4 py-2">Surgery Type</th>
              <th class="px-4 py-2">Date</th>
              <th class="px-4 py-2">Hospital/Clinic</th>
              <th class="px-4 py-2">Remarks</th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-t">
              <td class="px-2 py-2"><input type="text" name="surgery_type" class="w-full p-1 border rounded"></td>
              <td class="px-2 py-2"><input type="date" name="surgery_date" class="w-full p-1 border rounded"></td>
              <td class="px-2 py-2"><input type="text" name="surgery_location" class="w-full p-1 border rounded"></td>
              <td class="px-2 py-2"><input type="text" name="surgery_remarks" class="w-full p-1 border rounded"></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- CURRENT MEDICATIONS -->
    <div>
      <h2 class="text-xl font-bold mb-4 flex items-center gap-2"><span>💊</span>CURRENT MEDICATIONS</h2>
      <table class="min-w-full border text-sm">
        <thead class="bg-[#5D5CFF] text-white">
          <tr>
            <th class="px-4 py-2">Medication Name</th>
            <th class="px-4 py-2">Dosage</th>
            <th class="px-4 py-2">Reason</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-t">
            <td class="px-2 py-2"><input type="text" name="medication_name" class="w-full p-1 border rounded" placeholder="Ex. Aspirin"></td>
            <td class="px-2 py-2"><input type="text" name="medication_dosage" class="w-full p-1 border rounded" placeholder="81 mg daily"></td>
            <td class="px-2 py-2"><input type="text" name="medication_reason" class="w-full p-1 border rounded" placeholder="Blood thinner"></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- DENTAL HISTORY -->
    <div>
      <h2 class="text-xl font-bold mb-4 flex items-center gap-2"><span>🍩</span>DENTAL HISTORY</h2>
      <div class="space-y-4">
        <div>
          <label class="font-semibold">Reason for today’s visit</label>
          <input type="text" name="visit_reason" class="w-full p-2 border rounded" placeholder="Toothache on upper right molar">
        </div>
        <div>
          <label class="font-semibold">Last dental visit</label>
          <input type="date" name="last_dental_visit" class="w-full p-2 border rounded">
        </div>
        <div>
          <label class="font-semibold">Previous dental problems?</label>
          <div class="flex gap-4 mt-1">
            <label><input type="radio" name="had_dental_issues" value="1" class="mr-2">Yes</label>
            <label><input type="radio" name="had_dental_issues" value="0" class="mr-2">No</label>
          </div>
        </div>
        <div>
          <label class="font-semibold">If yes, please describe</label>
          <input type="text" name="dental_issue_description" class="w-full p-2 border rounded" placeholder="Root canal treatment in 2020">
        </div>
        <div>
          <label class="font-semibold">Do you experience dental anxiety?</label>
          <div class="flex gap-4 mt-1">
            <label><input type="radio" name="dental_anxiety" value="1" class="mr-2">Yes</label>
            <label><input type="radio" name="dental_anxiety" value="0" class="mr-2">No</label>
          </div>
        </div>
      </div>
    </div>

    <!-- EMERGENCY CONTACT -->
    <div>
      <h2 class="text-xl font-bold mb-4 flex items-center gap-2"><span>👥</span>EMERGENCY CONTACT</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block font-semibold">Name</label>
          <input type="text" name="emergency_name" class="w-full p-2 border rounded" required>
        </div>
        <div>
          <label class="block font-semibold">Relationship</label>
          <input type="text" name="emergency_relationship" class="w-full p-2 border rounded" required>
        </div>
        <div>
          <label class="block font-semibold">Contact Number</label>
          <input type="text" name="emergency_contact" class="w-full p-2 border rounded" required>
        </div>
      </div>
    </div>

    <!-- SUBMIT BUTTON -->
    <div class="text-right">
      @if (auth()->user()->formstatus == false)
            <a href="/logouts" class="bg-[#5D5CFF] hover:bg-indigo-700 text-white px-6 py-2 rounded mt-6"><i class="fa-solid fa-right-from-bracket mr-2"></i>Cancel</a>
      @endif
    
      <button type="submit" class="bg-[#5D5CFF] hover:bg-indigo-700 text-white px-6 py-2 rounded mt-6">Submit</button>
    </div>

  </form>
</div>
@endsection
