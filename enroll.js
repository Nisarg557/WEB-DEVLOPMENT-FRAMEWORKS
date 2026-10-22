document.addEventListener("DOMContentLoaded", () => {
    const enrollForm = document.querySelector(".enroll-form");

    if (enrollForm) {
        enrollForm.addEventListener("submit", function (e) {
            // 1. Prevent the default page reload
            e.preventDefault();

            // 2. Gather the form data
            const formData = new FormData(enrollForm);

            // 3. Send the data to enroll.php in the background
            fetch("enroll.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // 4. Show success message
                alert("Enrollment successful! Your data has been saved.");
                
                // 5. Clear the form
                enrollForm.reset();
            })
            .catch(error => {
                console.error("Error:", error);
                alert("There was an error saving your enrollment. Please try again.");
            });
        });
    }
});