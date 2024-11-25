<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library ID</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/library-logo.png" type="png">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>


<?php session_start() ?>

<body>
    <div class="wrapper">

        <div class="container-top">
            <?php include 'navbar.php'; ?>
        </div>

        <div id="overlay" class="overlay"></div>

        <div class="row-body-padding-0">

            <div class="container-sidebar" id="sidebar">
                <?php include 'sidebar.php'; ?>
            </div>


            <div class="container-content">

                <div class="about-banner">
                    <div class="transparent-about">
                        <div class="about-title-white">
                            Library ID Application
                        </div>
                        <div class="about-subtitle-white">
                            Discover the requirements and procedures for obtaining your Library ID.
                        </div>
                        <div class="about-subtitle-white">
                            Learn how to apply, the necessary documents, and fill out the application form.
                        </div>
                    </div>
                </div>


                <!-- loading animation -->
                <div id="loading-overlay">
                    <div class="spinner"></div>
                </div>


                <div class="body-navbar">
                    <div class="body-navbar-contents" onclick="scrollToSection('requirements')">Requirements</div>
                    <div class="body-navbar-contents">|</div>
                    <div class="body-navbar-contents" onclick="scrollToSection('procedures')">Procedures</div>
                    <div class="body-navbar-contents">|</div>
                    <div class="body-navbar-contents" onclick="scrollToSection('application-form')">Application Form</div>
                </div>


                <div class="application-contents" id="requirements">

                    <div class="application-title">
                        Requirements in Applying for Library ID
                    </div>

                    <div class="row row-around">

                        <div class="application-requirements-contents">
                            <div class="application-subtitle">MINOR APPLICANT</div>
                            <div class="application-description">
                                1. Signature of Parent/Guardian and any Valid ID <br> <br>
                                2. Signature of Guarantor (Teacher, City Officials, Barangay Officials and City Employees) <br> <br>
                                3. One (1) ID picture (1x1 size)
                            </div>
                        </div>

                        <div class="application-requirements-contents">
                            <div class="application-subtitle">ADULT APPLICANT</div>
                            <div class="application-description">
                                1. Any Valid ID of applicant<br> <br>
                                2. Signature of Guarantor (Government Officials/ employees, Person of good moral character) <br> <br>
                                3. One (1) ID picture (1x1 size)
                            </div>
                        </div>

                    </div>


                </div>


                <div class="application-procedure-contents" id="procedures">

                    <div class="application-title">
                        Procedures in Applying for Library ID
                    </div>

                    <div class="application-procedure-container">
                        <div class="application-procedure-infographics">
                            <img src="../images/procedure_infographics.png" alt="" class="image">
                        </div>
                    </div>


                </div>


                <div class="application-form-container" id="application-form">

                    <div class="application-title">
                        Application Form in Applying for Library ID
                    </div>

                    <div class="application-button-container">
                        <div class="application-button">
                            <button class="application-nav-button" id="prevButton" disabled>&lt;</button>

                            <div>

                                <div class="page-title" id="pageTitle">Page 1 Checklist</div>

                                <a href="library_id_print.php" style="display: flex; justify-content: center;">
                                    <button class="button-download">Download</button>
                                </a>
                            </div>

                            <button class="application-nav-button" id="nextButton">&gt;</button>
                        </div>
                    </div>

                    <div class="application-form-contents">

                        <div class="application-checklist-container visible" id="checklist-form">

                            <div class="application-form-50">
                                <div class="row-center">
                                    <div class="form-label-20-line">Application for Library Card</div>
                                </div>

                                <div class="form-header-checklist">
                                    <div class="form-logo-checklist">
                                        <img src="../images/makaticity-logo.png" alt="" class="image">
                                    </div>
                                    <div class="header-title-checklist">CHECKLIST</div>
                                    <div class="check-logo-checklist">
                                        <img src="../images/check-logo.png" alt="" class="image">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="box-checklist"></div>
                                    <div class="form-label-20">FILLED-UP APPLICATION FORM</div>
                                </div>

                                <div class="checklist-body">
                                    <div class="form-label-20">VALID I.D.</div>


                                    <div class="checklist-body-contents">

                                        <div class="row">
                                            <div class="box-checklist"></div>
                                            <div class="form-label-20">STUDENT I.D.</div>
                                        </div>

                                        <div class="row">
                                            <div class="box-checklist"></div>
                                            <div class="form-label-20">GOVERNMENT ISSUED I.D.</div>
                                        </div>

                                    </div>

                                    <div class="form-label-20">ISSUED BY:</div>
                                    <div class="form-input-box"> </div>

                                </div>

                                <div>
                                    <div class="form-input-box"> </div>

                                    <div style="margin-left: 10px;">
                                        <div class="form-label-20">PRINTED NAME OVER SIGNATURE</div>
                                        <div class="input-container">
                                            <div class="form-label-20">DATE</div>
                                            <div class="form-input-box-small"> </div>
                                        </div>
                                    </div>

                                </div>


                            </div>
                        </div>

                        <div class="application-form hidden" id="application-forms">

                            <div class="form-header">
                                <div class="form-logo">
                                    <img src="../images/makaticity-logo.png" alt="" class="image">
                                </div>

                                <div class="header-title">APPLICATION FOR MAKATI CITY LIBRARY IDENTIFICATION CARD</div>

                                <div class="form-logo">
                                    <img src="../images/makaticity-logo.png" alt="" class="image">
                                </div>
                            </div>

                            <div class="form-body">
                                <div class="form-salutation">Sir/ Madam;</div>
                                <div class="form-paragraph">
                                    I, the undersigned,
                                    <span class="underline-name"></span>,<span class="underline-age"></span>years old, hereby, apply for a Membership Card.
                                    I have carefully read the library rules and regulations.
                                    Understand that this identification card is non-transferable.
                                    I shall observe the duties of advising the library authorities of the change of my address and reporting immediately in case my ID is lost, mislaid or stolen.
                                </div>

                                <div class="form-row-between">

                                    <div class="form-guarantor-container">

                                        <div class="guarantor-box">
                                            <div class="font-size-14-bold">GUARANTOR'S INFORMATION</div>
                                            <div class="font-size-12">(Guarantor is liable either to pay or replace lost/unreturned books of the applicant)</div>
                                        </div>

                                        <div>
                                            <div class="form-input-box"> </div>
                                            <div class="form-label">Name of Guarantor</div>
                                        </div>

                                        <div>
                                            <div class="form-input-box"> </div>
                                            <div class="form-label">Address</div>
                                        </div>

                                        <div>
                                            <div class="form-input-box"> </div>
                                            <div class="form-label">Company/School Address & Contact No.</div>
                                        </div>

                                        <div>
                                            <div class="form-input-box"> </div>
                                            <div class="form-label">Personal Contact Number</div>
                                        </div>

                                        <div>
                                            <div class="form-input-box"> </div>
                                            <div class="form-label">Signature</div>
                                        </div>


                                        <div>
                                            <div class="input-container">
                                                <div class="form-label-14">ID Card No.</div>
                                                <div class="form-input-box-small"></div>
                                            </div>

                                            <div class="input-container">
                                                <div class="form-label-14">Date of Issuance</div>
                                                <div class="form-input-box-small"></div>
                                            </div>

                                            <div class="input-container">
                                                <div class="form-label-14">Validity</div>
                                                <div class="form-input-box-small"></div>
                                            </div>

                                            <div class="input-container">
                                                <div class="form-label-14">Processed by:</div>
                                                <div class="form-input-box-small"></div>
                                            </div>
                                        </div>






                                    </div>

                                    <div class="form-patrons-container">

                                        <div>
                                            <div class="form-closing">
                                                Very respectfully yours,
                                            </div>

                                            <div class="form-label">APPLICANT'S INFORMATION</div>

                                        </div>


                                        <div>
                                            <div class="form-input-box"> </div>
                                            <div class="form-label">Name of Applicant</div>
                                        </div>

                                        <div>
                                            <div class="form-input-box"> </div>
                                            <div class="form-label">Address</div>
                                        </div>

                                        <div>
                                            <div class="form-input-box"> </div>
                                            <div class="form-label">Company/School Address & Contact No.</div>
                                        </div>

                                        <div>
                                            <div class="form-input-box"> </div>
                                            <div class="form-label">Personal Contact Number</div>
                                        </div>

                                        <div>
                                            <div class="form-input-box"> </div>
                                            <div class="form-label">Signature</div>
                                        </div>


                                        <div>
                                            <div class="input-container">
                                                <div class="form-label-14">Birthdate</div>
                                                <div class="form-input-box-small"></div>
                                            </div>

                                            <div class="input-container">
                                                <div class="form-label-14">Gender</div>
                                                <div class="form-input-box-small"></div>
                                            </div>

                                            <div class="input-container">
                                                <div class="form-label-14">Email</div>
                                                <div class="form-input-box-small"></div>
                                            </div>

                                            <div class="form-label-italic">If below 18 yrs.old</div>

                                            <div class="input-container">
                                                <div class="form-label-14">Parent/Guardian</div>
                                                <div class="form-input-box-small"></div>
                                            </div>

                                            <div class="input-container">
                                                <div class="form-label-14">Relationship</div>
                                                <div class="form-input-box-small"></div>
                                            </div>

                                            <div class="input-container">
                                                <div class="form-label-14">Contact No.</div>
                                                <div class="form-input-box-small"></div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="form-footer">
                                    <div class="form-label-14">LENI C. FERMIN</div>
                                    <div class="form-label-14">Officer-In-Charge</div>
                                    <div class="form-label-14">Education Department</div>
                                </div>

                            </div>


                        </div>


                        <script>
                            // Function to set the height of the application-form-container
                            function setApplicationFormContainerHeight() {
                                const applicationFormContainer = document.getElementById('application-form');
                                if (document.getElementById('application-forms').classList.contains('visible')) {
                                    applicationFormContainer.style.height = '1500px'; // Set height when visible
                                } else {
                                    applicationFormContainer.style.height = ''; // Reset height when not visible
                                }
                            }

                            // Event listener for the Next button
                            document.getElementById('nextButton').addEventListener('click', function() {
                                document.getElementById('checklist-form').classList.remove('visible');
                                document.getElementById('checklist-form').classList.add('hidden');

                                document.getElementById('application-forms').classList.remove('hidden');
                                document.getElementById('application-forms').classList.add('visible');

                                document.getElementById('pageTitle').innerText = 'Page 2 Application Form';
                                document.getElementById('prevButton').disabled = false;
                                document.getElementById('nextButton').disabled = true;

                                setApplicationFormContainerHeight(); // Set height when moving to application forms
                            });

                            // Event listener for the Previous button
                            document.getElementById('prevButton').addEventListener('click', function() {
                                document.getElementById('application-forms').classList.remove('visible');
                                document.getElementById('application-forms').classList.add('hidden');

                                document.getElementById('checklist-form').classList.remove('hidden');
                                document.getElementById('checklist-form').classList.add('visible');

                                document.getElementById('pageTitle').innerText = 'Page 1 Checklist';
                                document.getElementById('prevButton').disabled = true;
                                document.getElementById('nextButton').disabled = false;

                                setApplicationFormContainerHeight(); // Set height when moving back to checklist
                            });

                            // Initial height setup
                            setApplicationFormContainerHeight(); // Call this to set the initial height based on visibility
                        </script>


                    </div>

                </div>

            </div>


        </div>



        <div class="container-footer">

            <?php include 'footer.php'; ?>

        </div>


    </div>
</body>



</html>


<script src="js/banner.js"></script>
<script src="js/sidebar.js"></script>
<script src="js/loading-animation.js"></script>




<script>
    function scrollToSection(sectionId) {
        const section = document.getElementById(sectionId);

        if (section) {
            const sectionPosition = section.getBoundingClientRect().top + window.scrollY;

            const offset = 100;

            window.scrollTo({
                top: sectionPosition - offset,
                behavior: 'smooth'
            });
        }
    }
</script>