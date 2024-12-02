<?php
ob_start();
include '../header.php';

if ($_SESSION['admin'] !== true) {
  header('Location: /');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // var_dump($_POST, $_FILES); die;
  $product_name = $_POST['product_name'];
  $product_price = $_POST['product_price'] ?? 0;
  $product_description = $_POST['product_description'];
  $product_stock = $_POST['product_stock'];

  // Handle file upload
  $product_image = $_FILES['product_image'] ?? null;
  $image_path = uploadImage($product_image, "../assets/uploads/");

  if ($image_path) {
    $params = [
      'name' => $product_name,
      'price' => $product_price,
      'image' => $image_path,
      'description' => $product_description,
      'stock' => $product_stock,
    ];

    $product->addProduct($params);
    header('Location: /'); // redirect to products list page
  } else {
    // var_dump($product_image); die;
    echo "<script>alert('Failed to upload image.');</script>";
  }
}
?>

<div class="container mt-5">
  <h2 class="mb-4">Add New Product</h2>
  <form action="" method="post" enctype="multipart/form-data">
    <div class="form-group mb-3">
      <label for="product_name">Product Name</label>
      <input type="text" class="form-control" name="product_name" id="product_name" required>
    </div>
    <div class="form-group mb-3">
      <label for="product_price">Product Price</label>
      <div class="input-group">
        <span class="input-group-text">Rp.</span>
        <input type="number" class="form-control" name="product_price" id="product_price" required>
      </div>
    </div>
    <div class="form-group mb-3">
      <label for="product_stock">Product Stock</label>
      <input type="number" class="form-control" name="product_stock" id="product_stock" required>
    </div>
    <div class="form-group mb-3">
      <label for="product_description">Product Description</label>
      <textarea class="form-control" name="product_description" id="product_description" rows="5" required></textarea>
    </div>
    <div class="form-group mb-3">
      <label for="product_image">Product Image</label>
      <input type="file" class="form-control" name="product_image" id="product_image" required>
    </div>
    <button type="submit" class="btn btn-primary mt-4">Add Product</button>
  </form>
</div>

<?php
include '../footer.php';
?>