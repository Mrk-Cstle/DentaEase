@extends('layout.cnav')

@section('title', 'Chat')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('main-content')
<div class="flex h-screen">
  
  <!-- Sidebar (Branches) -->
  <div class="w-1/4 bg-sky-100 border-r border-sky-300 flex flex-col">
    <h2 class="text-lg font-bold p-4 bg-sky-300 text-white">Branches</h2>
    <ul id="branchList" class="flex-1 overflow-y-auto divide-y divide-sky-200"></ul>
  </div>
  
  <!-- Chat Window -->
  <div class="flex-1 flex flex-col bg-slate-300">
    <div id="chatHeader" class="p-4 bg-sky-300 text-white font-bold">Select a branch</div>
    <div id="messagesBox" class="flex-1 overflow-y-auto p-4 space-y-3"></div>
    <div class="p-4 border-t border-sky-300 flex">
      <input id="messageInput" type="text" placeholder="Type a message..."
        class="flex-1 border rounded-lg p-2 focus:outline-none focus:ring focus:ring-sky-400">
      <button id="sendBtn" class="ml-2 bg-sky-500 text-white px-4 py-2 rounded-lg hover:bg-sky-600">Send</button>
    </div>
  </div>
</div>

<script>
let currentBranch = null;
const authUserId = {{ auth()->id() }};

// ✅ Load branches with latest message and highlight current
function loadBranches() {
  fetch("{{ route('branches.list') }}")
    .then(res => res.json())
    .then(branches => {
      const branchList = document.getElementById("branchList");
      branchList.innerHTML = "";

      branches.forEach(branch => {
        const lastMsg = branch.latest_message
          ? branch.latest_message.message
          : "No messages yet";

        const li = document.createElement("li");
        li.className = `p-3 hover:bg-sky-200 cursor-pointer border-b border-sky-200 transition
          ${currentBranch === branch.id ? "bg-sky-300" : ""}`;

        li.innerHTML = `
          <strong>${branch.name}</strong><br>
          <small class="text-gray-600">${lastMsg}</small>
        `;

        li.onclick = () => {
          currentBranch = branch.id;
          document.getElementById("chatHeader").textContent = branch.name;
          loadMessages(branch.id, branch.name);
        };

        branchList.appendChild(li);
      });
    })
    .catch(err => console.error("Error loading branches:", err));
}

// ✅ Load chat messages for the selected branch
function loadMessages(storeId, branchName) {
  currentBranch = storeId;
  document.getElementById("chatHeader").textContent = branchName;

  fetch(`/patient/messages/${storeId}`)
    .then(res => res.json())
    .then(messages => {
      const box = document.getElementById("messagesBox");
      box.innerHTML = "";

      messages.forEach(msg => {
        const isMine = msg.sender_id === authUserId;
        const msgClass = isMine
          ? "bg-sky-500 text-white ml-auto"
          : "bg-sky-200 text-sky-900";

        box.innerHTML += `
          <div class="${msgClass} p-2 rounded-lg max-w-md shadow">
            ${msg.message}
          </div>`;
      });

      box.scrollTop = box.scrollHeight;
    });
}

// ✅ Send message
document.getElementById("sendBtn").addEventListener("click", () => {
  const input = document.getElementById("messageInput");
  const text = input.value.trim();
  if (!text || !currentBranch) return;

  fetch("{{ route('patient.messages.store') }}", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": "{{ csrf_token() }}"
    },
    body: JSON.stringify({
      store_id: currentBranch,
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
        loadBranches(); // refresh branch list after sending
      } else {
        alert("Error: " + resp.message);
      }
    });
});

// ✅ Refresh every 3 seconds
setInterval(() => {
  loadBranches();
  if (currentBranch) {
    loadMessages(currentBranch, document.getElementById("chatHeader").textContent);
  }
}, 3000);

// Initial load
loadBranches();

</script>
@endsection
