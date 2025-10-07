@extends('layout.navigation')

@section('title','Chat')
@section('main-content')
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <div class="flex h-screen">
    
    <!-- Sidebar (Patients) -->
    <div class="w-1/4 bg-sky-100 border-r border-sky-300 flex flex-col">
      <h2 class="text-lg font-bold p-4 bg-sky-300 text-white">Patients</h2>
      
      <!-- 🔎 Search bar -->
      <div class="p-2">
        <input id="patientSearch" type="text" placeholder="Search patient..."
          class="w-full border rounded-lg p-2 focus:outline-none focus:ring focus:ring-sky-400">
      </div>

      <ul id="patientList" class="flex-1 overflow-y-auto divide-y divide-sky-200"></ul>
    </div>
  
    <!-- Chat Window -->
    <div class="flex-1 flex flex-col bg-slate-300">
      <!-- Header -->
      <div id="chatHeader" class="p-4 bg-sky-300 text-white font-bold">Select a patient</div>
      
      <!-- Messages -->
      <div id="messagesBox" class="flex-1 overflow-y-auto p-4 space-y-3"></div>
      
      <!-- Input -->
      <div class="p-4 border-t border-sky-300 flex">
        <input id="messageInput" type="text" placeholder="Type a message..."
          class="flex-1 border rounded-lg p-2 focus:outline-none focus:ring focus:ring-sky-400">
        <button id="sendBtn" class="ml-2 bg-sky-500 text-white px-4 py-2 rounded-lg hover:bg-sky-600">
          Send
        </button>
      </div>
    </div>
  </div>


<script>
  let currentPatient = null;
  const currentStore = "{{ session('active_branch_id') }}"; // admin belongs to branch
  const authUserId = {{ auth()->id() }};
  let allPatients = [];

  // Load patient list
 function loadPatients() {
  fetch("{{ route('patients.list') }}")
    .then(res => res.json())
    .then(patients => {
      allPatients = patients;
      renderPatientList(patients);
    })
    .catch(err => console.error("Error loading patients:", err));
}

function renderPatientList(patients) {
  const patientList = document.getElementById("patientList");
  patientList.innerHTML = "";

  patients.forEach(patient => {
    // latest_message is now just a string
    let lastMsg = patient.latest_message || "No messages yet";
    let branchName = patient.branch_name ? ` (${patient.branch_name})` : "";
    let time = patient.latest_message_time ? ` • ${patient.latest_message_time}` : "";

    let li = document.createElement("li");
    li.className =
      "p-3 hover:bg-sky-200 cursor-pointer border-b border-gray-200 transition";

    if (currentPatient === patient.id) {
      li.classList.add("bg-sky-300");
    }

    // li.innerHTML = `
    //   <strong>${patient.full_name}</strong><br>
    //   <small class="text-gray-600">${lastMsg}${time}${branchName}</small>
    // `;
   li.innerHTML = `
      <strong>${patient.full_name}</strong><br>
      <small class="text-gray-600">${lastMsg}</small>
    `;
    li.onclick = () => {
      currentPatient = patient.id;
      document.getElementById("chatHeader").textContent = patient.full_name;
      loadMessages(currentStore, patient.id, patient.full_name);
    };

    patientList.appendChild(li);
  });
}


// ✅ Refresh both patients list and messages every 3 seconds
setInterval(() => {
  loadPatients(); // refresh patient list (includes latest message below name)

  if (currentPatient) {
    const currentName = document.getElementById("chatHeader").textContent;
    loadMessages(currentStore, currentPatient, currentName);
  }
}, 3000);

// ✅ Initial load
loadPatients();


  // Search patients
  document.getElementById("patientSearch").addEventListener("input", function() {
    const query = this.value.toLowerCase();
    const filtered = allPatients.filter(p => p.name.toLowerCase().includes(query));
    renderPatientList(filtered);
  });

  // Load messages for a patient
  function loadMessages(storeId, userId, patientName) {
    currentPatient = userId;
    document.getElementById("chatHeader").textContent = patientName;

    fetch(`/messages/${storeId}/${userId}`)
      .then(res => res.json())
      .then(messages => {
        const box = document.getElementById("messagesBox");
        box.innerHTML = "";
        messages.forEach(msg => {
          const isMine = msg.sender_id === authUserId;
          box.innerHTML += `
            <div class="${isMine 
              ? 'bg-sky-500 text-white ml-auto' 
              : 'bg-sky-200 text-sky-900'} p-2 rounded-lg max-w-md shadow">
              ${msg.message}
            </div>`;
        });
        box.scrollTop = box.scrollHeight;
      });
  }
setInterval(() => {
  if (currentPatient) {
    loadMessages(currentStore, currentPatient, document.getElementById("chatHeader").textContent);
  }
}, 3000);
  // Send message (admin to patient)
  document.getElementById("sendBtn").addEventListener("click", () => {
    const input = document.getElementById("messageInput");
    const text = input.value.trim();
    if (!text || !currentPatient) return;

    fetch("{{ route('messages.store') }}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}"
      },
      body: JSON.stringify({
        store_id: currentStore,
        user_id: currentPatient,
        message: text
      })
    })
    .then(res => res.json())
    .then(resp => {
      if (resp.status === "success") {
        const msg = resp.message;
        const box = document.getElementById("messagesBox");
        box.innerHTML += `
          <div class="bg-sky-500 text-white p-2 rounded-lg max-w-md ml-auto shadow">
            ${msg.message}
          </div>`;
        input.value = "";
        box.scrollTop = box.scrollHeight;
      } else {
        alert("Error: " + resp.message);
      }
    });
  });


</script>
@endsection
