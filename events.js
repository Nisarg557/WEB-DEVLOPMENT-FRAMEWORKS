async function loadEvents() {
    try {
        // Fetch live data from the database via the API instead of events.json
        const response = await fetch('events_api.php?action=read');
        const result = await response.json();
        
        const container = document.querySelector('#events-container'); 
        let htmlContent = "";

        if (result.status === 'success') {
            result.data.forEach((event) => {
                // Map the database columns (title, event_date, description) to the HTML
                htmlContent += `
                    <div class="Event-element">
                        <h3>${event.title}</h3><hr>
                        <p>📅 <strong>Date:</strong> ${event.event_date}</p>
                        <p>📍 <strong>Details:</strong> ${event.description}</p>
                    </div>&nbsp;
                `;
            });
            container.innerHTML = htmlContent;
        } else {
            container.innerHTML = "<p>No events found.</p>";
        }

    } catch (error) {
        console.error("Error reading database:", error);
        document.querySelector('#events-container').innerHTML = "<p>Unable to load events right now.</p>"; 
    }
}

loadEvents();