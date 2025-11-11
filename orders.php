<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
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
    <p class="h1 text-center mb-5 mt-3 display-5">Your Orders</p>
    <?php

    require("dbparams.inc");
    $conn = new mysqli($server, $id, $pw );
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error); //not for production
    }
    $conn->select_db($db);
    $q = "SELECT orders.orderID, orders.orderDate, orders.orderTotal, books.name, books.price, order_items.quantity, ROUND(books.price * order_items.quantity,2) AS item_total
    FROM orders 
    INNER JOIN order_items on orders.orderID = order_items.orderID
    INNER JOIN books on order_items.productID = books.id
    ORDER BY orders.orderDate DESC;";
    $result = $conn->query($q);

    if ($result ->num_rows > 0) {
        // output data of each row

        echo "<table class='table table-striped fs-4' style='max-width: 60%; margin: 0 auto;'>
  <thead>
    <tr>
      <th scope='col'>Order ID</th>
      <th scope='col'>Order Date</th>
      <th scope='col'>Order Total</th>
      <th scope='col'>Product</th>
      <th scope='col'>Price</th>
      <th scope='col'>Quantity</th>
      <th scope='col'>Item Total</th>
    </tr>
    </thead>
    <tbody>";
        $current_orderID = null;
        while($row = $result->fetch_assoc()) {
            extract ($row);
            if ($orderID != $current_orderID)
            {
                echo "
                <tr>
                <th scope='row'>$orderID</th>
                <td>$orderDate</td>
                <td>$orderTotal</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                </tr>
                ";
                echo "
                <tr>
                <th scope='row'></th>
                <td></td>
                <td></td>
                <td>$name</td>
                <td>$price</td>
                <td>$quantity</td>
                <td>$item_total</td>
                </tr>";
                $current_orderID = $orderID;
            } else {
            echo "
                <tr>
                <th scope='row'></th>
                <td></td>
                <td></td>
                <td>$name</td>
                <td>$price</td>
                <td>$quantity</td>
                <td>$item_total</td>
                </tr>
            ";
            }
    }
    echo '</tr>';
    echo '</tbody>';
    echo '</table>';
    } else {
        echo "no results";
    }
    ?>

<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
  crossorigin="anonymous"
></script>
</body>