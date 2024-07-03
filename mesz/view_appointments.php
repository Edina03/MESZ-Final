<?php
session_start();
if (!isset($_SESSION["patientID"]) && !isset($_SESSION["doctorID"])) {
    header("location: index.php?message=Please, login");
    exit();
}

require 'functions/db-config.php';
global $pdo;
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>My appointments</title>
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
                    <a class="nav-link" href="doctors.php">Doctors <i class="bi bi-briefcase"></i></a>
                </li>
                <?php if (!isset($_SESSION['patientID']) && !isset($_SESSION['doctorID'])): ?>
                    <li class="nav-item"><a class="nav-link" href="register.php">Sign up <i class='bi bi-person'></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login <i class='bi bi-person'></i></a></li>
                <?php else: ?>
                    <?php
                    if (isset($_SESSION['patientID'])) {
                        echo '<li class="nav-item"><a class="nav-link" href="appointment.php">Appointments <i class="bi bi-calendar"></i></a></li>';
                        echo '<li class="nav-item"><a class="nav-link active" href="view_appointments.php">My appointments  <i class="bi bi-calendar-event"></i></a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="view_my_records.php">My Cardboard  <i class="bi bi-file-earmark-person"></i></a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="profile.php">Profile  <i class="bi bi-person-circle"></i></a></li>';
                    }
                    if (isset($_SESSION['doctorID'])) {
                     echo '<li class="nav-item"><a class="nav-link" href="add_patient_records.php">Writing medical record </a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="view_patient_records.php">Viewing medical records </a></li>';
                        echo '<li class="nav-item"><a class="nav-link active" href="view_appointments.php">My appointments </a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="admin.php">Admin page </a></li>';
                    }
                    ?>
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="functions/logOutFunction.php">Log out <i class="bi bi-box-arrow-right"></i></a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<br><br><br>
<div class="container">
    <h1>My appointments</h1>
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($_GET['message']); ?></div>
    <?php endif; ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Date</th>
            <th
                    Appointment
            </th>
            <th>Dentist</th>
            <th>Procedure</th>
            <th>Operations</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (isset($_SESSION['patientID'])) {
            $patientID = $_SESSION["patientID"];
            $sql = "SELECT a.appID, a.schedule, d.firstName, d.lastName, p.procedureName FROM Appointment a JOIN Doctor d ON a.doctorID = d.doctorID JOIN Procedures p ON a.procedureID = p.procedureID WHERE a.patientID = ? AND a.schedule > NOW() ORDER BY a.schedule ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$patientID]);
            $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        if (isset($_SESSION['doctorID'])) {
            $doctorID = $_SESSION["doctorID"];
            $sql = "SELECT a.appID, a.schedule, pa.firstName, pa.lastName, p.procedureName FROM Appointment a JOIN Patient pa ON a.patientID = pa.patientID JOIN Procedures p ON a.procedureID = p.procedureID WHERE a.doctorID = ? AND a.schedule > NOW() ORDER BY a.schedule ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$doctorID]);
            $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        foreach ($appointments as $appointment) {
            echo "<tr>";
            echo "<td>" . date('Y-m-d', strtotime($appointment['schedule'])) . "</td>";
            echo "<td>" . date('H:i', strtotime($appointment['schedule'])) . "</td>";
            echo "<td>" . htmlspecialchars($appointment['firstName'] . ' ' . $appointment['lastName']) . "</td>";
            echo "<td>" . htmlspecialchars($appointment['procedureName']) . "</td>";
            echo "<td>
                <form method='post' action='functions/delete_appointment.php' style='display:inline;'>
                    <input type='hidden' name='appID' value='" . $appointment['appID'] . "'>
                    <button type='submit' class='btn btn-danger btn-sm'>Delete</button>
                </form>
              </td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>