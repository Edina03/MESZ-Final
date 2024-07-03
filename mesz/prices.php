<?php
session_start();
$message = $_GET['message'] ?? '';
$messageType = $_GET['type'] ?? 'info';

require 'functions/db-config.php';
global $pdo;

if (isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];

    $stmt = $pdo->prepare("SELECT patientID FROM Patient WHERE remember_token = :token");
    $stmt->execute([':token' => $token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt2 = $pdo->prepare("SELECT doctorID FROM Doctor WHERE remember_token = :token");
    $stmt2->execute([':token' => $token]);
    $doctor = $stmt2->fetch(PDO::FETCH_ASSOC);

    if ($doctor) {
        $_SESSION['doctorID'] = $doctor['doctorID'];
    } else {
        setcookie('remember_me', '', time() - 3600, "/", "", false, true);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prices</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style/prices.css">
</head>
<br data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">
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
                    <a class="nav-link active" href="prices.php">Prices <i class="bi bi-wallet"></i></a>
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
                     echo '<li class="nav-item"><a class="nav-link" href="add_patient_records.php">Writing medical record </a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="view_patient_records.php">Viewing medical records </a></li>';
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
<div class="container">
    <?php if ($message !== ''): ?>
        <div class="alert alert-<?php echo $messageType; ?>" role="alert"><?php echo $message; ?></div>
    <?php endif; ?>
    <h2>Procedures and Prices</h2>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Proedure</th>
            <th>Price</th>
        </tr>
        </thead>
        <tbody>
        <?php
        try {
            $stmt = $pdo->query("SELECT procedureName, price FROM Procedures");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr><td>{$row['procedureName']}</td><td>{$row['price']} DIN</td></tr>";
            }
        } catch (PDOException $e) {
            echo "<tr><td colspan='2'>Hiba történt az eljárások betöltése közben: " . $e->getMessage() . "</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

</body>
</html>
