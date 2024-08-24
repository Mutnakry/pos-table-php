<?php
session_start();

include_once "includes/header.php";
include "../connection.php"; // Make sure this is the correct connection file


if (isset($_POST['regDetalle'])) {
    $id_producto = $_POST['id'];
    $mesa = $_POST['mesa']; // table id

    // Check if cart exists in the session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if product is already in the cart
    $product_exists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $id_producto && $item['table_id'] == $mesa) {
            $item['quantity'] += 1;
            $product_exists = true;
            break;
        }
    }

    // If product does not exist, add it to the cart
    if (!$product_exists) {
        $new_item = array(
            'product_id' => $id_producto,
            'table_id' => $mesa,
            'quantity' => 1
        );
        $_SESSION['cart'][] = $new_item;
    }

    echo json_encode('registrado');
    exit();
}


if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        // Fetch product details from the database using product_id
        $query = mysqli_query($conn, "SELECT * FROM product WHERE id = '{$item['product_id']}'");
        $product = mysqli_fetch_assoc($query);

        echo '<div class="cart-item">';
        echo '<h6>' . $product['names'] . '</h6>';
        echo '<p>Quantity: ' . $item['quantity'] . '</p>';
        echo '<p>Price: $' . $product['price'] . '</p>';
        echo '</div>';
    }
} else {
    echo '<p>Your cart is empty</p>';
}

?>
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-edit"></i>
            table
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-7 col-sm-9">
                <div class="tab-content" id="vert-tabs-right-tabContent">
                    <div class="tab-pane fade show active" id="vert-tabs-right-home" role="tabpanel" aria-labelledby="vert-tabs-right-home-tab">
                        <input type="hidden" id="id_table" value="<?php echo $_GET['id_table'] ?>">
                        <input type="hidden" id="table" value="<?php echo $_GET['table'] ?>">
                        <div class="row">
                            <?php
                            include "../connection.php";
                            $query = mysqli_query($conn, "SELECT * FROM product WHERE status = 1");
                            $result = mysqli_num_rows($query);
                            
                            if ($result > 0) {
                                while ($data = mysqli_fetch_assoc($query)) {
                                    ?>
                                    <div class="col-md-3">
                                        <div class="col-12">
                                            <img src="<?php echo ($data['image'] == null) ? '../assets/img/default.png' : $data['image']; ?>" class="product-image" alt="Product Image">
                                        </div>
                                        <h6 class="my-3"><?php echo $data['names']; ?></h6>
                                        <div class="bg-gray py-2 px-3 mt-4">
                                            <h2 class="mb-0">
                                                $<?php echo $data['price']; ?>
                                            </h2>
                                        </div>
                                        <div class="mt-4">
                                            <a class="btn btn-primary btn-block btn-flat addDetalle" href="#" data-id="<?php echo $data['id']; ?>">
                                                <i class="fas fa-cart-plus mr-2"></i>
                                                Add
                                            </a>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pedido" role="tabpanel" aria-labelledby="pedido-tab">
                        <div class="row" id="detalle_pedido"></div>
                        <hr>
                        <div class="form-group">
                            <label for="observacion">Observaciones</label>
                            <textarea id="observacion" class="form-control" rows="3" placeholder="Observaciones"></textarea>
                        </div>
                        <button class="btn btn-primary" type="button" id="realizar_pedido">Realizar pedido</button>
                    </div>
                </div>
            </div>
            <div class="col-5 col-sm-3">
                <div class="nav flex-column nav-tabs nav-tabs-right h-100" id="vert-tabs-right-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active" id="vert-tabs-right-home-tab" data-toggle="pill" href="#vert-tabs-right-home" role="tab" aria-controls="vert-tabs-right-home" aria-selected="true">Platos</a>
                    <a class="nav-link" id="pedido-tab" data-toggle="pill" href="#pedido" role="tab" aria-controls="pedido" aria-selected="false">Pedido</a>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card -->
</div>
<?php include_once "includes/footer.php";

?>


<script>
// function registrarDetalle(id_pro) {
//     let action = 'regDetalle';
//     let id_sala = $('#id_room').val();
//     let mesa = $('#table').val();
    
//     $.ajax({
//         url: "ajax.php",
//         type: 'POST',
//         dataType: "json",
//         data: {
//             id: id_pro,
//             regDetalle: action,
//             id_sala: id_sala,
//             mesa: mesa
//         },
//         success: function (response) {
//             if (response == 'registrado') {
//                 listar(); // Call a function to refresh the cart or update the UI
//             }
//             Swal.fire({
//                 position: 'top-end',
//                 icon: 'success',
//                 title: 'Producto agregado',
//                 showConfirmButton: false,
//                 timer: 2000
//             });
//         }
//     });
// }

// function registrarDetalle(id_pro) {
//     let action = 'regDetalle';
//     let id_sala = $('#id_room').val();
//     let mesa = $('#table').val();
    
//     $.ajax({
//         url: "ajax.php", // Ensure this is the correct path to your PHP script
//         type: 'POST',
//         dataType: "json",
//         data: {
//             id: id_pro,
//             regDetalle: action,
//             mesa: mesa
//         },
//         success: function (response) {
//             if (response == 'registrado') {
//                 listar(); // Call a function to refresh the cart or update the UI
//             }
//             Swal.fire({
//                 position: 'top-end',
//                 icon: 'success',
//                 title: 'Producto agregado',
//                 showConfirmButton: false,
//                 timer: 2000
//             });
//         }
//     });
// }

</script>