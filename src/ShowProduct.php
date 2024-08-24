<?php
session_start();

include_once "includes/header.php";
include "../connection.php"; // Make sure this is the correct connection file



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_qty = $_POST['product_qty'];

        // Fetch the available quantity from the database
        $sql = "SELECT qty FROM products WHERE id = $product_id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $available_qty = $row['qty'];




        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        if (isset($_SESSION['cart'][$product_id])) {
            $current_qty = $_SESSION['cart'][$product_id]['qty'];
            if ($current_qty + $product_qty <= $available_qty) {
                $_SESSION['cart'][$product_id]['qty'] += $product_qty;
            } else {
                $_SESSION['toastr'] = array(
                    'type' => 'error',
                    'message' => 'Product limit reached!',
                    'title' => 'Error'
                );
            }
        } else {
            if ($product_qty <= $available_qty) {
                $_SESSION['cart'][$product_id] = array(
                    'id' => $product_id,
                    'name' => $product_name,
                    'price' => $product_price,
                    'qty' => $product_qty
                );
            } else {
                $_SESSION['toastr'] = array(
                    'type' => 'error',
                    'message' => 'Product limit reached!',
                    'title' => 'Error'
                );
            }
        }
    }


    // if (isset($_POST['update_cart'])) {
    //     $product_id = $_POST['product_id'];
    //     $new_qty = $_POST['new_qty'];

    //     $sql = "SELECT qty FROM products WHERE id = $product_id";
    //     $result = mysqli_query($conn, $sql);
    //     $row = mysqli_fetch_assoc($result);
    //     $available_qty = $row['qty'];

    //     if ($new_qty > 0 && $new_qty <= $available_qty) {
    //         $_SESSION['cart'][$product_id]['qty'] = $new_qty;
    //     } elseif ($new_qty > $available_qty) {
    //         $_SESSION['toastr'] = array(
    //             'type' => 'error',
    //             'message' => 'Product limit reached!',
    //             'title' => 'Error'
    //         );
    //     } else {
    //         unset($_SESSION['cart'][$product_id]);
    //     }
    // }

    if (isset($_POST['remove_from_cart'])) {
        $product_id = $_POST['product_id'];
        unset($_SESSION['cart'][$product_id]);
    }


    if (isset($_POST['update_cart'])) {
        $product_id = $_POST['product_id'];
        $new_qty = $_POST['new_qty'];

        $sql = "SELECT qty FROM products WHERE id = $product_id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $available_qty = $row['qty'];

        if ($new_qty > 0 && $new_qty <= $available_qty) {
            $_SESSION['cart'][$product_id]['qty'] = $new_qty;
        } elseif ($new_qty > $available_qty) {
            $_SESSION['toastr'] = array(
                'type' => 'error',
                'message' => 'Product limit reached!',
                'title' => 'Error'
            );
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    if (isset($_POST['remove_from_cart'])) {
        $product_id = $_POST['product_id'];
        unset($_SESSION['cart'][$product_id]);
    }





    if (isset($_POST['place_order'])) {
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $table = mysqli_real_escape_string($conn, $_GET['table']);
            $room = mysqli_real_escape_string($conn, $_GET['id_table']);

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

            // Insert order into `orders` table
            $sql = "INSERT INTO orders (id_room, num_table, total, status) 
                    VALUES ('$room', '$table', '$total', 'OFF')";
            if (mysqli_query($conn, $sql)) {
                $order_id = mysqli_insert_id($conn);

                // Insert order details into `order_details` table
                foreach ($_SESSION['cart'] as $product_id => $item) {
                    $product_id = mysqli_real_escape_string($conn, $item['id']);
                    $product_qty = mysqli_real_escape_string($conn, $item['qty']);
                    $product_price = mysqli_real_escape_string($conn, $item['price']);

                    $sql = "INSERT INTO order_details (product_id, order_id, quantity, price) 
                            VALUES ('$product_id', '$order_id', '$product_qty', '$product_price')";

                    if (!mysqli_query($conn, $sql)) {
                        $_SESSION['toastr'] = array(
                            'type' => 'error',
                            'message' => 'Order details could not be inserted. Please try again later.',
                            'title' => 'Error'
                        );
                        error_log("MySQL Error: " . mysqli_error($conn));
                        exit;
                    }
                }

                unset($_SESSION['cart']); // Clear the cart after placing the order
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
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            <?php
            if (isset($_SESSION['toastr'])) {
                echo "toastr." . $_SESSION['toastr']['type'] . "('" . $_SESSION['toastr']['message'] . "', '" . $_SESSION['toastr']['title'] . "');";
                unset($_SESSION['toastr']);
            }
            ?>
        });
    </script>
    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-center",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    </script>

    <style>
        .card-img-top {
            width: 100%;
            height: auto;
        }

        .card {
            max-width: 18rem;
            margin: auto;
        }
    </style>
    <script>
        function addToCart(productId, productName, productPrice, availableQty) {
            const form = document.createElement('form');
            form.method = 'post';
            form.action = '';

            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'product_id';
            idInput.value = productId;
            form.appendChild(idInput);

            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = 'product_name';
            nameInput.value = productName;
            form.appendChild(nameInput);

            const priceInput = document.createElement('input');
            priceInput.type = 'hidden';
            priceInput.name = 'product_price';
            priceInput.value = productPrice;
            form.appendChild(priceInput);

            const qtyInput = document.createElement('input');
            qtyInput.type = 'hidden';
            qtyInput.name = 'product_qty';
            qtyInput.value = 1;
            form.appendChild(qtyInput);

            const addAction = document.createElement('input');
            addAction.type = 'hidden';
            addAction.name = 'add_to_cart';
            form.appendChild(addAction);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</head>

<body>
    <div class="">
        <form method="post" action="">
            <div class="row">
                <div class="col-lg-8 col-md-12">
                    <h1>Products</h1>
                    <div class="row">
                        <?php
                        $sql = "SELECT * FROM products WHERE status = 1";
                        $query = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_array($query)) {
                        ?>
                            <div class="col-md-2 mb-3 rounded-pill">
                                <div class="card" style="cursor: pointer;" onclick="addToCart('<?php echo $row['id']; ?>', '<?php echo $row['names']; ?>', '<?php echo $row['price']; ?>', '<?php echo $row['qty']; ?>')">
                                    <img src="https://t4.ftcdn.net/jpg/00/65/70/65/360_F_65706597_uNm2SwlPIuNUDuMwo6stBd81e25Y8K8s.jpg" class="card-img-top" alt="Product Image">
                                    <div class="card-body">
                                        <h5 class="card-title">Name: <?php echo $row["names"] ?></h5>
                                        <p class="card-text">Price: <?php echo $row["price"] ?></p>
                                        <!-- <p class="card-text">Available Quantity: <?php echo $row["qty"] ?></p> -->
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                </div>
                <div class="col-lg-4 col-md-12 overflow-x-auto"   style="max-height: auto; overflow-x: auto; overflow-y: auto;">
                    <div >
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
                                            <td><?php echo $item['name']; ?></td>
                                            <td><?php echo $item['price']; ?></td>
                                            <td>
                                                <form method="post" action="" style="display:inline;">
                                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                                    <input type="hidden" name="new_qty" value="<?php echo max(1, $item['qty'] - 1); ?>">
                                                    <button type="submit" name="update_cart" class="btn btn-sm btn-outline-secondary">-</button>
                                                </form>
                                                <?php echo $item['qty']; ?>
                                                <form method="post" action="" style="display:inline;">
                                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                                    <input type="hidden" name="new_qty" value="<?php echo $item['qty'] + 1; ?>">
                                                    <button type="submit" name="update_cart" class="btn btn-sm btn-outline-secondary">+</button>
                                                </form>
                                            </td>
                                            <td><?php echo $subtotal; ?></td>
                                            <td>
                                                <form method="post" action="">
                                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
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
                                    <td colspan="3">Total</td>
                                    <td><?php echo $total; ?></td>
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
            </div>
        </form>
    </div>
    <?php include_once "includes/footer.php"; ?>
</body>

</html>