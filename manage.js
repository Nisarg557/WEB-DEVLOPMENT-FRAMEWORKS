document.addEventListener("DOMContentLoaded", loadEvents);

        async function loadEvents() {
            const search = document.getElementById('searchInput').value;
            const response = await fetch(`events_api.php?action=read&search=${search}`);
            const result = await response.json();
            
            const tbody = document.getElementById('eventsTableBody');
            tbody.innerHTML = '';
            
            if(result.status === 'success') {
                result.data.forEach(event => {
                    // Escape quotes in strings to prevent JS errors in the onclick handler
                    const safeTitle = event.title.replace(/'/g, "\\'");
                    const safeDesc = event.description.replace(/'/g, "\\'");
                    
                    tbody.innerHTML += `
                        <tr>
                            <td>${event.id}</td>
                            <td>${event.title}</td>
                            <td>${event.description}</td>
                            <td>${event.event_date}</td>
                            <td>
                                <button onclick="editEvent(${event.id}, '${safeTitle}', '${safeDesc}', '${event.event_date}')">Edit</button>
                                <button onclick="deleteEvent(${event.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                });
            }
        }

        async function saveEvent() {
            const id = document.getElementById('eventId').value;
            const data = {
                id: id,
                title: document.getElementById('eventTitle').value,
                description: document.getElementById('eventDescription').value,
                event_date: document.getElementById('eventDate').value
            };
            
            const action = id ? 'update' : 'create';
            const response = await fetch(`events_api.php?action=${action}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            alert(result.message);
            closeModal();
            loadEvents();
        }

        async function deleteEvent(id) {
            if(confirm("Are you sure you want to delete this event?")) {
                await fetch(`events_api.php?action=delete`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                });
                loadEvents();
            }
        }

        function openModal() { document.getElementById('eventModal').style.display = 'block'; }
        function closeModal() { 
            document.getElementById('eventModal').style.display = 'none'; 
            document.getElementById('eventId').value = '';
            document.getElementById('eventTitle').value = '';
            document.getElementById('eventDescription').value = '';
            document.getElementById('eventDate').value = '';
        }
        function editEvent(id, title, desc, date) {
            document.getElementById('eventId').value = id;
            document.getElementById('eventTitle').value = title;
            document.getElementById('eventDescription').value = desc;
            document.getElementById('eventDate').value = date;
            document.getElementById('modalTitle').innerText = "Edit Event";
            openModal();
        }