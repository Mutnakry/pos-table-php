<?php
session_start();
$fecha = date('Y-m-d');
$id_room = $_GET['id_room'];
$table = $_GET['table'];
include_once "includes/header.php";
include "../connection.php";

if (isset($_POST['place_order'])) {
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $table = mysqli_real_escape_string($conn, $_GET['table']);
        $room = mysqli_real_escape_string($conn, $_GET['id_room']);

        if (empty($table)) {
            $_SESSION['toastr'] = array(
                'type' => 'error',
                'message' => 'Invalid table ID!',
                'title' => 'Error'
            );
            exit;
        }

        // Calculate total
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['qty'];
        }

        // Update or insert order into `orders` table
        $sql = "UPDATE orders SET id_room='$room', num_table='$table', total='$total', status='OFF' WHERE id_room='$room' AND num_table='$table' AND status='PENDING'";

        if (mysqli_query($conn, $sql)) {
            $order_id = mysqli_insert_id($conn); // Get the last inserted order ID

            // Loop through each item in the cart and insert/update order details
            foreach ($_SESSION['cart'] as $item) {
                $product_id = mysqli_real_escape_string($conn, $item['id']);
                $product_qty = mysqli_real_escape_string($conn, $item['qty']);
                $product_price = mysqli_real_escape_string($conn, $item['price']);

                // Insert or update order details
                $sql = "INSERT INTO order_details (product_id, order_id, quantity, price)
                        VALUES ('$product_id', '$order_id', '$product_qty', '$product_price')
                        ON DUPLICATE KEY UPDATE quantity='$product_qty', price='$product_price'";

                if (!mysqli_query($conn, $sql)) {
                    $_SESSION['toastr'] = array(
                        'type' => 'error',
                        'message' => 'Order details could not be inserted/updated. Please try again later.',
                        'title' => 'Error'
                    );
                    error_log("MySQL Error: " . mysqli_error($conn));
                    exit;
                }
            }

            // Clear the cart after placing the order
            unset($_SESSION['cart']);
            $_SESSION['toastr'] = array(
                'type' => 'success',
                'message' => 'Order placed successfully!',
                'title' => 'Success'
            );
        } else {
            $_SESSION['toastr'] = array(
                'type' => 'error',
                'message' => 'Order could not be placed. Please try again later.',
                'title' => 'Error'
            );
            error_log("MySQL Error: " . mysqli_error($conn));
        }
    } else {
        $_SESSION['toastr'] = array(
            'type' => 'error',
            'message' => 'Your cart is empty!',
            'title' => 'Error'
        );
    }
}


if (isset($_POST['update_cart'])) {
    $product_id = $_POST['product_id'];
    $new_qty = $_POST['new_qty'];

    // Get available quantity from the database
    $sql = "SELECT qty FROM products WHERE id = $product_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $available_qty = $row['qty'];

    if ($new_qty > 0 && $new_qty <= $available_qty) {
        // Update the quantity in the cart
        $_SESSION['cart'][$product_id]['quantity'] = $new_qty;
    } elseif ($new_qty > $available_qty) {
        // If the requested quantity is more than available, show an error
        $_SESSION['toastr'] = array(
            'type' => 'error',
            'message' => 'Product limit reached!',
            'title' => 'Error'
        );
    } else {
        // If quantity is less than 1, remove the item from the cart
        unset($_SESSION['cart'][$product_id]);
    }
}
?>

<script>
    function addToCart(id, name, price) {
        const cartItem = document.querySelector(`#cart-item-${id}`);
        if (cartItem) {
            // If item is already in the cart, increase the quantity
            const qtyField = cartItem.querySelector('.cart-qty');
            qtyField.value = parseInt(qtyField.value) + 1;
            updateCart(id, qtyField.value);
        } else {
            // If item is not in the cart, add it with quantity 1
            // You can implement the logic here to add the item to the cart if needed
        }
    }

    function updateCart(id, newQty) {
        const form = document.createElement('form');
        form.method = 'post';
        form.style.display = 'none';

        const productIdField = document.createElement('input');
        productIdField.type = 'hidden';
        productIdField.name = 'product_id';
        productIdField.value = id;
        form.appendChild(productIdField);

        const newQtyField = document.createElement('input');
        newQtyField.type = 'hidden';
        newQtyField.name = 'new_qty';
        newQtyField.value = newQty;
        form.appendChild(newQtyField);

        const updateCartField = document.createElement('input');
        updateCartField.type = 'hidden';
        updateCartField.name = 'update_cart';
        updateCartField.value = '1';
        form.appendChild(updateCartField);

        document.body.appendChild(form);
        form.submit();
    }
</script>


