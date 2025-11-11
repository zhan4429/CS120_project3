<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
      crossorigin="anonymous"
    />
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
      crossorigin="anonymous"
    ></script>
</head>
<body>
    <?php include 'nav.php'; ?>
    <p class="h1 text-center mb-5 mt-3 display-5">Thank you for your order!</p>
    <?php 
    $orderID = $_GET['orderID'];
    $orderTotal = $_GET['orderTotal'];
    $estimatedDate = date('Y-m-d', time() + (2 * 24 * 60 * 60));
    echo "<div class='container text-center'>
            <p class='h4'>Your order number is: <strong>$orderID</strong></p>
            <p class='h4'>Order Total: <strong>$$orderTotal</strong></p>
            <p class='h4'>Estimated Delivery Date: <strong>$estimatedDate</strong></p>
          </div>";
    ?>
</body>
</html>