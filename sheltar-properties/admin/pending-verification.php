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
                    <h6 class="card-title">Pending Verificatons</h6>
                    <p class="text-muted mb-3">List of all submitted verification requests awaiting approval</p>
                    <div class="alert alert-info" role="alert">
                        Agents submit <strong>Agent Registration Number</strong> while landloards submit <strong>Plot
                            Number</strong>
                    </div>
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
                                        WHERE v.status = 2 AND v.delete_flag = 0 ";

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
                                                        <li><a class='dropdown-item update-agent' href='#' data-bs-toggle='modal' data-bs-target='#exampleModalCenter' data-request-id='" . htmlspecialchars($requestId) . "' data-user-id='" . htmlspecialchars($userId) . "'>Update</a></li>
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


        editLinks.forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault();

                const requestId = this.getAttribute('data-request-id');
                const userId = this.getAttribute('data-user-id');

                const editModalTitle = document.getElementById('exampleModalCenterTitle');
                const editModalBody = document.querySelector('.modal-body');

                editModalTitle.textContent = "Update Agent Verificaton Status";
                editModalBody.innerHTML = `
                <div class="card">
                    <div class="card-body">
                        <form class="forms-sample" enctype="multipart/form-data" method="post" id="update-verification-status">
                            <div class="mb-3">
                                    <input type="hidden" name="update_request_id" value="${requestId}" required readonly>
                                    <input type="hidden" name="request_user" value="${userId}" required readonly>
                                </div>
                            <div class="mb-3">
                                <label for="">Verification Status:</label>
                                <select class="form-control" name="verificationStatus" id="verificationStatus" required>
                                    <option value="">Approve agent or reject verification request</option>
                                    <option value="1">Approve verification request</option>
                                    <option value="0">Reject verification request</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label">Comment (Optional)</label>
                                <textarea class="form-control" id="verificationComment" name="verificationComment" rows="3" maxlength="200"
                                placeholder="Maximum characters accepted is 200 chars"></textarea>
                            </div>
                            <button type="submit" name="submit" value="submit"
                                class="btn btn-primary me-2">Update</button>
                    </form>
                    </div>
                </div>`;


                const updateRequest = document.getElementById('update-verification-status');

                updateRequest.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const formData = new FormData(updateRequest);

                    var loader = document.getElementById("loader");
                    loader.style.display = "block";

                    $.ajax({
                        type: 'POST',
                        url: './update-request',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function (response) {
                            loader.style.display = "none";

                            if (response.success) {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then((result) => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.message
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            loader.style.display = "none";

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An unexpected error occurred.'
                            });
                        }
                    });
                });
            });
        });

    });
    $(document).ready(function () {
        $('.delete-user').on('click', function (e) {
            e.preventDefault();

            const userId = this.getAttribute('dataUserId');
            console.log(userId);

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger me-2'
                },
                buttonsStyling: false,
            })

            swalWithBootstrapButtons.fire({
                title: 'Warning!',
                text: "Are you sure you want to delete this user?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonClass: 'me-2',
                confirmButtonText: 'Yes, delete!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: './delete_user.php',
                        data: {
                            userId: userId
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'User Deleted',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.message
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An unexpected error occurred.'
                            });
                        }
                    });
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Deletion aborted',
                        'error'
                    )
                }
            });
        });
    })
</script>

<?php
include ("./includes/footer_end.php");
?>