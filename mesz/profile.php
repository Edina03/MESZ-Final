<?php
session_start();
$message = $_GET['message'] ?? '';
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
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
                        echo '<li class="nav-item"><a class="nav-link" href="view_my_records.php">My Cardboard  <i class="bi bi-file-earmark-person"></i></a></li>';
                        echo '<li class="nav-item"><a class="nav-link active" href="profile.php">Profile  <i class="bi bi-person-circle"></i></a></li>';
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
<br><br><br>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h2>Profile</h2>
            <?php if ($message !== ''): ?>
                <div class="alert alert-info" role="alert"><?php echo $message; ?></div>
            <?php endif; ?>
            <form id="profileForm" method="POST" action="functions/update_profile.php" class="form-horizontal">
                <div class="form-group">
                    <label for="inputPassword" class="col-sm-3 control-label">New password</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" id="inputPassword" name="newPassword"
                               placeholder="New password">
                        <small id="passwordHelpBlock" class="form-text text-danger"></small>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPhoneNumber" class="col-sm-3 control-label">Phone number</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="inputPhoneNumber" name="phoneNumber"
                               placeholder="Phone number">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail" class="col-sm-3 control-label">Email</label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Email">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-primary">Update profile</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#profileForm').submit(function (e) {
            e.preventDefault();

            var password = $('#inputPassword').val();
            if (password !== '' && !isValidPassword(password)) {
                $('#passwordHelpBlock').text('The password needs to be at least 8 characters long and needs to contain at least one upper case letter and one number.');
                return;
            }

            $('#passwordHelpBlock').text('');

            var formData = $(this).serialize();
            $.ajax({
                url: 'functions/update_profile.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    alert(response.message);
                    if (response.success) {
                        $('#inputPassword').val('');
                        $('#inputPhoneNumber').val('');
                        $('#inputEmail').val('');
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('An error occurred while processing the request. Please try again.');
                }
            });
        });

        function isValidPassword(password) {
            var passwordRegex = /^(?=.*[!@#$%^&*()_+\-={}\[\]:;"'<>,.?\/\\])(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
            return passwordRegex.test(password);
        }
    });
</script>

</body>
</html>