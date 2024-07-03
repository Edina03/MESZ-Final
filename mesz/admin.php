<?php ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to Admin page</title>
    <link rel="stylesheet" href="style/admin.css">
    <script>
        function redirectToAdminPage() {
            window.location.href = 'index.php';
        }
    </script>
</head>
<body>
    <h2>Login to Admin page</h2>
    <form action="secret/admin2_login.php" method="post">
        <label for="username">Admin username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <button class="btn btn-danger mb-2">Login to Admin page</button>
    </form>

    <button class="btn btn-danger mb-2" onClick="redirectToAdminPage()">Back</button>
</body>
</html>
