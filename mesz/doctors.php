<?php
session_start();
require 'functions/db-config.php';
global $pdo;

$query = "SELECT doctorID, firstName, lastName, specialisation, phoneNumber, email FROM Doctor";
$stmt = $pdo->query($query);
$doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dentists</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
                    <a class="nav-link active" href="doctors.php">Doctors <i class="bi bi-briefcase"></i></a>
                </li>
                <?php if (!isset($_SESSION['patientID']) && !isset($_SESSION['doctorID'])): ?>
                    <li class="nav-item"><a class="nav-link" href="register.php">Sign up <i class='bi bi-person'></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login <i class='bi bi-person'></i></a></li>
                <?php else: ?>
                    <?php
                    if (isset($_SESSION['patientID'])) {
                        echo '<li class="nav-item"><a class="nav-link" href="appointment.php">Appointments <i class="bi bi-calendar"></i></a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="view_appointments.php">My appointments  <i class="bi bi-calendar-event"></i></a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="view_my_records.php">My Cardboard  <i class="bi bi-file-earmark-person"></i></a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="profile.php">Profile  <i class="bi bi-person-circle"></i></a></li>';
                    }
                    if (isset($_SESSION['doctorID'])) {
                       echo '<li class="nav-item"><a class="nav-link" href="add_patient_records.php">Writing medical record </a></li>';
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
<div class="container mt-5 pt-5">
    <h1 class="text-center mb-4">Doctors</h1>
    <div id="doctors" class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($doctors as $doctor): ?>
            <div class="col">
                <div class="card h-100 text-center">
                    <img src="images/<?php echo $doctor['doctorID']; ?>.jpg"
                         class="card-img-top doctor-image"
                         alt="<?php echo htmlspecialchars($doctor['firstName'] . ' ' . $doctor['lastName']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($doctor['firstName'] . ' ' . $doctor['lastName']); ?></h5>
                        <p class="card-text"><strong>Specialization:</strong> <?php echo htmlspecialchars($doctor['specialisation']); ?></p>
                        <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($doctor['email']); ?></p>
                        <p class="card-text"><strong>Phone:</strong> <?php echo htmlspecialchars($doctor['phoneNumber']); ?></p>
                            <button class="btn btn-primary" onclick="loadDoctorDetails(<?php echo $doctor['doctorID']; ?>)">Details</button>
                    </div>
                    <div id="doctor-details-<?php echo $doctor['doctorID']; ?>" class="card-footer doctor-details"></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function loadDoctorDetails(doctorID) {
        var detailsDiv = $('#doctor-details-' + doctorID);

        if (detailsDiv.html().trim() === '') {
            $.ajax({
                url: 'functions/get_doctor_worktime.php',
                method: 'GET',
                data: {doctor_id: doctorID},
                dataType: 'json',
                success: function (data) {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    detailsDiv.html(`
                        <p><strong>Working days:</strong></p>
                        <ul class="custom-list">
                            <li>Monday: ${data.days.Monday ? 'Yes' : 'No'}</li>
                            <li>Tuesday: ${data.days.Tuesday ? 'Yes' : 'No'}</li>
                            <li>Wednesday: ${data.days.Wednesday ? 'Yes' : 'No'}</li>
                            <li>Thursday: ${data.days.Thursday ? 'Yes' : 'No'}</li>
                            <li>Friday: ${data.days.Friday ? 'Yes' : 'No'}</li>
                            <li>Saturday: ${data.days.Saturday ? 'Yes' : 'No'}</li>
                            <li>Sunday: ${data.days.Sunday ? 'Yes' : 'No'}</li>
                        </ul>
                         <p><strong>Work hours:</strong> ${data.start} -  ${data.end}</p>
                    `);

                    detailsDiv.slideDown();
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching doctor details:', error);
                }
            });
        } else {
            detailsDiv.slideToggle();
        }
    }
</script>

</body>
</html>