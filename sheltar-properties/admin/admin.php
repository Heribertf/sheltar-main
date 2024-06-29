<?php
include("./includes/header.php");
include("./includes/sidebar.php");
include("./includes/navbar.php");
?>

<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">PurplePay Agencies</a></li>
            <li class="breadcrumb-item active" aria-current="page">Administrators</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">System Administrators</h6>
                    <p class="text-muted mb-3">List of all current system administrators</p>
                    <div class="table-responsive">
                        <table id="dataTableAdmin" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Date Registered</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include_once 'mysqli_connection.php';
                                $i = 1;

                                $sql = "SELECT adminId, username, email, DATE_FORMAT(date_registered, '%d-%b-%Y') AS formattedDate, IF(verified = 1, 'Verified', 'Not-Verified') AS verificationStatus 
                                        FROM admin 
                                        WHERE delete_flag = 0";

                                if ($stmt = mysqli_prepare($conn, $sql)) {
                                    mysqli_stmt_execute($stmt);

                                    // Bind the results to variables
                                    mysqli_stmt_bind_result($stmt, $adminId, $username, $email, $formattedDate, $verificationStatus);

                                    while (mysqli_stmt_fetch($stmt)) {
                                        echo "<tr>";
                                        echo "<td>" . $i++ . "</td>";
                                        echo "<td>" . htmlspecialchars($username) . "</td>";
                                        echo "<td>" . htmlspecialchars($email) . "</td>";
                                        echo "<td>" . htmlspecialchars($formattedDate) . "</td>";
                                        // echo "<td>" . htmlspecialchars($verificationStatus) . "</td>";
                                        if ($verificationStatus === 'Verified') {
                                            echo "<td><span class='badge border border-success text-success'>" . htmlspecialchars($verificationStatus) . "</span></td>";
                                        } else {
                                            echo "<td><span class='badge border border-secodary text-secondary'>" . htmlspecialchars($verificationStatus) . "</span></td>";
                                        }
                                        echo "<td>
                                                <a href='#' class='btn btn-primary edit-admin' data-bs-toggle='modal' data-bs-target='#exampleModalCenter'
                                                    data-admin-id='" . htmlspecialchars($adminId) . "' data-admin-username='" . htmlspecialchars($username) . "'
                                                    data-admin-email='" . htmlspecialchars($email) . "' data-admin-date='" . htmlspecialchars($formattedDate) . "'>
                                                    <h5>Edit</h5>
                                                </a>
                                                <a href='#' class='btn btn-danger delete-admin'  data-admin-id='" . htmlspecialchars($adminId) . "'>
                                                    <h5>Delete</h5>
                                                </a>
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
                        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Administrator Details</h5>
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
include("./includes/footer.php");
?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editLinks = document.querySelectorAll('.edit-admin');

        editLinks.forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault();

                const editAdminId = this.getAttribute('data-admin-id');
                const editAdminUsername = this.getAttribute('data-admin-username');
                const editAdminEmail = this.getAttribute('data-admin-email');
                const editAdminDate = this.getAttribute('data-admin-date');

                const editModalTitle = document.getElementById('exampleModalCenterTitle');
                const editModalBody = document.querySelector('.modal-body');

                editModalTitle.textContent = "Edit Administrator Details";
                editModalBody.innerHTML = `
                    <div class="card">
                        <div class="card-body">
                        <h4 class="card-title">Updating: ${editAdminUsername}</h4>
                        <form enctype="multipart/form-data" method="post" id="editAdminForm">
                            <div class="mb-3">
                            <input type="hidden" name="editAdminId" id="editAdminId" value="${editAdminId}">
                            <label for="name" class="form-label">Username</label>
                            <input id="editAdminUsername" class="form-control" name="editAdminUsername" type="text" value="${editAdminUsername}" >
                            </div>
                            <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="editAdminEmail" class="form-control" name="editAdminEmail" type="email" value="${editAdminEmail}" >
                            </div>
                            <input class="btn btn-primary" type="submit" name="submit" value="Save Changes">
                        </form>
                        </div>
                    </div>`;

                const editAdminForm = document.getElementById('editAdminForm');

                editAdminForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const formData = new FormData(editAdminForm)

                    $.ajax({
                        type: 'POST',
                        url: './update_admin.php',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function (response) {
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
        $('.delete-admin').on('click', function (e) {
            e.preventDefault();

            const adminId = this.getAttribute('data-admin-id');
            console.log(adminId);

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger me-2'
                },
                buttonsStyling: false,
            })

            swalWithBootstrapButtons.fire({
                title: 'Warning!',
                text: "Are you sure you want to delete this administrator?",
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
                        url: './delete_admin.php',
                        data: {
                            adminId: adminId
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Administrator Deleted',
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
include("./includes/footer_end.php");
?>