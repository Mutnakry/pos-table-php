<?php
session_start();
include_once "includes/header.php";
?>
<div class="card">
    <div class="card-header text-center">
        Salas
    </div>
    <div class="card-body">
        <div class="row">
            <?php
            include "../connection.php";
            $query = mysqli_query($conn, "SELECT * FROM room WHERE status = 1");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) { ?>
                    <div class="col-6 col-sm-6 col-md-3 col-lg-2">
                        <div class="m-2 p-1 bg-secondary rounded shadow-xl">
                            <div class="text-center">
                                <img src="https://image-tc.galaxy.tf/wijpeg-4evsoqf4n3s2co1zdvbrcuvk9/conference-dining-room_standard.jpg?crop=199%2C0%2C1603%2C1202" class="product-image rounded img-fluid" alt="Product Image">
                            </div>
                            <h6 class="my-3 text-center">
                                <span class="badge badge-danger p-2"><?php echo $data['names']; ?></span>
                            </h6>
                            <div class="mt-4">
                                <a class="btn btn-primary rounded btn-block btn-flat" href="table.php?id_room=<?php echo $data['id']; ?>&table=<?php echo $data['table']; ?>&names=<?php echo $data['names']; ?>">
                                    <i class="far fa-eye mr-2"></i>
                                    Table
                                </a>
                            </div>
                        </div>
                 
                    </div>
            <?php }
            } ?>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>