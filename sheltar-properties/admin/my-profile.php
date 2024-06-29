<?php
include ("./includes/header.php");
include ("./includes/sidebar.php");
include ("./includes/navbar.php");

include ("./upload.php");

include_once './includes/config.php';
include_once './includes/mysqli_connection.php';

$userId = $_SESSION["id"];
$query = "SELECT user_id FROM verified_agents WHERE user_id = $userId";

$verified = 'Not Verified';

if ($stmt1 = mysqli_prepare($conn, $query)) {

    if (mysqli_stmt_execute($stmt1)) {
        mysqli_stmt_store_result($stmt1);

        if (mysqli_stmt_num_rows($stmt1) > 0) {
            mysqli_stmt_bind_result($stmt1, $user_id);

            if (mysqli_stmt_fetch($stmt1)) {
                $verified = 'Verified';
            }
        } else {
            $verified = 'Not Verified';
        }
    }
    mysqli_stmt_close($stmt1);
}

$query2 = "SELECT profileImage FROM users WHERE user_id = $userId";

$userProfile = null;

if ($stmt2 = mysqli_prepare($conn, $query2)) {

    if (mysqli_stmt_execute($stmt2)) {
        mysqli_stmt_store_result($stmt2);

        if (mysqli_stmt_num_rows($stmt2) > 0) {
            mysqli_stmt_bind_result($stmt2, $profileImg);

            if (mysqli_stmt_fetch($stmt2)) {
                $userProfile = $profileImg;
                $_SESSION["profile"] = $profileImg;
            }
        } else {
            $userProfile = null;
        }
    }
    mysqli_stmt_close($stmt2);
}
?>

<!-- <style>
    .profile-card {
        position: relative;
        width: 200px;
    }

    .profile-image-container {
        position: relative;
        overflow: hidden;
        border-radius: 50%;
        width: 100px;
        height: 100px;
    }

    .profile-image {
        display: block;
        width: 100%;
        height: auto;
    }

    .profile-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: rgba(0, 0, 0, 0.5);
        /* Adjust opacity as needed */
        opacity: 0;
        /* Initially hidden */
        transition: opacity 0.3s ease;
    }

    .profile-overlay a {
        color: #fff;
        text-decoration: none;
        padding: 8px 16px;
        border: 1px solid #fff;
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }

    .profile-overlay a:hover {
        background-color: #fff;
        color: #333;
    }

    .profile-card:hover .profile-overlay {
        opacity: 1;
        /* Show overlay on hover */
    }
</style> -->

<style>
    .profile-card {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .profile-image-container {
        position: relative;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
    }

    .profile-image {
        width: 100%;
        height: auto;
    }

    .profile-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        text-align: center;
        padding: 8px 0;
        opacity: 1;
        transition: opacity 0.3s ease;
    }

    .profile-image-container:hover .profile-overlay {
        opacity: 1;
    }

    .profile-overlay a {
        color: #fff;
        text-decoration: none;
    }

    .profile-details {
        margin-top: 20px;
        text-align: center;
    }

    .profile-info {
        margin-bottom: 10px;
    }

    .profile-info label {
        font-weight: bold;
    }

    .profile-button {
        margin-top: 20px;
    }
</style>


<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Manage Profile</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 d-flex justify-content-center">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">My Profile</h6>
                        <div class="profile-card">
                            <div class="profile-image-container">
                                <img src="../uploads/profile-images/<?php if (isset($_SESSION["profile"])) {
                                    echo htmlspecialchars($_SESSION["profile"]);
                                } else {
                                    echo "user.png";
                                } ?>" class="profile-image" alt="Profile Image">
                                <div class="profile-overlay">
                                    <a href="#" class="btn btn-primary update-image" data-bs-toggle='modal'
                                        data-bs-target='#exampleModalCenter'
                                        data-user-id="<?php echo htmlspecialchars($userId); ?>">Update</a>
                                </div>
                            </div>
                        </div>
                        <div class="profile-details">
                            <div class="profile-info">
                                <label>Name:</label>
                                <span><?php echo htmlspecialchars($_SESSION["fullname"]); ?></span>
                            </div>
                            <div class="profile-info">
                                <label>Contact:</label>
                                <span><?php echo htmlspecialchars($_SESSION["phone"]); ?></span>
                            </div>
                            <div class="profile-info">
                                <label>Email:</label>
                                <span><?php echo htmlspecialchars($_SESSION["email"]); ?></span>
                            </div>

                            <div class="profile-button">
                                <button class="btn btn-outline-primary change-password" data-bs-toggle='modal'
                                    data-bs-target='#exampleModalCenter'
                                    data-user-id="<?php echo htmlspecialchars($userId); ?>">Change Password</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Manage Profile</h5>
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
        const updateImage = document.querySelector('.update-image');
        const updatePassword = document.querySelector('.change-password');

        updateImage.addEventListener('click', function (event) {
            event.preventDefault();

            const userId = this.getAttribute('data-user-id');

            const editModalTitle = document.getElementById('exampleModalCenterTitle');
            const editModalBody = document.querySelector('.modal-body');

            editModalTitle.textContent = "Update Profile Image";
            editModalBody.innerHTML = `
                    <div class="card">
                        <div class="card-body">
                        <form enctype="multipart/form-data" method="post" id="update-profile-image">
                            <div class="mb-3">
                            <input type="hidden" name="userId" id="userId" value="${userId}">
                            <label for="name" class="form-label">Choose profile picture</label>
                            <input id="profileImage" class="form-control" name="profileImage" type="file" required >
                            </div>
                            <input class="btn btn-primary" type="submit" name="submit" value="Update">
                        </form>
                        </div>
                    </div>`;

            const profileForm = document.getElementById('update-profile-image');

            profileForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(profileForm)

                $.ajax({
                    type: 'POST',
                    url: './update-profile',
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

        updatePassword.addEventListener('click', function (event) {
            event.preventDefault();

            const userId = this.getAttribute('data-user-id');

            const editModalTitle = document.getElementById('exampleModalCenterTitle');
            const editModalBody = document.querySelector('.modal-body');

            editModalTitle.textContent = "Update Password";
            editModalBody.innerHTML = `
                    <div class="card">
                        <div class="card-body">
                        <form enctype="multipart/form-data" method="post" id="update-password">
                            <div class="mb-3">
                            <input type="hidden" name="userId" id="userId" value="${userId}">
                            <label for="password" class="form-label">Current Password</label>
                            <input id="currentPassword" class="form-control" name="currentPassword" type="password" required >
                            </div>
                            <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input id="newPassword" class="form-control" name="newPassword" type="password" required >
                            </div>
                            <div class="mb-3">
                            <label for="password" class="form-label">Confirm New Password</label>
                            <input id="confirmNewPassword" class="form-control" name="confirmNewPassword" type="password" required >
                            </div>
                            <input class="btn btn-primary" type="submit" name="submit" value="Change">
                        </form>
                        </div>
                    </div>`;

            const passwordForm = document.getElementById('update-password');

            passwordForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(passwordForm)

                $.ajax({
                    type: 'POST',
                    url: './update-password',
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

</script>


<?php
include ("./includes/footer_end.php");
?>