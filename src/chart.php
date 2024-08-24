<?php
include("../conexion.php");
if ($_POST['action'] == 'room') {
    $desde = date('Y') . '-01-01 00:00:00';
    $hasta = date('Y') . '-12-31 23:59:59';
    $query = mysqli_query($conn, "SELECT SUM(IF(MONTH(dob) = 1, total, 0)) AS ene,
    SUM(IF(MONTH(dob) = 2, total, 0)) AS feb,
    SUM(IF(MONTH(dob) = 3, total, 0)) AS mar,
    SUM(IF(MONTH(dob) = 4, total, 0)) AS abr,
    SUM(IF(MONTH(dob) = 5, total, 0)) AS may,
    SUM(IF(MONTH(dob) = 6, total, 0)) AS jun,
    SUM(IF(MONTH(dob) = 7, total, 0)) AS jul,
    SUM(IF(MONTH(dob) = 8, total, 0)) AS ago,
    SUM(IF(MONTH(dob) = 9, total, 0)) AS sep,
    SUM(IF(MONTH(dob) = 10, total, 0)) AS oct,
    SUM(IF(MONTH(dob) = 11, total, 0)) AS nov,
    SUM(IF(MONTH(dob) = 12, total, 0)) AS dic 
    FROM room WHERE dob BETWEEN '$desde' AND '$hasta'");
    $arreglo = mysqli_fetch_assoc($query);
    echo json_encode($arreglo);
    die();
}
?>



<?php
include("../connection.php");

// Initialize variables
// $total = 0;
// $arreglo = [];

// // Check if POST request and 'action' is set
// if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
//     if ($_POST['action'] == 'room') {
//         $desde = date('Y') . '-01-01 00:00:00';
//         $hasta = date('Y') . '-12-31 23:59:59';
//         $query = mysqli_query($conexion, "SELECT 
//             SUM(IF(MONTH(dob) = 1, total, 0)) AS ene,
//             SUM(IF(MONTH(dob) = 2, total, 0)) AS feb,
//             SUM(IF(MONTH(dob) = 3, total, 0)) AS mar,
//             SUM(IF(MONTH(dob) = 4, total, 0)) AS abr,
//             SUM(IF(MONTH(dob) = 5, total, 0)) AS may,
//             SUM(IF(MONTH(dob) = 6, total, 0)) AS jun,
//             SUM(IF(MONTH(dob) = 7, total, 0)) AS jul,
//             SUM(IF(MONTH(dob) = 8, total, 0)) AS ago,
//             SUM(IF(MONTH(dob) = 9, total, 0)) AS sep,
//             SUM(IF(MONTH(dob) = 10, total, 0)) AS oct,
//             SUM(IF(MONTH(dob) = 11, total, 0)) AS nov,
//             SUM(IF(MONTH(dob) = 12, total, 0)) AS dic 
//             FROM room WHERE dob BETWEEN '$desde' AND '$hasta'");
//         $arreglo = mysqli_fetch_assoc($query);
//         echo json_encode($arreglo);
//         die();
//     }
// }




<?php
include("../connection.php");

// Initialize variables
$total = 0;
$arreglo = [];

// Check if POST request and 'action' is set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'room') {
        $desde = date('Y') . '-01-01 00:00:00';
        $hasta = date('Y') . '-12-31 23:59:59';
        $query = mysqli_query($conexion, "SELECT 
            SUM(IF(MONTH(dob) = 1, total, 0)) AS ene,
            SUM(IF(MONTH(dob) = 2, total, 0)) AS feb,
            SUM(IF(MONTH(dob) = 3, total, 0)) AS mar,
            SUM(IF(MONTH(dob) = 4, total, 0)) AS abr,
            SUM(IF(MONTH(dob) = 5, total, 0)) AS may,
            SUM(IF(MONTH(dob) = 6, total, 0)) AS jun,
            SUM(IF(MONTH(dob) = 7, total, 0)) AS jul,
            SUM(IF(MONTH(dob) = 8, total, 0)) AS ago,
            SUM(IF(MONTH(dob) = 9, total, 0)) AS sep,
            SUM(IF(MONTH(dob) = 10, total, 0)) AS oct,
            SUM(IF(MONTH(dob) = 11, total, 0)) AS nov,
            SUM(IF(MONTH(dob) = 12, total, 0)) AS dic 
            FROM room WHERE dob BETWEEN '$desde' AND '$hasta'");
        $arreglo = mysqli_fetch_assoc($query);
        echo json_encode($arreglo);
        die();
    }
}

