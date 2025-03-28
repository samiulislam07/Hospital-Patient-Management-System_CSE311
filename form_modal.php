<?php
include 'config.php';

// Fetch available tests
$testQuery = "SELECT test_name FROM test";
$testResult = $con->query($testQuery);
$testList = [];
while ($row = $testResult->fetch_assoc()) {
    $testList[] = $row['test_name'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="css/popup.css">
    <!-- External Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

</head>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<body>
    <!-- Order Test Pop-up Form -->
    <div id="orderTestPopup" class="popup">
        <button class="pclose-btn">&times;</button> <!-- Close Button -->
        <h4 id="orderTestTitle" class="text-left">Ordering Test for ""</h4>
        <form id="orderTestForm" action="process_form.php" method="POST">
            <input type="hidden" id="testPatientId" name="patientId">

            <!-- Search Field Only -->
            <div class="form-group search-container position-relative">
                <input type="text" id="testSearch" class="form-control" placeholder="Type to search tests..." autocomplete="off">
                <div id="testDropdown" class="dropdown-menu"></div>
            </div>

            <div class="form-group">
                <label>Selected Tests:</label>
                <textarea id="selectedTests" name="selectedTests" class="form-control" rows="2" readonly></textarea>
            </div>

            <button type="submit" id="orderTestBtn" class="psubmit-btn" disabled>Order</button>
        </form>
    </div>

    <!-- JavaScript for Order Test -->
    <script>
        $(document).ready(function() {
            const testList = <?= json_encode($testList, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
            let selectedTests = [];
            let dropdown = $("#testDropdown");

            // Show Order Test Pop-up
            $(".order-form-btn").click(function() {
                let patientId = $(this).data("patient-id");
                $("#testPatientId").val(patientId);
                $("#orderTestTitle").text("Ordering Test for " + patientId);
                $("#testSearch").val(""); // Clear search field
                $("#selectedTests").val("");
                selectedTests = [];
                $("#orderTestBtn").prop("disabled", true);
                $("#orderTestPopup").show();
            });

            // Close Pop-up
            $(".pclose-btn").click(function() {
                $("#orderTestPopup").hide();
                dropdown.hide();
            });

            // Filter Tests as User Types
            $("#testSearch").on("input", function() {
                let searchText = $(this).val().toLowerCase();
                let filteredTests = testList.filter(test => test.toLowerCase().includes(searchText));
                populateDropdown(filteredTests);
            });

            // Populate Dropdown
            function populateDropdown(tests) {
                dropdown.empty();
                if (tests.length) {
                    tests.forEach(test => {
                        dropdown.append(`<a class="dropdown-item test-option" href="#">${test}</a>`);
                    });
                    dropdown.show();
                } else {
                    dropdown.hide();
                }
            }

            // Select Test from Dropdown
            $(document).on("click", ".test-option", function(e) {
                e.preventDefault();
                let testName = $(this).text();
                if (!selectedTests.includes(testName)) {
                    selectedTests.push(testName);
                    $("#selectedTests").val(selectedTests.join(", "));
                    $("#orderTestBtn").prop("disabled", false);
                }
                $("#testSearch").val("");
                dropdown.hide();
            });

            // Hide Dropdown on Outside Click
            $(document).click(function(e) {
                if (!$(e.target).closest(".search-container").length) {
                    dropdown.hide();
                }
            });

            // Submit Order Test Form via AJAX
            $("#orderTestForm").submit(function(event) {
                event.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    url: "process_form.php",
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        alert(response.message);
                        if (response.status === "success") {
                            $("#orderTestPopup").hide();
                            $("#testSearch").val(""); // Clear search after ordering
                            window.location.hash = "#list-patients";
                        }
                    },
                    error: function() {
                        alert("Error processing test order.");
                    }
                });
            });
        });
    </script>


    <!-- Treatment Plan Pop-up Form -->
    <div id="popupForm" class="popup">
        <button class="pclose-btn">&times;</button> <!-- Close Button -->
        <h4 id="popupTitle">Treatment Plan for " "</h4>
        <form id="prescriptionForm" action="process_form.php" method="POST">
            <input type="hidden" id="patientId" name="patientId">
            <div class="pform-group">
                <label for="dosage">Dosage:</label>
                <textarea id="dosage" name="dosage" class="form-control" rows="2"></textarea>
            </div>
            <div class="pform-group">
                <label for="suggestion">Suggestion:</label>
                <textarea id="suggestion" name="suggestion" class="form-control" rows="2" required></textarea>
            </div>
            <button type="submit" id="submitBtn" class="psubmit-btn">Prescribe</button>
        </form>
    </div>


    <!-- JavaScript for Prescribe Treatment Plan-->
    <script>
        $(document).ready(function() {
            // Open pop-up when button is clicked
            $(".treatment-form-btn").click(function() {
                var patientId = $(this).data("patient-id");
                $("#patientId").val(patientId);
                $("#popupTitle").text("Treatment Plan for " + patientId);
                $("#popupForm").show();
            });

            // Close pop-up when ✖ button is clicked
            $(".pclose-btn").click(function() {
                $("#popupForm").hide();
                $("#dosage").val("");
                $("#suggestion").val("");
            });

            // Submit form via AJAX
            $("#prescriptionForm").submit(function(event) {
                event.preventDefault();

                $.ajax({
                    url: "process_form.php",
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response) {
                        if (response.status === "success") {
                            alert(response.message);
                            $("#popupForm").hide();
                            window.location.hash = "#list-patients"; // Redirect here.
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert("Error processing treatment plan.");
                    }
                });
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

</body>

</html>