<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style/login_sing_up.css">
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
                    <li class="nav-item"><a class="nav-link active" href="register.php">Sign up <i class='bi bi-person'></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login <i class='bi bi-person'></i></a></li>
                <?php else: ?>
                    <?php
                    if (isset($_SESSION['patientID'])) {
                        echo '<li class="nav-item"><a class="nav-link" href="appointment.php">Időpont foglalás</a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="view_appointments.php">Foglalásaim</a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="view_my_records.php">Kartonom</a></li>';
                    }
                    if (isset($_SESSION['doctorID'])) {
                        echo '<li class="nav-item"><a class="nav-link" href="add_patient_records.php">Writing medical record </a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="view_patient_records.php">Viewing medical records </a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="view_appointments.php">My appointments </a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="admin.php">Admin page </a></li>';echo '<li class="nav-item"><a class="nav-link" href="admin.php">Admin page </a></li>';
                    }
                    ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">
                            <?php echo htmlspecialchars($_SESSION['firstName'] . ' ' . $_SESSION['lastName']); ?> <span
                                    class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <?php
                            if(isset($_SESSION['patientID'])) {
                                echo '<li class="nav-item"><a href="profile.php">Profil</a></li>"';
                            }
                            ?>
                            <li><a href="functions/logOutFunction.php">Kijelentkezés</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="form_login">
    <h1>Registration</h1>
    <form action="functions/regFunction.php" method="POST" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="firstName">First name:</label>
            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First name" required>
        </div>
        <div class="form-group">
            <label for="lastName">Last name:</label>
            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last name" required>
        </div>
        <div class="form-group">
            <label for="phoneNumber">Phone number:</label>
            <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="+381 6********"
                   required>
        </div>
        <div class="form-group">
            <label for="email">Email address:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="example@gamail.com" required>
            <small id="emailHelpBlock" class="form-text text-muted" style="display: none; color: red;">Please enter a valid email address.</small>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="My password" required>
            <small id="passwordHelpBlock" class="form-text text-muted" style="display: none; color: red;">
                     The password needs to contain at least one number, special character and one upper case letter.
            </small>
        </div>
        <div class="form-group">
            <label for="passwordConfirm">Confirm Password:</label>
            <input type="password" class="form-control" id="passwordConfirm" name="passwordConfirm"
                   placeholder="Repeat Password" required>
            <small id="passwordConfirmHelpBlock" class="form-text text-muted" style="display: none; color: red;">The two passwords need to be identical!</small>
        </div>
        <button class="submit_2" type="submit" id="registerBtn">Sign Up</button>
        <a href="login.php" class="ca">Already have an account?</a><br>
        <a href="index.php#About_us" class="ca">Back</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<script src="functions/register.js"></script>

</body>
</html>
