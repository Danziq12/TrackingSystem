<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    <link rel="stylesheet" type="text/css" href="Login.css">
</head>

<body>
    <div class="login-container">
        <form name="form1" method="post" action="checklogindb.php">

            <label for="username">Username:</label>
            <input type="text" name="username" id="username" /><br /> <br />

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" /><br/> <br/>

            <input name="submit" type="submit" id="submit" value="Log in" /> 
        </form>

        <!-- Register link -->
        <p style="text-align:center; margin-top: 10px;">
            Don't have an account? <a href="register.php" style="color:#007bff; text-decoration:none; font-weight:bold;">Register here</a>
        </p>
    </div>
</body>

<footer>
    <p>&copy; 2025 Binasifu. All rights reserved.</p>
</footer>
</html>
