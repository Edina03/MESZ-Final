<?php
session_start();
if (!isset($_SESSION['doctorID'])) {
    header("location: index.php?message=" . urlencode("Log in") . "&type=alert");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
    <title>Adding patient treatment data</title>
    <style>
        body {
            padding-top: 70px;
        }
    </style>
    <link rel="stylesheet" href="style/dentists.css">
</head>
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
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="functions/logOutFunction.php">Log out <i class="bi bi-box-arrow-right"></i></a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<br><br>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h2>Adding Patient Treatment Data</h2>
            <?php if (isset($_GET['message'])): ?>
                <div class="alert alert-info" role="alert"><?php echo htmlspecialchars($_GET['message']); ?></div>
            <?php endif; ?>
            <form method="POST" action="functions/add_patient_record.php" class="form-horizontal">
                <div class="form-group">
                    <label for="patientID" class="col-sm-3 control-label">Patient ID</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="patientID" name="patientID" placeholder="Patient ID"
                               required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="procedureDate" class="col-sm-3 control-label">Procedure date</label>
                    <div class="col-sm-9">
                        <input type="date" class="form-control" id="procedureDate" name="procedureDate" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="procedureDetails" class="col-sm-3 control-label">Procedure Details</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" id="procedureDetails" name="procedureDetails" rows="4"
                                  required></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes" class="col-sm-3 control-label">Notes</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" id="notes" name="notes" rows="4"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="procedure" class="col-sm-3 control-label">Procedure</label>
                    <div class="col-sm-9">
                        <select id="procedure" name="procedure_id" class="form-control" required>
                            <option value="">Choose a procedure</option>
                            <?php
                            require 'functions/db-config.php';
                            global $pdo;
                            $stmt = $pdo->query("SELECT procedureID, procedureName FROM Procedures");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value=\"{$row['procedureID']}\">{$row['procedureName']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
              
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
</body>
</html>