<?php
/**
 * @var $link
 * @var $array_left
 * @var $array_right
 */
/**
 * @var $link
 * @var $hospital_name
 * @var $hospital_address
 */
require_once "configs/config.php";
include_once("index_controller.php");

// pull data from database departments
$mysql = "SELECT dep_name FROM departments";
$result = $link->query($mysql);

//make array to stor all the info
$array = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        //store info on each index for each row
        $array[] = $row['dep_name'];
    }
}

# show name of the hospital
$hospital_name = $hospital_address = "";
$sql_getHospitalName = "SELECT * FROM hospital";
$stmt_getHN = $link->query($sql_getHospitalName);
if ($stmt_getHN->num_rows > 0) {
    while ($row = $stmt_getHN->fetch_assoc()) {
        $hospital_name = $row['hospital_name'];
        $hospital_address = $row['hospital_address'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Hospital VY</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Srisakdi" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css"/>
    <link rel="stylesheet" href="assets/typography.css"/>
    <link rel="stylesheet" href="assets/responsive.css"/>

</head>

<body>

<header>
    <?php
    include("header/header.php");
    ?>
    <section class="hr-line">
        <hr>
    </section>
    <section class="hero-section" id="home">
        <article class="hero-section-content">
            <h2 class="hero-section-content-title"><?php echo $hospital_name; ?></h2>
            <hr>

            <h3 class="hero-section-content-additional">As evidenced by our long history of medical care, our hospital
                continues to set the standard for medical treatments and offers one of the most comprehensive arrays of
                services and programs.</h3>
            <a href="index.php #dep">
                <button class="btn_send">See more</button>
            </a>
        </article>
        <article class="hero-section-img">
            <img src="pictures/all_docs.png" alt="hero-section-img"/>
        </article>
    </section>
</header>

<main>
    <section class="benefits">

        <article class="benefits-card">
            <header class="benefits-card-header">
                <img class="inverted" src="pictures/test.png" alt="benefits1"/>
            </header>
            <article class="benefits-card-body">
                <h4 class="benefits-title">Precise results</h4>
                <p class="benefits-content">
                    Our experts will make an accurate analysis and find the problem
                </p>
            </article>
        </article>

        <article class="benefits-card">
            <header class="benefits-card-header">
                <img class="inverted" src="pictures/Check results online.png" alt="benefits2"/>
            </header>
            <article class="benefits-card-body">
                <h4 class="benefits-title">Results online</h4>
                <p class="benefits-content">
                    With us you can easily and quickly check your results
                </p>
            </article>
        </article>

        <article class="benefits-card">
            <header class="benefits-card-header">
                <img class="inverted" src="pictures/Modern equipment.png" alt="benefits3"/>
            </header>
            <article class="benefits-card-body">
                <h4 class="benefits-title">Modern equipment</h4>
                <p class="benefits-content">
                    We have the latest medical developments and quality service
                </p>
            </article>
        </article>
    </section>


    <section class="wrap-all-tests" id="dep">
        <article>
            <article class="department_header ">
                <h1 class="department-title">Departments</h1>
            </article>
        </article>
        <article class="other-tests">
            <ol>
                <article class="two-col-tests">
                    <div class="left-test">
                        <?php
                        foreach ($array_left as $val) {
                            echo "<li>" . $val['dep_name'] . "</li>";
                        }
                        ?>
                    </div>
                    <div class="right-test">
                        <?php
                        foreach ($array_right as $val) {
                            echo "<li>" . $val['dep_name'] . "</li>";
                        }
                        ?>
                    </div>
                </article>
            </ol>
        </article>
    </section>


    <section class="about" id="about">
        <article class="about-header ">
            <h1 class="about-title">About Us</h1>
        </article>
        <article class="about-content">
            <article class="about-text">
                <h2 class="about-text-title">Who are we?</h2>
                <p class="about-text-paragraph">
                    We are one of the best hospitals in South London. Hospital VY has grown to become one of the
                    most
                    advanced and comprehensive healthcare institutions in the region.
                    We have some of the best doctors in the country.
                </p>

                <h2 class="about-text-title">Our Mission</h2>
                <p class="about-text-paragraph">
                    Quality patient care is our priority. Providing excellent clinical and service quality, offering
                    compassionate care, and supporting research and medical education are essential to our mission.
                    This
                    mission is founded in the ethical and cultural precepts of the Judaic tradition, which inspire
                    devotion to the art and science of healing and to the care we give our patients and staff.
                </p>
            </article>
            <article class="about-img">
                <img class="inverted" src="pictures/about-us.png" alt="About us illustration"/>
            </article>
        </article>
    </section>

    <section class="contacts-wapper" id="contacts">
        <article class="contacts-header">
            <h1 class="contacts-title">Contacts</h1>
        </article>

        <article class="contacts">
            <article class="contacts-details">
                <article class="contacts-details-card ">
                    <i class="fas fa-map-marker-alt"></i>
                    <h4 class="contacts-details-card-title">Address</h4>
                    <p class="contacts-details-card-text"><?php echo $hospital_address; ?></p>
                </article>

                <article class="contacts-details-card ">
                    <i class="fas fa-phone-alt"></i>
                    <h4 class="contacts-details-card-title">Phone</h4>
                    <p class="contacts-details-card-text">0887569854</p>
                </article>

                <article class="contacts-details-card ">
                    <i class="far fa-envelope"></i>
                    <h4 class="contacts-details-card-title">E-mail</h4>
                    <p class="contacts-details-card-text">mvyar_lab@gmail.com</p>
                </article>

                <article class="contacts-details-card ">
                    <i class="far fa-clock"></i>
                    <h4 class="contacts-details-card-title">Work time</h4>
                    <p class="contacts-details-card-text">Every day - 24h</p>
                </article>
            </article>
            <article class="contacts-form">
                <form class="form-contacts" action="https://formsubmit.co/mvyarproj@gmail.com" method="POST">
                    <label for="name"></label>
                    <input type="text" id="name" name="name" placeholder="Name:"/>

                    <label for="mail"></label>
                    <input type="email" id="mail" name="mail" placeholder="E-mail:"/>
                    <input type="hidden" name="_next"
                           value="http://localhost/Project_Hospital_VY/index.php">
                    <input type="text" name="subject" placeholder="Subject: ">

                    <label for="message"></label>
                    <textarea id="message" name="message" placeholder="Message..." rows="10"></textarea>

                    <a href="">
                        <button class="btn_send">Send</button>
                    </a>
                </form>
            </article>
        </article>
    </section>
</main>

<hr>
<?php
include("footer/footer.php");
?>

<script src="script.js"></script>
</body>
</html>