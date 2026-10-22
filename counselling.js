document.addEventListener("DOMContentLoaded", () => {
    loadSessions();
    loadProfileInfo(); // Add this line to fetch the profile data on load
});

// Fetch student and counsellor data from the database
async function loadProfileInfo() {
    try {
        const response = await fetch('api_counsellor_info.php');
        const result = await response.json();

        if (result.status === 'success') {
            const data = result.data;
            
            // Populate Counsellor Info
            document.getElementById('cName').innerText = data.counsellor_name || "Not Assigned";
            document.getElementById('cEmail').innerText = data.counsellor_email || "N/A";
            document.getElementById('cContact').innerText = data.counsellor_contact || "N/A";
            
            // Populate Student Info
            document.getElementById('sName').innerText = `${data.first_name} ${data.last_name}`;
            document.getElementById('sRoll').innerText = data.username; // Assuming username is Roll No
            document.getElementById('sUser').innerText = data.username; 
        } else {
            console.error("Error loading info:", result.message);
            document.getElementById('cName').innerText = "Error loading data.";
        }
    } catch (error) {
        console.error("Fetch failed:", error);
    }
}

async function loadSessions() {
    try {
        const response = await fetch('api_counselling.php');
        const result = await response.json();
        const tbody = document.getElementById('sessionTableBody');
        tbody.innerHTML = '';

        if (result.status === 'success') {
            result.data.forEach(session => {
                const safeName = session.student_name.replace(/'/g, "\\'");
                const safeTopic = session.topic.replace(/'/g, "\\'");
                
                tbody.innerHTML += `
                    <tr>
                        <td>${session.id}</td>
                        <td>${session.student_name}</td>
                        <td>${session.topic}</td>
                        <td>${session.session_date}</td>
                        <td>
                            <button onclick="editSession(${session.id}, '${safeName}', '${safeTopic}', '${session.session_date}')">Edit</button>
                            <button onclick="deleteSession(${session.id})">Delete</button>
                        </td>
                    </tr>
                `;
            });
        }
    } catch (error) {
        console.error("Failed to load sessions:", error);
    }
}

async function saveSession() {
    const id = document.getElementById('sessionId').value;
    const method = id ? 'PUT' : 'POST';
    
    const payload = {
        id: id,
        student_name: document.getElementById('studentName').value,
        topic: document.getElementById('sessionTopic').value,
        session_date: document.getElementById('sessionDate').value
    };

    try {
        const response = await fetch('api_counselling.php', {
            method: method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        
        const result = await response.json();
        alert(result.message);
        
        closeSessionModal();
        loadSessions(); 
    } catch (error) {
        console.error("Failed to save session:", error);
    }
}

async function deleteSession(id) {
    if (confirm("Delete this session?")) {
        try {
            const response = await fetch('api_counselling.php', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            });
            
            await response.json();
            loadSessions(); 
        } catch (error) {
            console.error("Failed to delete session:", error);
        }
    }
}

function openSessionModal() { 
    document.getElementById('counsellingModal').style.display = 'block'; 
    document.getElementById('modalTitle').innerText = "Book Session";
}

function closeSessionModal() { 
    document.getElementById('counsellingModal').style.display = 'none';
    document.getElementById('sessionId').value = '';
    document.getElementById('studentName').value = '';
    document.getElementById('sessionTopic').value = '';
    document.getElementById('sessionDate').value = '';
}

function editSession(id, name, topic, date) {
    document.getElementById('sessionId').value = id;
    document.getElementById('studentName').value = name;
    document.getElementById('sessionTopic').value = topic;
    document.getElementById('sessionDate').value = date;
    document.getElementById('modalTitle').innerText = "Edit Session";
    document.getElementById('counsellingModal').style.display = 'block';
}