<?php

include '../header.php';

$id = 1;

// if (!isset($id)) {
//   header('Location: /');
//   exit;
// }

$product = $product->deleteProduct($id);

// Using alert
if ($product) {
  echo "<script>alert('Product deleted successfully')</script>";
  echo "<script>window.location = '/'</script>";
} else {
  echo "<script>alert('Product deletion failed')</script>";
  echo "<script>window.location = '/'</script>";
}
?>


<?php
include '../footer.php'
  ?>