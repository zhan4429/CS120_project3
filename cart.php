<?php 
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (isset($_POST['delete_item'])) {
    $product_id = $_POST['product_id'];
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

if (isset($_POST['continue_shopping'])) {
    header("Location: products.php");
    exit;
}


if (isset($_POST['checkout'])) {
    include 'connect_sql.php';
    $order_total = 0.0;
    foreach ($_SESSION['cart'] as $product_id => $item) {
        if (is_array($item) && isset($item['quantity'])) {
               $q = "SELECT price from books WHERE id = $product_id";
               $result = $conn->query($q);
               while($row = $result->fetch_assoc()) {
                   extract ($row);
                   $item_cost = $price * $item['quantity'];
                   $order_total += $item_cost;
                }
        }
    }
    $sql_get_next_id = "SELECT COALESCE(MAX(orderID), 1000) + 1 AS next_orderID FROM orders";
    $result = $conn->query($sql_get_next_id);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $next_orderID = $row['next_orderID']; 
    } else {
        die("Error: Could not determine the next order ID.");
    }
    // insert new order into orders table
    $query_order = "INSERT INTO orders (orderID, orderDate, orderTotal) 
                       VALUES ($next_orderID, NOW(), $order_total)";

    if ($conn->query($query_order) !== TRUE) {
        die("Error: " . $query_order . "<br>" . $conn->error);
    }

    $all_items_inserted = true;
    // insert each item in the cart into order_items table
    foreach ($_SESSION['cart'] as $product_id => $item) {
        if (is_array($item) && isset($item['quantity'])) {
            $quantity = $item['quantity'];
            $query_items = "INSERT INTO order_items (orderID, productID, quantity) VALUES($next_orderID, $product_id, $quantity)";
            if ($conn->query($query_items) !== TRUE) {
                $all_items_inserted = false;
                die("Error: " . $query_items . "<br>" . $conn->error);
            }
        }
    }
    // clear the cart
    if ($all_items_inserted) {
        $_SESSION['cart'] = [];
        $conn->close();
        header("Location: checkout.php?orderID=$next_orderID&orderTotal=$order_total");
        exit; 
    } else {
        die("Error: Not all items could be inserted into order_items.");
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="/style.css">
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
      crossorigin="anonymous"
    />
</head>
<body>

<?php include 'nav.php'; ?>
<p class="h1 text-center mb-5 mt-3 display-5">Shopping Cart</p>
<?php
if (!empty($_SESSION['cart'])) {
    $order_total = 0.0;
    include 'connect_sql.php';
    echo "<table class='table table-striped fs-4' style='max-width: 50%; margin: 0 auto;'>";
    echo '<thead><tr><th>Name</th><th>Price</th><th>Quantity</th><th>Cost</th><th>Action</th></tr></thead>';
    echo '<tbody>';
    foreach ($_SESSION['cart'] as $product_id => $item) {
        if (is_array($item) && isset($item['quantity'])) {
               $q = "SELECT name,price from books WHERE id = $product_id";
               $result = $conn->query($q);
               while($row = $result->fetch_assoc()) {
                   extract ($row);
                   $item_cost = $price * $item['quantity'];
                   $order_total += $item_cost;
                    echo "<tr>
                        <td>" . ($name) . "</td>
                        <td>$" . number_format($price, 2) . "</td>
                        <td>" . ($item['quantity']) . "</td>
                        <td>$" . number_format($item_cost, 2) . "</td>
                        <td>
                          <form method='post' style='margin:0;'>
                            <input type='hidden' name='product_id' value='$product_id'>
                            <button type='submit' name='delete_item' class='btn btn-danger btn-sm'>Delete</button>
                          </form>
                    </tr>";
                }
        }
    }
    $conn->close();
    echo "<tr>
            <td colspan='3' class='text-end fw-bold'>Order Total:</td>
            <td colspan='2' class='fw-bold'>$" . number_format($order_total, 2) . "</td>
          </tr>";
    echo '</tbody></table>';
}
?>
<form method="post" class="d-flex justify-content-center gap-3 mt-4">
  <button type="submit" name="continue_shopping" class="btn btn-primary btn-lg">Continue Shopping</button>
  <button type="submit" name="checkout"  class="btn btn-success btn-lg">Checkout</button>
</form>
</body>

</html>