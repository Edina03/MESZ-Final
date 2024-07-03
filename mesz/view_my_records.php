<?php
session_start();

if (!isset($_SESSION["patientID"])) {
    header("location: index.php?message=" . urlencode("Jelentkezzen be") . "&type=alert");
    exit();
}

require 'functions/db-config.php';
global $pdo;

$patientID = $_SESSION['patientID'];

$patientRecords = [];

$sql = "SELECT pr.procedureDate, pr.procedureDetails, pr.notes, d.firstName AS doctorFirstName, d.lastName AS doctorLastName, proc.procedureName, pr.price 
        FROM PatientRecords pr
        JOIN Doctor d ON pr.doctorID = d.doctorID
        JOIN Procedures proc ON pr.procedureID = proc.procedureID
        WHERE pr.patientID = ? ORDER BY pr.procedureDate DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$patientID]);
$patientRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>My records</title>
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
                        echo '<li class="nav-item"><a class="nav-link" href="view_appointments.php">My appointments  <i class="bi bi-calendar-event"></i></a></li>';
                        echo '<li class="nav-item"><a class="nav-link active" href="view_my_records.php">My Cardboard  <i class="bi bi-file-earmark-person"></i></a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="profile.php">Profile  <i class="bi bi-person-circle"></i></a></li>';
                    }
                    if (isset($_SESSION['doctorID'])) {
                     echo '<li class="nav-item"><a class="nav-link" href="add_patient_records.php">Writing medical record </a></li>';
                        echo '<li class="nav-item"><a class="nav-link active" href="view_patient_records.php">Viewing medical records </a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="view_appointments.php">My appointments </a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="admin.php">Admin page </a></li>';echo '<li class="nav-item"><a class="nav-link" href="admin.php">Admin page </a></li>';
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

<div class="container" style="padding-top: 70px;">
    <h2>My records</h2>
    <?php if (empty($patientRecords)): ?>
        <div class="alert alert-warning" role="alert" style="margin-top: 10px;">You don't have records.</div>
    <?php endif; ?>
    <?php if (!empty($patientRecords)): ?>
        <table class="table table-bordered" style="margin-top: 20px;">
            <thead>
            <tr>
                <th>Date of the procedure</th>
                <th>Procedure details</th>
                <th>Comments</th>
                <th>Dentist name</th>
                <th>Name of the procedure</th>
                <th>Price</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($patientRecords as $record): ?>
                <tr>
                    <td><?php echo htmlspecialchars($record['procedureDate']); ?></td>
                    <td><?php echo htmlspecialchars($record['procedureDetails']); ?></td>
                    <td><?php echo htmlspecialchars($record['notes']); ?></td>
                    <td><?php echo htmlspecialchars($record['doctorFirstName'] . ' ' . $record['doctorLastName']); ?></td>
                    <td><?php echo htmlspecialchars($record['procedureName']); ?></td>
                    <td><?php echo htmlspecialchars($record['price']); ?> DIN</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
</body>
</html>