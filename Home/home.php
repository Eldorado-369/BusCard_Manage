<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Card Management System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>

    </style>

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light shadow-sm" style="background-color: darkgreen;">
        <a class="navbar-brand text-white fw-bold" href="#">Bus Card Management</a>
        <div class="container d-flex">
            <button class="navbar-toggler border-0 ms-auto" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div class="collapse navbar-collapse ms-5" id="navbarNav">
                <ul class="navbar-nav mx-auto" style="margin-left:-1rem;">
                    <li class="nav-item">
                        <a class="nav-link custom-link px-4 text-white" href="http://localhost/Project/loginPage/loginPage.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link custom-link px-4 text-white" href="http://localhost/Project/signUpPage/signUpPage.php">Sign-Up</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link custom-link px-4 text-white" href="#aboutproject">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link custom-link px-4 text-white" href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="photo">
        <div class="image-container">
            <img src="../image/college2.png" alt="College Image" class="img-fluid">
            <div class="overlay-text-1">GET READY</div>
            <div class="overlay-text-2">TO IGNITE YOUR POTENTIAL</div>
        </div>
    </div>


    <section id="aboutproject" class="py-0">
        <div class="container1" style="color:black">
            <div class="about">
                <h2 class="text-center mb-4 " id="headingtextcolor">About the Project</h2>
                <h5> The Bus Card Management System is an innovative solution designed to streamline and optimize college transportation services. This system introduces two types of cards: the yellow card for regular bus users and the pink card for occasional travelers. The primary goal is to enhance the efficiency of college bus operations while providing flexible options for students with varying transportation needs.</h5>
                <h5>The yellow card caters to students who use the college bus daily and have paid their fees for the entire academic year. On the other hand, the pink card serves day scholars who require occasional bus access, limited to seven trips per month. Pink card holders must make payments through the website before boarding and are subject to a fare calculation based on route distance. To maintain balance and ensure availability for regular users, only 15 pink card holders are allowed per trip, and they must yield their seats to yellow card holders when necessary.</h5>
                <h5>Additionally, a real- time bus location feature is implemented for both administrative oversight and student convenience. This feature allows students to track their bus on travel days, improving punctuality and reducing wait times. By integrating these technologies and management strategies, the Bus Card Management System aims to create a more organized, efficient, and user-friendly transportation experience for college students.</h5>
            </div>
        </div>
    </section>


    <section id="features" class="py-0">
        <div class="container ">
            <h2 class="text-center mb-4" id="headingtextcolor">Features</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-4 feature-card">
                        <div class="card-body text-center" style="color:black">
                            <h5 class="card-title">Easy Registration</h5>
                            <p class="card-text">Quick and simple registration process for students.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4 feature-card">
                        <div class="card-body text-center" style="color:black">
                            <h5 class="card-title">Real-Time Tracking</h5>
                            <p class="card-text">Track bus locations and timings in real-time.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4 feature-card">
                        <div class="card-body text-center" style="color:black">
                            <h5 class="card-title">Secure Payments</h5>
                            <p class="card-text">Make secure online payments for bus cards.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container" style="color:black">
        <section id="aboutus" class="py-5 bg-light">
            <h2 class="text-center" id="headingtextcolor">About Us</h2>
            <p class="text-center" style="font-family:'Times New Roman', Times, serif; font-size: 1.2rem; margin-top:2rem;">Our mission is to provide a convenient and efficient bus card management system for students and schools.</p>
        </section>
    </div>
    <section id="contact" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4" id="headingtextcolor">Contact Us</h2>
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <input type="text" class="form-control" id="inputName" name="name" placeholder="Name" required>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Email" required>
                    </div>
                </div>
                <div class="form-group">
                    <textarea class="form-control" id="inputMessage" name="message" rows="4" placeholder="Message" required></textarea>
                </div>
                <button type="submit" class="btn custom-btn">Send Message</button>
            </form>

        </div>
    </section>

    <!-- inbox -->

    <?php
    $connection = mysqli_connect("localhost", "root", "", "busCardManagementSystem");
    if ($connection->connect_error) {
        echo "<div class='alert alert-danger alert-dismissible fade show text-center' role='alert'>
            Connection Failed: " . $connection->connect_error . "
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
            </button>
          </div>";
        die();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        $query = "INSERT INTO inboxTable (fromMail, toMail, content) VALUES (?, ?, ?)";
        $stmt = $connection->prepare($query);

        $toMail = 'admin@gmail.com';

        $stmt->bind_param("sss", $email, $toMail, $message);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success alert-dismissible fade show text-center' role='alert'>
                Your message has been sent successfully!
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
              </div>";
        } else {
            echo "<div class='alert alert-danger alert-dismissible fade show text-center' role='alert'>
                Error: " . $stmt->error . "
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
              </div>";
        }

        $stmt->close();
    }
    ?>


    <script>
        // Automatically dismiss alerts after 3 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.classList.remove('show'); // Hides the alert
            });
        }, 3000);
    </script>




    <!-- inbox ends here -->




    <footer class="text-white text-center py-3" style="background-color: darkgreen;">
        <p>&copy; 2024 Bus Card Management System. All Rights Reserved.</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="scripts.js"></script>

</body>

</html>