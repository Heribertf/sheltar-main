<?php
include ("./includes/header.php");
include ("./includes/sidebar.php");
include ("./includes/navbar.php");
?>

<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Agent Verificaton</li>
        </ol>
    </nav>

    <div class="row">
        <div id="loader">
            <div class="loader"></div>
        </div>

        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Rejected Verificaton Requests</h6>
                    <p class="text-muted mb-3">List of all rejected verification requests</p>
                    <div class="table-responsive">
                        <table id="dataTableUser" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Agent No / Plot No</th>
                                    <th>Contact</th>
                                    <th>Support Doc</th>
                                    <th>Type</th>
                                    <th>Request Date</th>
                                    <th>Status</th>
                                    <th>Comment</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include_once './includes/config.php';
                                include_once './includes/mysqli_connection.php';
                                $i = 1;

                                $sql = "SELECT v.id, v.user_id, v.name, v.number, v.contact, v.document, v.type, DATE_FORMAT(v.submitted, '%d-%b-%Y') AS formattedDate, v.comment,
                                        CASE 
                                            WHEN v.status = 1 THEN 'Verified'
                                            ELSE 'Not Verified' 
                                        END AS verificatonStatus,
                                        CONCAT(u.first_name, ' ', u.last_name) AS agent_name, u.phone, u.email
                                        FROM verification v
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        WHERE v.status = 0 AND v.delete_flag = 0 ";

                                if ($stmt = mysqli_prepare($conn, $sql)) {
                                    mysqli_stmt_execute($stmt);

                                    // Bind the results to variables
                                    mysqli_stmt_bind_result($stmt, $requestId, $userId, $agentName, $number, $contact, $supportDocument, $userType, $submissionDate, $comment, $verificationStatus, $registeredName, $registeredPhone, $registeredEmail);

                                    while (mysqli_stmt_fetch($stmt)) {
                                        if ($verificationStatus == 'Verified') {
                                            $statusClass = 'success';
                                        } else {
                                            $statusClass = 'secondary';
                                        }

                                        echo "<tr>";
                                        echo "<td>" . $i++ . "</td>";
                                        echo "<td>" . htmlspecialchars($agentName) . "</td>";
                                        echo "<td>" . htmlspecialchars($number) . "</td>";
                                        echo "<td>" . htmlspecialchars($contact) . "</td>";
                                        // echo "<td>" . htmlspecialchars($supportDocument) . "</td>";
                                        echo "<td> 
                                                <a href='" . htmlspecialchars($supportDocument) . "' class='btn btn-info'>
                                                    <h5>View Document</h5>
                                                </a> 
                                            </td>";
                                        echo "<td>" . htmlspecialchars($userType) . "</td>";
                                        echo "<td>" . htmlspecialchars($submissionDate) . "</td>";
                                        if ($verificationStatus === 'Verified') {
                                            echo "<td><span class='badge border border-success text-success'>" . htmlspecialchars($verificationStatus) . "</span></td>";
                                        } else {
                                            echo "<td><span class='badge border border-secodary text-secondary'>" . htmlspecialchars($verificationStatus) . "</span></td>";
                                        }
                                        echo "<td>" . htmlspecialchars($comment) . "</td>";
                                        echo "<td>
                                                <div class='dropdown'>
                                                    <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-expanded='false'>
                                                        Actions
                                                    </button>
                                                    <ul class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                                        <li><a class='dropdown-item view-agent' href='#' data-bs-toggle='modal' data-bs-target='#exampleModalCenter' data-agent-name='" . htmlspecialchars($registeredName) . "'
                                                        data-agent-contact='" . htmlspecialchars($registeredPhone) . "' data-agent-email='" . htmlspecialchars($registeredEmail) . "' data-agent-status='" . htmlspecialchars($verificationStatus) . "' data-class='" . htmlspecialchars($statusClass) . "'>View Agent</a></li>
                                                    </ul>
                                                </div>
                                            </td>";
                                        echo "</tr>";
                                    }

                                    mysqli_stmt_close($stmt);
                                } else {
                                    echo "Error in prepared statement: " . mysqli_error($conn);
                                }

                                mysqli_close($conn);
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Verify Agent</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<?php
include ("./includes/footer.php");
?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editLinks = document.querySelectorAll('.update-agent');
        const viewLinks = document.querySelectorAll('.view-agent');

        viewLinks.forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault();

                const agentName = this.getAttribute('data-agent-name');
                const agentContact = this.getAttribute('data-agent-contact');
                const agentEmail = this.getAttribute('data-agent-email');
                const agentStatus = this.getAttribute('data-agent-status');
                const statusClass = this.getAttribute('data-class');

                const editModalTitle = document.getElementById('exampleModalCenterTitle');
                const editModalBody = document.querySelector('.modal-body');

                editModalTitle.textContent = "View Registered Agent Details";
                editModalBody.innerHTML = `
                <div class="card">
                    <div class="card-body">
                            <div class="mb-3">
                                <p class="card-text mb-3"><span class="card-title">Agent Name: </span>${agentName}</p>
                                <p class="card-text mb-3"><span class="card-title">Agent Contact: </span>${agentContact}</p>
                                <p class="card-text mb-3"><span class="card-title">Agent Email: </span>${agentEmail}</p>
                                <p class="card-text mb-3"><span class="card-title">Agent Status: </span><span class="badge rounded-pill border border-${statusClass} text-${statusClass}">${agentStatus}</span></p>

                            </div>
                    </div>
                </div>`;
            });
        });
    });

</script>

<?php
include ("./includes/footer_end.php");
?>