<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Restaurant Booking App</title>

        <!-- Font Awesome -->
        <link href="vendor/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="css/main.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <form action="index.html" method="post">
                <label><input type="text" name="fullname" placeholder="Your Name"></label>
                <label><input type="text" name="username" placeholder="Username"></label>
                <label><input type="password" name="password" placeholder="Password"></label>
                <label><input type="text" name="address" placeholder="Address"></label>
                <label><input type="text" name="zipcode" placeholder="ZIP"></label>
                <label><input type="text" name="contact" placeholder="Contact No"></label>
                <a href="/dashboard"><button type="button" name="signup">Sign Up</button></a>
            </form>
            <div class="login">
                <p>Already have an account? <a href="/">Sign in here</a></p>
            </div>
        </div>
    </body>

</html>
