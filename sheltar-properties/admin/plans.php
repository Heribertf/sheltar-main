<?php
include ("./includes/header.php");
include ("./includes/sidebar.php");
include ("./includes/navbar.php");

include_once '../connection.php';

$featureQuery = "SELECT feature_id, feature_name FROM features WHERE delete_flag = 0";
$featureResult = $conn->query($featureQuery);

$planFeatures = [];
if ($featureResult->num_rows > 0) {
    while ($featureRow = $featureResult->fetch_assoc()) {
        $planFeatures[] = $featureRow;
    }
}

// Fetch all features for mapping
$features = [];
if ($featureResult->num_rows > 0) {
    $featureResult->data_seek(0);
    while ($featureRow = $featureResult->fetch_assoc()) {
        $features[$featureRow['feature_id']] = $featureRow['feature_name'];
    }
}

$query = "SELECT plan_id, plan_name, plan_price, plan_features, plan_status FROM plans WHERE delete_flag = 0";

if ($stmt = mysqli_prepare($conn, $query)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $planId, $planName, $planPrice, $plan_features, $planStatus);

    $plans = [];
    while (mysqli_stmt_fetch($stmt)) {
        $plans[] = [
            'id' => $planId,
            'name' => $planName,
            'price' => $planPrice,
            'features' => $plan_features,
            'status' => $planStatus
        ];
    }
    mysqli_stmt_close($stmt);
} else {
    error_log("Error in prepared statement: " . mysqli_error($conn));
}

mysqli_close($conn);
?>


