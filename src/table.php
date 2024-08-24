<?php
session_start();
include_once "includes/header.php";
include "../connection.php"; // Make sure this is the correct connection file

$id = $_GET['id_room'];
$names = $_GET['names'];
$table = $_GET['table'];
?>
<div class="card">
    <div class="card-header text-center fs-1">
    <h1>បន្ទប់ <?php echo htmlspecialchars($names); ?></h1>
    </div>
    <div class="card-body">
        <div class="row">
            <?php
            $query = mysqli_query($conn, "SELECT * FROM room WHERE id = $id");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                $data = mysqli_fetch_assoc($query);
                if ($data['table'] == $table) {
                    $item = 1;
                    for ($i = 0; $i < $table; $i++) {
                        $sql = mysqli_query($conn, "SELECT * FROM orders WHERE id_room = $id AND num_table = $item AND status = 'OFF'");
                        $order = mysqli_fetch_assoc($sql);
            ?>
                        <div class="col-6 col-sm-6 col-md-4 col-lg-2">
                            <div class="mb-2">
                                <div class=" border border-danger rounded shadow-lg">
                                    <div class="card card-widget widget-user" style="background-image: url('https://res.cloudinary.com/tf-lab/image/upload/restaurant/189ccb68-86b5-41ac-a686-00f61ba1103c/3b0ea7cf-0b4d-43c9-a45e-2c1a7ab06d70.jpg'); background-size: cover; background-position: center;">
                                        <!-- Header with conditional background color -->
                                        <div class="widget-user-header bg-<?php echo empty($order) ? 'success' : 'danger'; ?> ">
                                            <h1 class="widget-user-username fs-1">តុលេខ</h1>
                                            <h2 class="widget-user-desc fs-2"><?php echo $item; ?></h2>
                                        </div>
                                        <div class="widget-user-image">
                                        <img class="img-circle elevation-2" src="../assets/img/mesa.jpg" alt="User Avatar">
                                    </div>
                                        <div class="card-footer">
                                            <div class="description-block text-center">
                                                <?php if (empty($order)) { ?>
                                                    <a class="btn px-5 btn-outline-info" href="ShowProduct.php?id_table=<?php echo $id; ?>&table=<?php echo $item; ?>">NO</a>
                                                    <!-- <a class="btn px-5 btn-outline-info" href="ShowProduct.php">NO</a> -->
                                                <?php } else { ?>
                                                    <a class="btn btn-danger px-5" href="update_sale.php?id_room=<?php echo $id; ?>&table=<?php echo $item; ?>">OFF</a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                      
                                    </div>
                                </div>
                            </div>
                        </div>
            <?php
                        $item++;
                    }
                }
            } ?>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>