<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-edit"></i>
            Platos
        </h3>
    </div>
    <div class="card-body">
        <input type="hidden" id="id_room" value="<?php echo htmlspecialchars($id_room); ?>">
        <input type="hidden" id="table" value="<?php echo htmlspecialchars($table); ?>">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="row">
                    <?php
                    $sql = "SELECT * FROM products WHERE status = 1";
                    $query = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($query)) {
                    ?>
                        <div class="col-md-2 mb-3 rounded-pill">
                            <div class="card" style="cursor: pointer;" onclick="addToCart('<?php echo $row['id']; ?>', '<?php echo $row['names']; ?>', '<?php echo $row['price']; ?>')">
                                <img src="https://t4.ftcdn.net/jpg/00/65/70/65/360_F_65706597_uNm2SwlPIuNUDuMwo6stBd81e25Y8K8s.jpg" class="card-img-top" alt="Product Image">
                                <div class="card-body">
                                    <h5 class="card-title">Name: <?php echo htmlspecialchars($row['names']); ?></h5>
                                    <p class="card-text">Price: $<?php echo number_format($row['price'], 2); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php

            $query = mysqli_query($conn, "SELECT * FROM orders WHERE id_room = $id_room AND num_table = $table AND status = 'OFF'");
            $result = mysqli_fetch_assoc($query);
            if (!empty($result)) { ?>
                <div class="col-lg-4 col-md-12" style="max-height: auto; overflow-x: auto; overflow-y: auto;">
                    <div class="bg-gray py-2 px-3">
                        <h2 class="mb-0">
                            បន្ទប់ទី : <?php echo htmlspecialchars($id_room); ?> , តុទី​ : <?php echo htmlspecialchars($table); ?>
                        </h2>
                    </div>
                    <hr>
                    <h3>Platos</h3>
                    <div class="row">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $order_id = $result['id'];
                                $query1 = mysqli_query($conn, "SELECT * FROM order_details WHERE order_id = $order_id");
                                $total = 0;
                                while ($item = mysqli_fetch_assoc($query1)) {
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total += $subtotal;
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['product_id']); ?></td>
                                        <td><?php echo number_format($item['price'], 2); ?></td>
                                        <td>
                                            <form method="post" action="" style="display:inline;">
                                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['id']); ?>">
                                                <input type="hidden" name="new_qty" value="<?php echo $item['quantity'] - 1; ?>">
                                                <button type="submit" name="update_cart" class="btn btn-sm btn-outline-secondary">-</button>
                                            </form>
                                            <?php echo htmlspecialchars($item['quantity']); ?>
                                            <form method="post" action="" style="display:inline;">
                                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['id']); ?>">
                                                <input type="hidden" name="new_qty" value="<?php echo $item['quantity'] + 1; ?>">
                                                <button type="submit" name="update_cart" class="btn btn-sm btn-outline-secondary">+</button>
                                            </form>
                                        </td>
                                        <td><?php echo number_format($subtotal, 2); ?></td>
                                        <td>
                                            <form method="post" action="">
                                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['id']); ?>">
                                                <button type="submit" name="remove_from_cart" class="btn btn-sm btn-danger">Remove</button>
                                            </form>
                                        </td>
                                    </tr>

                                <?php } ?>
                                <tr>
                                    <td colspan="3"><strong>Total</strong></td>
                                    <td><?php echo number_format($total, 2); ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="5">
                                        <button type="submit" name="place_order" class="btn btn-sm btn-primary">Place Order</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            <?php } ?>
        </div>
    </div>

    <!-- <div class="row">
        <div class="col-lg-8 col-md-12">
            <h1>Products</h1>
            <div class="row">
                <?php
                $sql = "SELECT * FROM products WHERE status = 1";
                $query = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($query)) {
                ?>
                    <div class="col-md-2 mb-3 rounded-pill">
                        <div class="card" style="cursor: pointer;" onclick="addToCart('<?php echo $row['id']; ?>', '<?php echo $row['names']; ?>', '<?php echo $row['price']; ?>')">
                            <img src="https://t4.ftcdn.net/jpg/00/65/70/65/360_F_65706597_uNm2SwlPIuNUDuMwo6stBd81e25Y8K8s.jpg" class="card-img-top" alt="Product Image">
                            <div class="card-body">
                                <h5 class="card-title">Name: <?php echo htmlspecialchars($row['names']); ?></h5>
                                <p class="card-text">Price: $<?php echo number_format($row['price'], 2); ?></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="col-lg-4 col-md-12" style="max-height: auto; overflow-x: auto; overflow-y: auto;">
            <div>
                <h2>Shopping Cart</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        if (isset($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $item) {
                                $subtotal = $item['price'] * $item['qty'];
                                $total += $subtotal;
                        ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td><?php echo number_format($item['price'], 2); ?></td>
                                    <td>
                                        <form method="post" action="" style="display:inline;">
                                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['id']); ?>">
                                            <input type="hidden" name="new_qty" value="<?php echo max(1, $item['qty'] - 1); ?>">
                                            <button type="submit" name="update_cart" class="btn btn-sm btn-outline-secondary">-</button>
                                        </form>
                                        <?php echo htmlspecialchars($item['qty']); ?>
                                        <form method="post" action="" style="display:inline;">
                                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['id']); ?>">
                                            <input type="hidden" name="new_qty" value="<?php echo $item['qty'] + 1; ?>">
                                            <button type="submit" name="update_cart" class="btn btn-sm btn-outline-secondary">+</button>
                                        </form>
                                    </td>
                                    <td><?php echo number_format($subtotal, 2); ?></td>
                                    <td>
                                        <form method="post" action="">
                                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['id']); ?>">
                                            <button type="submit" name="remove_from_cart" class="btn btn-sm btn-danger">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo '<tr><td colspan="5">Your cart is empty</td></tr>';
                        }
                        ?>
                        <tr>
                            <td colspan="3"><strong>Total</strong></td>
                            <td><?php echo number_format($total, 2); ?></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <button type="submit" name="place_order" class="btn btn-sm btn-primary">Place Order</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div> -->
</div>
<?php include_once "includes/footer.php"; ?>

<script>
    function addToCart(id, name, price) {
        console.log('Add to cart:', id, name, price);
    }
</script>