<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Subscription Plans</li>
        </ol>
    </nav>
    <div class="row">

        <div class="col-md-12 d-flex justify-content-center">
            <div class="col-12 col-md-6 grid-margin stretch-card" id="first-form">
                <div class="card">
                    <div class="card-body">

                        <h6 class="card-title">Add New Plan</h6>

                        <form class="forms-sample" enctype="multipart/form-data" method="post" id="plan-form">
                            <div class="mb-3">
                                <label for="">Plan Name:</label>
                                <input class="form-control" type="text" id="plan_name" name="plan_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="">Plan Price (Ksh):</label>
                                <input class="form-control" type="number" id="plan_price" name="plan_price" required>
                            </div>
                            <!-- <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">Select Plan
                                    Features:</label>
                                <select class="form-control" name="plan_features[]" id="plan_features" multiple
                                    required>
                                    <?php foreach ($planFeatures as $feature): ?>
                                        <option value="<?php echo htmlspecialchars($feature['feature_id']); ?>">
                                            <?php echo htmlspecialchars($feature['feature_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div> -->
                            <div class="mb-3">
                                <label for="plan_status">Plan Status:</label>
                                <select class="form-control" name="plan_status" id="plan_status">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                            <button type="submit" name="submit" value="submit"
                                class="btn btn-primary me-2">Submit</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Available Plans</h4>
                    <div class="table-responsive pt-3">
                        <table id="dataTableTasks" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Plan Name</th>
                                    <th>Price</th>
                                    <th>Features</th>
                                    <th>Subscription Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($plans) > 0) {
                                    $i = 1;
                                    $planType = "Monthly";

                                    foreach ($plans as $plan) {
                                        $k = 0;
                                        switch ($plan['id']) {
                                            case 1:
                                                $specificFeature = ["10 per month", "Email support", "Standard", "Standard", "Basic", "Basic", "-"];
                                                $listingLimit = "Up to 10 listings";
                                                break;
                                            case 2:
                                                $specificFeature = ["50 per month", "Priority email and chat", "Enhanced", "Enhanced", "Advanced", "Detailed", "Basic"];
                                                $listingLimit = "Up to 50 listings";
                                                break;
                                            case 3:
                                                $specificFeature = ["Unlimited", "24/7 phone, email, chat", "Premium", "Top-tier", "Full suite", "Comprehensive", "Advanced"];
                                                $listingLimit = "Ulimited listings";
                                                break;
                                        }

                                        $featureNames = [];
                                        $featureIds = explode(',', $plan['features']);

                                        foreach ($featureIds as $featureId) {
                                            if (isset($features[$featureId])) {
                                                $featureNames[] = $features[$featureId] . ": " . $specificFeature[$k++];
                                            }
                                        }

                                        echo "<tr>";
                                        echo "<td>" . $i++ . "</td>";
                                        echo "<td>" . htmlspecialchars($plan['name']) . "</td>";
                                        echo "<td>Ksh " . number_format($plan['price']) . "</td>";
                                        echo "<td>" . htmlspecialchars(implode(', ', $featureNames)) . "</td>";
                                        echo "<td>" . htmlspecialchars($planType) . "</td>";

                                        if ($plan['status'] === 'Active') {
                                            echo "<td><span class='badge border border-success text-success'>" . htmlspecialchars($plan['status']) . "</span></td>";
                                        } else if ($plan['status'] === 'Inactive') {
                                            echo "<td><span class='badge border border-secondary text-secondary'>" . htmlspecialchars($plan['status']) . "</span></td>";
                                        }

                                        echo "<td>
                                                <a href='#' class='btn btn-primary edit-plan' data-bs-toggle='modal' data-bs-target='#exampleModalCenter'
                                                    data-plan-id='" . htmlspecialchars($plan['id']) . "' data-plan-name='" . htmlspecialchars($plan['name']) . "'
                                                    data-plan-price='" . htmlspecialchars($plan['price']) . "' data-plan-features='" . htmlspecialchars($plan['features']) . "'
                                                    data-plan-type='" . htmlspecialchars($planType) . "' data-plan-status='" . htmlspecialchars($plan['status']) . "'>
                                                    <h5>Edit</h5>
                                                </a>
                                                <a href='#' class='btn btn-danger delete-plan'  dataplanId='" . htmlspecialchars($plan['id']) . "'>
                                                    <h5>Delete</h5>
                                                </a>
                                            </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo '<tr><td colspan="7" class="text-center">No records found.</td></tr>';
                                }
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
                        <h5 class="modal-title" id="exampleModalCenterTitle">Update Plan Details</h5>
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
        const editLinks = document.querySelectorAll('.edit-plan');

        editLinks.forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault();

                const planId = this.getAttribute('data-plan-id');
                const planName = this.getAttribute('data-plan-name');
                const planPrice = this.getAttribute('data-plan-price');
                const planStatus = this.getAttribute('data-plan-status');

                const selectOptions1 = `
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    `;

                const selectOptions2 = `
                                        <option value="Monthly">Monthly</option>
                                        <option value="Annually">Annually</option>
                                    `;

                const editModalTitle = document.getElementById('exampleModalCenterTitle');
                const editModalBody = document.querySelector('.modal-body');

                editModalTitle.textContent = "Update Plan Details";
                editModalBody.innerHTML = `
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Updating : <span class="text-primary">${planName} Plan</span></h6>
                            <form class="forms-sample" enctype="multipart/form-data" method="post" id="plan-edit-form">
                                <div class="mb-3">
                                        <input type="hidden" name="edit-plan-id" value="${planId}" required>
                                    </div>
                                <div class="mb-3">
                                    <label for="">Plan Name:</label>
                                    <input class="form-control" type="text" id="edit_plan_name" name="edit_plan_name" value="${planName}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="">Plan Price (Ksh):</label>
                                    <input class="form-control" type="number" id="edit_plan_price" name="edit_plan_price" value="${planPrice}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="plan_status">Plan Status:</label>
                                    <select class="form-control" name="edit_plan_status" id="edit_plan_status">
                                        ${selectOptions1}
                                    </select>
                                </div>
                                <button type="submit" name="submit" value="submit"
                                    class="btn btn-primary me-2">Update</button>
                        </form>
                        </div>
                    </div>`;

                const selectElement1 = editModalBody.querySelector('#edit_plan_status');
                for (const option of selectElement1.options) {
                    if (option.value === planStatus) {
                        option.selected = true;
                        break;
                    }
                }


                const editForm = document.getElementById('plan-edit-form');

                editForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const formData = new FormData(editForm)

                    $.ajax({
                        type: 'POST',
                        url: './plan-management',
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
        $('#plan-form').submit(function (e) {
            e.preventDefault();

            var formData = new FormData($(this)[0]);
            $.ajax({
                type: 'POST',
                url: './add-plan',
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
                    })
                }
            });
        });

        $('.delete-plan').on('click', function (e) {
            e.preventDefault();

            const planId = this.getAttribute('dataplanId');

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger me-2'
                },
                buttonsStyling: false,
            })

            swalWithBootstrapButtons.fire({
                title: 'Warning!',
                text: "Are you sure you want to delete this plan?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonClass: 'me-2',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: './delete-plan',
                        data: {
                            planId: planId
                        },
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
    });
</script>
<?php
include ("./includes/footer_end.php");
?>