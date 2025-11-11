<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="/style.css">

    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
      crossorigin="anonymous"
    />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <style>
        .hide {
            display: none;
        }
    </style>

    <script>
        function toggleInfo(descID, btnID) {
            let desc = document.getElementById(descID);
            let btn = document.getElementById(btnID);

            desc.classList.toggle('hide');

            if (desc.classList.contains('hide')) {
                btn.textContent = 'More';
            } else {
                btn.textContent = 'Less'
            }
        }
    </script>
</head>
<body>

<?php include 'nav.php'; ?>

<p class="h1 text-center mb-5 mt-3">Welcome to My Bookstore</p>

<?php
include 'connect_sql.php';

$q = "SELECT * FROM books";
$result = $conn->query($q);

if ($result ->num_rows > 0) {
    // output data of each row
    echo '<div style="max-width: 60%; margin: 0 auto;">';
    echo "<div class=\"row g-4\">";
    while($row = $result->fetch_assoc()) {
        extract ($row);
        echo "
        <div class=\"col-12 col-sm-6 col-md-3 mb-3\">
        <div class=\"card h-100 shadow-sm\">
        <img src=\"$cover\" class=\"card-img-top\" alt=\"...\" />
        <div class=\"card-body\">
        <h5 class=\"card-title\">\$$price</h5>
        <p class='card-text hide' id='desc_$id'>$description</p>
        <form method='post' action='add_to_cart.php'>
        <input type='hidden' name='product_id' value='$id'>
        <button type='submit' class='btn btn-primary'>Add to Cart</button>
    <button type='button' class=\"btn btn-primary\" id=\"btn_$id\" onclick=\"toggleInfo('desc_$id', 'btn_$id')\">More</button>
    </form>
    
    </div>
    </div>
</div>";
}
echo '</div>';
echo '</div>';
} else {
    echo "no results";
}
?>
</body>
</html>