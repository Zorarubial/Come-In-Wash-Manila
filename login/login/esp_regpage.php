<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="text-center mt-5">User Registration</h2>
        <form action="esp_reghandler.php" method="POST" class="mt-4" id="registrationForm">
            <div class="form-group">
                <label for="iName">Name:</label>
                <input type="text" id="iName" name="iName" class="form-control" required pattern="^[a-zA-Z\s]+$" title="Name should contain only letters and spaces.">
            </div>
            <div class="form-group">
                <label for="iEmail">Email:</label>
                <input type="email" id="iEmail" name="iEmail" class="form-control" required pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Enter a valid email address.">
            </div>
            <div class="form-group">
                <label for="iPass">Password:</label>
                <input type="password" id="iPass" name="iPass" class="form-control" required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$" title="Password must be at least 8 characters long and contain one uppercase letter, one lowercase letter, one digit, and one special character.">
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            const form = event.currentTarget;
            
            // Check form validity
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        });
    </script>
</body>
</html>