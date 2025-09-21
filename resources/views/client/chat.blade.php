<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Messaging</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="h-screen bg-sky-50">
  <div class="flex h-screen">
    
    <!-- Sidebar (Patients) -->
    <div class="w-1/4 bg-sky-100 border-r border-sky-300 flex flex-col">
      <h2 class="text-lg font-bold p-4 bg-sky-300 text-white">Patients</h2>
      <ul id="patientList" class="flex-1 overflow-y-auto divide-y divide-sky-200">
        <!-- Example, but this should be loaded dynamically -->
        <li data-id="1" class="p-3 hover:bg-sky-200 cursor-pointer">John Doe</li>
        <li data-id="2" class="p-3 hover:bg-sky-200 cursor-pointer">Jane Smith</li>
        <li data-id="3" class="p-3 hover:bg-sky-200 cursor-pointer">Michael Cruz</li>
      </ul>
    </div>
    
    <!-- Chat Window -->
    <div class="flex-1 flex flex-col">
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
</body>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const patientList = document.querySelector("#patientList");
    const messagesBox = document.querySelector("#messagesBox");
    const chatHeader = document.querySelector("#chatHeader");
    const input = document.querySelector("#messageInput");
    const sendBtn = document.querySelector("#sendBtn");
    let currentPatientId = null;

    // Click on patient to load chat
    patientList.addEventListener("click", function (e) {
        if (e.target.tagName === "LI") {
            currentPatientId = e.target.dataset.id;
            chatHeader.textContent = e.target.textContent;
            loadMessages(currentPatientId);
        }
    });

    // Load messages
    function loadMessages(patientId) {
        fetch(`/messages/${patientId}`)
            .then(res => res.json())
            .then(messages => {
                messagesBox.innerHTML = "";
                messages.forEach(msg => {
                    if (msg.user_id === {{ auth()->id() }}) {
                        messagesBox.innerHTML += `
                            <div class="bg-sky-500 text-white p-2 rounded-lg max-w-md ml-auto shadow">
                              ${msg.message}
                            </div>`;
                    } else {
                        messagesBox.innerHTML += `
                            <div class="bg-sky-200 text-sky-900 p-2 rounded-lg max-w-md shadow">
                              ${msg.message}
                            </div>`;
                    }
                });
                messagesBox.scrollTop = messagesBox.scrollHeight;
            });
    }

    // Send message
    sendBtn.addEventListener("click", function () {
        if (!currentPatientId) {
            alert("Select a patient first.");
            return;
        }

        const text = input.value.trim();
        if (!text) return;

        fetch("{{ route('messages.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                store_id: currentPatientId,
                message: text
            })
        })
        .then(res => res.json())
        .then(msg => {
            messagesBox.innerHTML += `
                <div class="bg-sky-500 text-white p-2 rounded-lg max-w-md ml-auto shadow">
                  ${msg.message}
                </div>`;
            input.value = "";
            messagesBox.scrollTop = messagesBox.scrollHeight;
        });
    });
});
</script>
</html>
