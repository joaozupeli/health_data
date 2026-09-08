
document.getElementById("login-form").addEventListener("submit", function login(event) {
    event.preventDefault();

    const user = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    
    if (user === "" || password === "") {
        alert("Username and password are required");
        return;
    }

    fetch("http://localhost:8080/modules/auth/AuthController.php", {
        method: "POST",
        body: JSON.stringify({ username: user, password: password }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = "dashboard.php";
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        alert("Error: " + error);
    });
});