// Get total sales
$query5 = mysqli_query($conn, "SELECT SUM(`total`) AS total FROM room"); // Assuming 'total' column in 'room' table
if ($query5) {
    $totalVentas = mysqli_fetch_assoc($query5);
    $total = $totalVentas['total'] ?? 0; // Default to 0 if no result
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Sales</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <p class="d-flex flex-column">
                            <span class="text-bold text-lg">$<?php echo number_format($total, 2); ?></span>
                            <span>Total</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->

                    <div class="position-relative mb-4">
                        <canvas id="sales-chart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Fetch sales data from PHP
            var salesData = <?php echo json_encode($arreglo); ?>;

            // Create a chart
            var ctx = document.getElementById('sales-chart').getContext('2d');
            var salesChart = new Chart(ctx, {
                type: 'bar', // You can change this to 'line', 'pie', etc.
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Monthly Sales',
                        data: [
                            salesData.ene,
                            salesData.feb,
                            salesData.mar,
                            salesData.abr,
                            salesData.may,
                            salesData.jun,
                            salesData.jul,
                            salesData.ago,
                            salesData.sep,
                            salesData.oct,
                            salesData.nov,
                            salesData.dic
                        ],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Total Sales',
                        data: new Array(12).fill(<?php echo $total; ?>), // Fill the array with total value
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        type: 'line', // Line type for total sales
                        tension: 0.1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>






















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

    // Process order
    // if (isset($_POST['place_order'])) {
    //     if (isset($_SESSION['cart'])) {
    //         foreach ($_SESSION['cart'] as $product_id => $item) {
    //             $product_price = $_POST['price'];
    //             $product_qty = $_POST['qty'];
    //             $sql = "INSERT INTO order_details (product_id, table_id, quantity, price) 
    //                 VALUES ('$product_id', '$table', '$product_qty', '$product_price')";
    //             mysqli_query($conn, $sql);
    //         }
    //         unset($_SESSION['cart']); // Clear the cart
    //         $_SESSION['toastr'] = array(
    //             'type' => 'success',
    //             'message' => 'Order placed successfully!',
    //             'title' => 'Success'
    //         );
    //     } else {
    //         $_SESSION['toastr'] = array(
    //             'type' => 'error',
    //             'message' => 'Order failed!',
    //             'title' => 'Error'
    //         );
    //     }
    // }
    if (isset($_POST['place_order'])) {
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $product_id => $item) {
                
$table = $_GET['table'];
                $product_id = mysqli_real_escape_string($conn, $item['id']);
                $product_qty = mysqli_real_escape_string($conn, $item['qty']);
                $product_price = mysqli_real_escape_string($conn, $item['price']);
                
                // Debugging
                echo "Table ID: $table_id <br>";
                echo "Product ID: $product_id <br>";
                echo "Quantity: $product_qty <br>";
                echo "Price: $product_price <br>";
                
                // Proceed with insertion
                $sql = "INSERT INTO order_details (product_id, table_id, quantity, price) 
                        VALUES ('$product_id', '$table_id', '$product_qty', '$product_price')";
                
                if (mysqli_query($conn, $sql)) {
                    // Success
                } else {
                    $_SESSION['toastr'] = array(
                        'type' => 'error',
                        'message' => 'Order could not be placed. Please try again later.',
                        'title' => 'Error'
                    );
                    error_log("MySQL Error: " . mysqli_error($conn));
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
    <div class="row">
        <form method="post" action="">
            <div class="col-7 col-sm-9">
                <h1>Products</h1>
                <div class="row">
                    <?php
                    $sql = "SELECT * FROM products WHERE status = 1";
                    $query = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_array($query)) {
                    ?>
                        <div class="col-md-4 mb-3">
                            <div class="card" style="cursor: pointer;" onclick="addToCart('<?php echo $row['id']; ?>', '<?php echo $row['names']; ?>', '<?php echo $row['price']; ?>', '<?php echo $row['qty']; ?>')">
                                <img src="https://t4.ftcdn.net/jpg/00/65/70/65/360_F_65706597_uNm2SwlPIuNUDuMwo6stBd81e25Y8K8s.jpg" class="card-img-top" alt="Product Image">
                                <div class="card-body">
                                    <h5 class="card-title">Name: <?php echo $row["names"] ?></h5>
                                    <p class="card-text">Price: <?php echo $row["price"] ?></p>
                                    <p class="card-text">Available Quantity: <?php echo $row["qty"] ?></p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            </div>
            <div class="col-5 col-sm-3">
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
        </form>


    </div>
    <?php include_once "includes/footer.php"; ?>
</body>

</html>



<?php
session_start();

include_once "includes/header.php";
include "../connection.php"; // Ensure this file contains the correct connection details

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

            if (empty($table)) {
                $_SESSION['toastr'] = array(
                    'type' => 'error',
                    'message' => 'Invalid table ID!',
                    'title' => 'Error'
                );
                exit;
            }

            foreach ($_SESSION['cart'] as $product_id => $item) {
                $product_id = mysqli_real_escape_string($conn, $item['id']);
                $product_qty = mysqli_real_escape_string($conn, $item['qty']);
                $product_price = mysqli_real_escape_string($conn, $item['price']);

                $sql = "INSERT INTO order_details (product_id, table_id, quantity, price) 
                        VALUES ('$product_id', '$table', '$product_qty', '$product_price')";

                if (!mysqli_query($conn, $sql)) {
                    $_SESSION['toastr'] = array(
                        'type' => 'error',
                        'message' => 'Order could not be placed. Please try again later.',
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
    <div class="row">
        <form method="post" action="">
            <div class="col-7 col-sm-9">
                <h1>Products</h1>
                <div class="row">
                    <?php
                    $sql = "SELECT * FROM products WHERE status = 1";
                    $query = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_array($query)) {
                    ?>
                        <div class="col-md-4 mb-3">
                            <div class="card" style="cursor: pointer;" onclick="addToCart('<?php echo $row['id']; ?>', '<?php echo $row['names']; ?>', '<?php echo $row['price']; ?>', '<?php echo $row['qty']; ?>')">
                                <!-- <img src="data:image/jpeg;base64,<?php echo base64_encode($row['product_image']); ?>" class="card-img-top" alt="Product Image"> -->
                                 <img src="https://cdn.pixabay.com/photo/2021/11/20/02/52/apple-6810736_640.png" alt="">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $row['names']; ?></h5>
                                    <p class="card-text">Price: Ksh <?php echo number_format($row['price'], 2); ?></p>
                                    <p class="card-text">Available Qty: <?php echo $row['qty']; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="col-5 col-sm-3">
                <h1>Cart</h1>
                <?php if (!empty($_SESSION['cart'])): ?>
                    <ul class="list-group">
                        <?php foreach ($_SESSION['cart'] as $key => $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo $item['name']; ?>
                                <span class="badge badge-primary badge-pill">Qty: <?php echo $item['qty']; ?></span>
                                <form method="post" action="" style="display: inline-block;">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <input type="number" name="new_qty" value="<?php echo $item['qty']; ?>" min="1" style="width: 50px;">
                                    <input type="hidden" name="update_cart">
                                    <button type="submit" class="btn btn-sm btn-warning">Update</button>
                                </form>
                                <form method="post" action="" style="display: inline-block;">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <input type="hidden" name="remove_from_cart">
                                    <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <form method="post" action="">
                        <input type="hidden" name="place_order">
                        <button type="submit" class="btn btn-primary mt-3">Place Order</button>
                    </form>
                <?php else: ?>
                    <p>Your cart is empty.</p>
                <?php endif; ?>
            </div>
        </form>
    </div>
</body>
</html>





/////////////////////////////// ssuccess table order_detal/////
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

    // Process order
    // if (isset($_POST['place_order'])) {
    //     if (isset($_SESSION['cart'])) {
    //         foreach ($_SESSION['cart'] as $product_id => $item) {
    //             $product_price = $_POST['price'];
    //             $product_qty = $_POST['qty'];
    //             $sql = "INSERT INTO order_details (product_id, table_id, quantity, price) 
    //                 VALUES ('$product_id', '$table', '$product_qty', '$product_price')";
    //             mysqli_query($conn, $sql);
    //         }
    //         unset($_SESSION['cart']); // Clear the cart
    //         $_SESSION['toastr'] = array(
    //             'type' => 'success',
    //             'message' => 'Order placed successfully!',
    //             'title' => 'Success'
    //         );
    //     } else {
    //         $_SESSION['toastr'] = array(
    //             'type' => 'error',
    //             'message' => 'Order failed!',
    //             'title' => 'Error'
    //         );
    //     }
    // }
    // if (isset($_POST['place_order'])) {
    //     if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    //         foreach ($_SESSION['cart'] as $product_id => $item) {

    //             $table = $_GET['table'];
    //             $product_id = mysqli_real_escape_string($conn, $item['id']);
    //             $product_qty = mysqli_real_escape_string($conn, $item['qty']);
    //             $product_price = mysqli_real_escape_string($conn, $item['price']);

    //             // Debugging
    //             echo "Table ID: $table_id <br>";
    //             echo "Product ID: $product_id <br>";
    //             echo "Quantity: $product_qty <br>";
    //             echo "Price: $product_price <br>";

    //             // Proceed with insertion
    //             $sql = "INSERT INTO order_details (product_id, table_id, quantity, price) 
    //                     VALUES ('$product_id', '$table_id', '$product_qty', '$product_price')";

    //             if (mysqli_query($conn, $sql)) {
    //                 // Success
    //             } else {
    //                 $_SESSION['toastr'] = array(
    //                     'type' => 'error',
    //                     'message' => 'Order could not be placed. Please try again later.',
    //                     'title' => 'Error'
    //                 );
    //                 error_log("MySQL Error: " . mysqli_error($conn));
    //             }
    //         }
    //         unset($_SESSION['cart']); // Clear the cart after placing the order
    //         $_SESSION['toastr'] = array(
    //             'type' => 'success',
    //             'message' => 'Order placed successfully!',
    //             'title' => 'Success'
    //         );
    //     } else {
    //         $_SESSION['toastr'] = array(
    //             'type' => 'error',
    //             'message' => 'Your cart is empty!',
    //             'title' => 'Error'
    //         );
    //     }
    // }
    
    if (isset($_POST['place_order'])) {
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $table = mysqli_real_escape_string($conn, $_GET['table']);

            if (empty($table)) {
                $_SESSION['toastr'] = array(
                    'type' => 'error',
                    'message' => 'Invalid table ID!',
                    'title' => 'Error'
                );
                exit;
            }

            foreach ($_SESSION['cart'] as $product_id => $item) {
                $product_id = mysqli_real_escape_string($conn, $item['id']);
                $product_qty = mysqli_real_escape_string($conn, $item['qty']);
                $product_price = mysqli_real_escape_string($conn, $item['price']);

                $sql = "INSERT INTO order_details (product_id, table_id, quantity, price) 
                        VALUES ('$product_id', '$table', '$product_qty', '$product_price')";

                if (!mysqli_query($conn, $sql)) {
                    $_SESSION['toastr'] = array(
                        'type' => 'error',
                        'message' => 'Order could not be placed. Please try again later.',
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
    <div class="row">
        <form method="post" action="">
            <div class="col-7 col-sm-9">
                <h1>Products</h1>
                <div class="row">
                    <?php
                    $sql = "SELECT * FROM products WHERE status = 1";
                    $query = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_array($query)) {
                    ?>
                        <div class="col-md-4 mb-3">
                            <div class="card" style="cursor: pointer;" onclick="addToCart('<?php echo $row['id']; ?>', '<?php echo $row['names']; ?>', '<?php echo $row['price']; ?>', '<?php echo $row['qty']; ?>')">
                                <img src="https://t4.ftcdn.net/jpg/00/65/70/65/360_F_65706597_uNm2SwlPIuNUDuMwo6stBd81e25Y8K8s.jpg" class="card-img-top" alt="Product Image">
                                <div class="card-body">
                                    <h5 class="card-title">Name: <?php echo $row["names"] ?></h5>
                                    <p class="card-text">Price: <?php echo $row["price"] ?></p>
                                    <p class="card-text">Available Quantity: <?php echo $row["qty"] ?></p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            </div>
            <div class="col-5 col-sm-3">
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
        </form>


    </div>
    <?php include_once "includes/footer.php"; ?>
</body>

</html>
