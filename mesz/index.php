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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
    <title>MESZ</title>
    <style>
        body {
            padding-top: 70px;
        }
    </style>
    <link rel="stylesheet" href="style/index.css">
</head>
<body>

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
                    <a class="nav-link" href="#About_us">About us <i class="bi bi-house"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#Location">Location <i class="bi bi-map"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#Contact">Contact <i class="bi bi-telephone"></i></a>
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

<div id="About_us" class="container-fluid">
    <br>
    <h2>Welcome to our page!</h2>
    <p>At our Dental Care Center, we believe in the power of a healthy smile. From routine cleanings to advanced
        procedures,
        we are here to provide you with comprehensive dental care that exceeds your expectations.
        Our team of skilled professionals is dedicated to ensuring your comfort and satisfaction throughout your dental
        journey.
        With state-of-the-art technology and personalized treatment plans, we strive to deliver exceptional results
        while maintaining a warm and welcoming atmosphere.
    </p>
    <p>
        Explore our website to learn more about our services, meet our team, and schedule your appointment today. We
        can't wait to welcome you to our practice!
    </p>
    <!-- Full-width images with number and caption text -->
    <div class="container">
        <div class="mySlides">
            <img src="images/Home_page-appointment_1.jpg" style="width:100%">
        </div>

        <div class="mySlides">
            <img src="images/Home_page-appointment_2.jpg" style="width:100%">
        </div>

        <div class="mySlides">
            <img src="images/Home_page-appointment_3.jpg" style="width:100%">
        </div>

        <div class="mySlides">
            <img src="images/Home_page-appointment_5.jpg" style="width:100%">
        </div>
        <div class="mySlides">
            <img src="images/Home_page-Smile.jpg" style="width:100%">
        </div>

        <a class="prev" onclick="plusSlides(-1)">❮</a>
        <a class="next" onclick="plusSlides(1)">❯</a>

    </div>
    <div class="row">
        <div class="column">
            <img class="demo cursor" src="images/Home_page-appointment_1.jpg" style="width:100%"
                 onclick="currentSlide(1)" alt="The Woods">
        </div>
        <div class="column">
            <img class="demo cursor" src="images/Home_page-appointment_2.jpg" style="width:100%"
                 onclick="currentSlide(2)" alt="Cinque Terre">
        </div>
        <div class="column">
            <img class="demo cursor" src="images/Home_page-appointment_3.jpg" style="width:100%"
                 onclick="currentSlide(3)" alt="Mountains and fjords">
        </div>
        <div class="column">
            <img class="demo cursor" src="images/Home_page-room_2.jpg" style="width:100%" onclick="currentSlide(4)"
                 alt="Northern Lights">
        </div>
        <div class="column">
            <img class="demo cursor" src="images/Home_page-appointment_5.jpg" style="width:100%"
                 onclick="currentSlide(4)" alt="Northern Lights">
        </div>
        <div class="column">
            <img class="demo cursor" src="images/Home_page-Smile.jpg" style="width:100%" onclick="currentSlide(4)"
                 alt="Northern Lights">
        </div>
    </div>
</div>

<script src="javascript/index.js"></script>

<div id="Location" class="container-fluid">
    <br>
    <br>
    <h1>Our location</h1><br>
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2766.8225856836293!2d19.6595730766567!3d46.09451369116248!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x474366d1b03dbbc5%3A0xfbb187d5a85acad0!2sVisoka%20tehni%C4%8Dka%20%C5%A1kola%20strukovnih%20studija%20-%20Subotica!5e0!3m2!1shu!2srs!4v1710957232074!5m2!1shu!2srs"
            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">

    </iframe>
    <br>
</div>
<div id="Contact" class="container-fluid">
    <h1 class="mt-5">Contact</h1> <br>
    <div class="row">
        <div class="col"><h5>If you have any questions feel free to contact us! <br>We are here for you!</h5>
            <address>
                Mobile: 024655201 / 0650350604 <br>
                Gmail: mesz@vts.su.ac.rs <br>
            </address>
            <table>
                <tr>
                    <th>Day</th>
                    <th>Open hours</th>
                </tr>
                <tr>
                    <td>Monday</td>
                    <td>08-18</td>
                </tr>
                <tr>
                    <td>Tuesday</td>
                    <td>08-18</td>
                </tr>
                <tr>
                    <td>Wednesday</td>
                    <td>08-18</td>
                </tr>
                <tr>
                    <td>Thursday</td>
                    <td>08-18</td>
                </tr>
                <tr>
                    <td>Friday</td>
                    <td>08-18</td>
                </tr>
                <tr>
                    <td>Weekend</td>
                    <td>08-18</td>
                </tr>
            </table>
        </div>
        <div class="col"><img src="images/Home_page-Call.jpg" alt="Call center" style="height: fit-content"
                              width="100%"></div>
    </div>
    <br>
</div>
<footer>
    <p>© 2024 MESZ, All Rights Reserved.</p>
</footer>
</body>
</html>