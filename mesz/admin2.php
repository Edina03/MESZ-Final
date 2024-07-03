<?php
session_start();
include '../functions/db-config.php';
if (!isset($_SESSION['doctorID'])) {
    header("location: index.php&message=Not an admin&type=error");
    exit();
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Dental System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style/admin.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-pZ+NqAs/sHSTXxDr+XGDBd3b7W8a5tvhS4WWQhc5vqOs2TcW6Kpq8l6I4H5Odf1B"
            crossorigin="anonymous"></script>
    <script>
        function toggleFields() {
            var operation = document.getElementById("operation").value;
            var fields = document.querySelectorAll(".conditional-field");
            fields.forEach(function (field) {
                field.style.display = (operation === "delete") ? "none" : "block";
            });
        }

        function fetchBookings() {
            var date = document.getElementById("date-picker").value;
            if (date) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "functions/view_reservations.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        document.getElementById("bookings").innerHTML = xhr.responseText;
                    }
                };
                xhr.send("date=" + encodeURIComponent(date));
            } else {
                alert("Please choose a date.");
            }
        }
    </script>
</head>
<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">
<nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
    <div class="container-fluid">
        <img src="images/mesz.png" style="height: 50px">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="admin2.php">Admin page</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Log out <i class="bi bi-box-arrow-right"></i></a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<br><br><br><br><br><br>
<a href="#procedure_insert" class="btn btn-primary mb-2">Inserting and modifying procedures</a>
<a href="#procedure_delete" class="btn btn-secondary mb-2">Delete a procedure</a>
<a href="#dentist_insert" class="btn btn-success mb-2">Dentist insertion and modification</a>
<a href="#dentist_delete" class="btn btn-danger mb-2">Delete a dentist</a>
<a href="#viewing" class="btn btn-info mb-2">Viewing appointments</a>
<div id="procedure_insert">
    <br><br><br><br><br><br><hr>
    <br>
    <h1>PROCEDURES</h1>
    <h2>Inserting and modifying procedures </h2>
    <form action="functions/manage_services.php" method="post">
        <label for="operation">Operation:</label>
        <select name="operation" id="operation" onchange="toggleFields()">
            <option value="insert">Input</option>
            <option value="update">Update</option>
        </select><br><br>

        <label for="procedureID">Procedure ID:</label>
        <input type="text" id="procedureID" name="procedureID"><br><br>

        <label for="procedureName">Procedure name:</label>
        <input type="text" id="procedureName" name="procedureName"><br><br>

        <label for="price">Price:</label>
        <input type="text" id="price" name="price"><br><br>

        <button class="btn btn-danger mb-2">Send</button>
        <a href="#top" class="btn btn-secondary">Back to Top</a>
    </form>
</div>
<br><br>
<hr>
<br><br>
<div id="procedure_delete">
    <br><br><br><br><br><br>
    <h2>Delete a procedure</h2>
    <form action="functions/delete_service.php" method="post">
        <label for="procedureID">Procedure ID:</label>
        <input type="number" id="procedureID" name="procedureID" required><br><br>
        <button class="btn btn-danger mb-2">Delete</button>
        <a href="#top" class="btn btn-secondary">Back to Top</a>
    </form>
</div>

<br><br>
<hr>
<br><br>
        <h1>DENTISTS</h1>
<div id="dentist_insert">
    <br><br><br><br><br><br>
    <h2>Dentist insertion and modification</h2>
    <form action="functions/manage_dentists.php" method="post">
        <label for="operation">Operation:</label>
        <select name="operation" id="operation">
            <option value="insert">Input</option>
            <option value="update">Update</option>
        </select><br><br>

        <label for="doctorID">Dentist ID:</label>
        <input type="text" id="doctorID" name="doctorID"><br><br>

        <label for="firstName">First name:</label>
        <input type="text" id="firstName" name="firstName" required><br><br>

        <label for="lastName">Last name:</label>
        <input type="text" id="lastName" name="lastName" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="phoneNumber">Phone number:</label>
        <input type="text" id="phoneNumber" name="phoneNumber" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="worktime">Work time:</label>
        <input type="text" id="worktime" name="worktime" required><br><br>

        <label for="specialisation">Specialisation:</label>
        <input type="text" id="specialisation" name="specialisation" required><br><br>

        <button class="btn btn-danger mb-2">Send</button>
        <a href="#top" class="btn btn-secondary">Back to Top</a>
    </form>
</div>
<br><br>
<hr>
<br><br>
<div id="dentist_delete">
    <br><br><br><br><br><br>
    <h2>Delete a dentist</h2>
    <form action="functions/delete_dentist.php" method="post">
        <label for="doctorID">Dentist ID:</label>
        <input type="number" id="doctorID" name="doctorID"><br><br>
        <button class="btn btn-danger mb-2">Delete</button>
        <a href="#top" class="btn btn-secondary">Back to Top</a>
    </form>
</div>
<br><br>
<hr>
<br><br>
<div id="viewing">
    <br><br><br><br><br><br>
          <h1>APPOINTMENTS</h1>
    <h2>Viewing appointments</h2>
    <form onsubmit="fetchBookings(); return false;">
        <label for="date-picker">Date:</label>
        <input type="date" id="date-picker" name="date" required><br><br>
        <button class="btn btn-danger mb-2">View</button>
        <a href="#top" class="btn btn-secondary">Back to Top</a>
    </form>
    <br><br><br>
    <div id="bookings"></div>
</div>
<br><br><br><br><br><br>
<script>
    toggleFields();
</script>
</body>
</html>
