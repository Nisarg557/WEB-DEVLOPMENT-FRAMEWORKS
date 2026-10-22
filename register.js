function registerUser(event) 
{
    event.preventDefault();

    const firstName = document.getElementById('firstName');
    const middleName = document.getElementById('middleName');
    const lastName = document.getElementById('lastName');
    const username = document.getElementById('username');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirmPassword');

    const nameRegex = /^[A-Za-z]+$/;
    const usernameRegex = /^[a-zA-Z0-9_-]{3,16}$/;
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    if (!nameRegex.test(firstName.value) || !nameRegex.test(lastName.value) || 
        (middleName.value && !nameRegex.test(middleName.value)))
    {
        alert("Names must contain only letters.");
        return false;
    }

    if (!usernameRegex.test(username.value))
    {
        alert("Invalid Username!\n- Must be 3–16 characters long.\n- Can only contain letters, numbers, underscores (_), and hyphens (-).");
        return false;
    }

    if (!passwordRegex.test(password.value))
    {
        alert("Invalid Password!\n- Must be at least 8 characters long.\n- Must contain at least one uppercase letter, one lowercase letter, one number, and one special character.");
        return false;
    }

    if (password.value !== confirmPassword.value)
    {
        alert("Passwords do not match!");
        return false;
    }

    // Package the data and send to PHP
    const data = {
        firstName: firstName.value,
        middleName: middleName.value,
        lastName: lastName.value,
        username: username.value,
        password: password.value
    };

    fetch('register.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if(result.status === 'success') {
            alert(result.message);
            window.location.href = "login.html"; // Send them to login after registering
        } else {
            alert(result.message); // e.g., "Username already exists"
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred during registration.');
    });

    return false;
}