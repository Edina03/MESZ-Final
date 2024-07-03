<?php
session_start();
if (!isset($_SESSION["patientID"])) {
    header("location: index.php?message=Please, login");
    exit();
}

require 'functions/db-config.php';
global $pdo;

$patientID = $_SESSION['patientID'];

$stmt = $pdo->prepare("SELECT auth FROM Patient WHERE patientID = ?");
$stmt->execute([$patientID]);
$auth = $stmt->fetchColumn();

if (!is_null($auth) && $auth !== '') {
    header("location: index.php?message=" . urlencode("Please, confirm your email address to continue.") . "&type=alert");
    exit();
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <title>Make an appointment</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/dentists.css">
</head>
<body>
<nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
    <div class="container-fluid">
        <img src="images/mesz.png" style="height: 50px">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">About us <i class="bi bi-house"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php#Location">Location <i class="bi bi-map"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php#Contact">Contact <i class="bi bi-telephone"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="prices.php">Prices <i class="bi bi-wallet"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="doctors.php">Doctors <i class="bi bi-briefcase"></i></a>
                </li>
                <?php if (!isset($_SESSION['patientID']) && !isset($_SESSION['doctorID'])): ?>
                    <li class="nav-item"><a class="nav-link" href="register.php">Sign up <i class='bi bi-person'></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login <i class='bi bi-person'></i></a></li>
                <?php else: ?>
                    <?php
                    if (isset($_SESSION['patientID'])) {
                        echo '<li class="nav-item"><a class="nav-link active" href="appointment.php">Appointments <i class="bi bi-calendar"></i></a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="view_appointments.php">My appointments  <i class="bi bi-calendar-event"></i></a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="view_my_records.php">My Cardboard  <i class="bi bi-file-earmark-person"></i></a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="profile.php">Profile  <i class="bi bi-person-circle"></i></a></li>';
                    }
                    if (isset($_SESSION['doctorID'])) {
                        echo '<li class="nav-item"><a class="nav-link active" href="add_patient_records.php">Writing medical record </a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="view_patient_records.php">Viewing medical records </a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="view_appointments.php">My appointments </a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="admin.php">Admin page </a></li>';
                    }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="functions/logOutFunction.php">Log out <i class="bi bi-box-arrow-right"></i></a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<br>
<div class="container">
    <h1>Make an appointment</h1>
    <form id="appointmentForm" method="POST" action="functions/book_appointment.php" class="form-horizontal">
        <div class="form-group">
            <label for="doctor" class="col-sm-2 control-label">Choose a dentist:</label>
            <div class="col-sm-10">
                <select id="doctor" name="doctor_id" class="form-control" required>
                    <option value="">Choose a dentist</option>
                    <?php
                    $stmt = $pdo->query("SELECT doctorID, firstName, lastName FROM Doctor");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value=\"{$row['doctorID']}\">{$row['firstName']} {$row['lastName']}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="procedure" class="col-sm-2 control-label">Choose a procedure:</label>
            <div class="col-sm-10">
                <select id="procedure" name="procedure_id" class="form-control" required>
                    <option value="">Choose a procedure</option>
                    <?php
                    $stmt = $pdo->query("SELECT procedureID, procedureName FROM Procedures");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value=\"{$row['procedureID']}\">{$row['procedureName']}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="appointment_day" class="col-sm-2 control-label">Choose the appointment day:</label>
            <div class="col-sm-10">
                <input type="text" id="appointment_day" name="appointment_day" class="form-control" required>
            </div>
        </div>

        <div class="form-group">
            <label for="appointment_time" class="col-sm-2 control-label">Choose the appointment time:</label>
            <div class="col-sm-10">
                <select id="appointment_time" name="appointment_time" class="form-control" required>
                    <!-- Időintervallumok itt lesznek feltöltve -->
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary">Make an appointment</button>
            </div>
        </div>
    </form>
    <?php
    if (isset($_GET['message'])) {
        $message = $_GET['message'];
        echo "<div class='alert alert-info' role='alert'>$message</div>";
    }
    ?>
    <h2>Booked appointments:</h2>
    <table class="table">
        <thead>
        <tr>
            <th>Appointment</th>
        </tr>
        </thead>
        <tbody id="bookedSlots">
        <!-- Itt lesznek megjelenítve a foglalt időpontok -->
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        let flatpickrInstance;
        $('#doctor').change(function () {
            var doctorId = $(this).val();
            if (doctorId) {
                $.ajax({
                    url: 'functions/get_doctor_worktime.php',
                    type: 'GET',
                    data: {doctor_id: doctorId},
                    dataType: 'json',
                    success: function (data) {
                        if (flatpickrInstance) {
                            flatpickrInstance.destroy();
                        }
                        var timeSelect = $('#appointment_time');
                        timeSelect.empty(); // Mezők kiürítése

                        var startTime = new Date('1970-01-01T' + data.start + ':00');
                        var endTime = new Date('1970-01-01T' + data.end + ':00');

                        while (startTime < endTime) {
                            var time = startTime.toTimeString().substring(0, 5);
                            timeSelect.append(new Option(time, time));
                            startTime.setMinutes(startTime.getMinutes() + 30);
                        }

                        var enabledDays = [];
                        if (data.days.Monday) enabledDays.push(1);
                        if (data.days.Tuesday) enabledDays.push(2);
                        if (data.days.Wednesday) enabledDays.push(3);
                        if (data.days.Thursday) enabledDays.push(4);
                        if (data.days.Friday) enabledDays.push(5);
                        if (data.days.Saturday) enabledDays.push(6);
                        if (data.days.Sunday) enabledDays.push(0);

                        flatpickrInstance = flatpickr("#appointment_day", {
                            dateFormat: "Y-m-d",
                            disable: [
                                function (date) {
                                    return !enabledDays.includes(date.getDay());
                                }
                            ],
                            minDate: new Date().fp_incr(1), // tomorrow
                            maxDate: new Date().fp_incr(30) // 1 month in the future
                        });

                        $(document).ready(function () {
                            $('#appointment_day').change(function () {
                                var doctorId = $('#doctor').val(); // Make sure you have the doctor ID selected
                                var selectedDate = $(this).val();
                                $.ajax({
                                    url: 'functions/get_booked_times.php',
                                    type: 'GET',
                                    data: {doctor_id: doctorId, date: selectedDate},
                                    dataType: 'json',
                                    success: function (data) {
                                        var bookedSlotsTable = $('#bookedSlots');
                                        bookedSlotsTable.empty();

                                        if (data && data.length > 0) {
                                            data.forEach(function (slot) {
                                                bookedSlotsTable.append('<tr><td>' + slot + '</td></tr>');
                                            });
                                        } else {
                                            bookedSlotsTable.append('<tr><td colspan="3">There are none booked appointments for this day.</td></tr>');
                                        }
                                    },
                                    error: function (xhr, status, error) {
                                        console.error(xhr.responseText);
                                        alert('An error occurred while retrieving the booked appointments. Please try again..');
                                    }
                                });
                            });
                        });

                    }
                });
            } else {
                $('#appointment_time').empty();
                if (flatpickrInstance) {
                    flatpickrInstance.destroy();
                }
                $('#appointment_day').val('');
            }
        });
    });
</script>
</body>
</html>