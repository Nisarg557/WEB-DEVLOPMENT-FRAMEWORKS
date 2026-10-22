async function loginUser(event) 
{
    event.preventDefault(); 
    
    const usernameInput = document.getElementById('username').value.trim();
const passwordInput = document.getElementById('password').value.trim();
const rememberMeInput = document.getElementById('rememberMe').checked;

    const usernameRegex = /^[a-zA-Z0-9_-]{3,16}$/;
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!\%*?&]{8,}$/;

    if (!usernameRegex.test(usernameInput)) {
        alert("Invalid Username!\n- Must be 3-16 characters long.\n- Can only contain letters, numbers, underscores (_), and hyphens (-).");
        return false; 
    }

    if (!passwordRegex.test(passwordInput)) {
        alert("Invalid Password!\n- Must be at least 8 characters long.\n- Must contain at least one uppercase letter, one lowercase letter, one number, and one special character.");
        return false; 
    }

    try {
        const response = await fetch("login.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ 
                username: usernameInput, 
                password: passwordInput,
                remember_me: rememberMeInput
            })
        });
        

        const result = await response.json();

        if (result.status === "success") {
            alert(`Login successful! Welcome ${result.name}`);
            
            if (result.role === 'admin') {
                window.location.href = "admin_dashboard.html";
            } else {
                window.location.href = "mainpage.html"; 
            }
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error("Error with database login:", error);
        alert("Unable to process login right now.");
    }

    return false;
}