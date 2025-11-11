<?php
session_start();
if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = array();
  } 

if (isset($_POST['product_id'])) {
      $product_id = $_POST['product_id'];
      if (isset($_SESSION['cart'][$product_id])) {
          $_SESSION['cart'][$product_id]['quantity'] += 1;
      }
      else {
          $_SESSION['cart'][$product_id] = ['quantity' => 1];
      }
}
session_write_close();
header('Location: cart.php');
exit;
?>
