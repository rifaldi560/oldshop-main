<?php

require 'scripts/database/DBController.php';

require 'scripts/Product.php';

require 'scripts/Cart.php';

$db = new DBController();

$product = new Products($db);
$products = $product->getData();

$cart = new Cart();

$host = 'localhost';
$usernames = 'root';
// $password = '';
$database = 'oldshop';

$conn = mysqli_connect($host, $usernames, '', $database);

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

function query($query)
{
    global $conn;

    $result = mysqli_query($conn, $query);
    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

// -----------------------------
// |       Auth                |
// -----------------------------

function register($data)
{
    global $conn;

    $username = stripslashes($data["username"]);
    $firstname = stripslashes($data["firstname"]);
    $lastname = stripslashes($data["lastname"]);
    $email = stripslashes($data["email"]);
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password2 = mysqli_real_escape_string($conn, $data["password2"]);
    $register_date = date("Y-m-d H:i:s");

    // Check if username already exists
    $result = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");
    if (mysqli_fetch_assoc($result)) {
        echo "
        <script>
            alert('Username sudah terdaftar');
        </script>
        ";
        return false;
    }

    // Check password confirmation
    if ($password !== $password2) {
        echo "
        <script>
            alert('Password tidak sama');
        </script>
        ";
        return false;
    }

    // Encrypt password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data
    mysqli_query($conn, "INSERT INTO users VALUES(NULL, '$firstname', '$lastname', '$username',  '$password', '$email', NULL, NULL, '$register_date', NULL)");

    return mysqli_affected_rows($conn);
}

function login($data)
{
    global $conn;

    $username = strtolower(stripslashes($data["username"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);

    // Check if username exists
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (!$result) {
        echo "
        <script>
            alert('Username tidak terdaftar');
        </script>
        ";
        return false;
    }

    // Check password
    $row = mysqli_fetch_assoc($result);
    if (!password_verify($password, $row["password"])) {
        echo "
        <script>
            alert('Password salah');
        </script>
        ";
        return false;
    }

    // Set session
    $_SESSION["login"] = true;
    $_SESSION["user_id"] = $row["id"];

    return true;
}

function logout()
{
    $_SESSION = [];
    session_unset();
    session_destroy();

    return true;
}

// -----------------------------
// |       Products            |
// -----------------------------

function getProducts()
{
    global $conn;

    $result = mysqli_query($conn, "SELECT * FROM products");
    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

function getProduct($id)
{
    global $conn;

    $result = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");

    return mysqli_fetch_assoc($result);
}

// -----------------------------
// |       Cart                |
// -----------------------------
function addToCart($data)
{
    $product_id = $data['product_id'];
    $user_id = $data['user_id'];
    $quantity = $data['quantity'];

    // Initialize cart session if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if product is already in cart
    if (isset($_SESSION['cart'][$user_id][$product_id])) {
        // Update the quantity of the existing product in the cart
        $_SESSION['cart'][$user_id][$product_id]['quantity'] += $quantity;
    } else {
        // Add new product to cart
        $_SESSION['cart'][$user_id][$product_id] = [
            'product_id' => $product_id,
            'quantity' => $quantity,
        ];
    }

    return true;
}


function removeFromCart($data)
{
    $product_id = $data['product_id'];
    $user_id = $data['user_id'];

    // Check if product is in cart
    if (!isset($_SESSION['cart'][$user_id][$product_id])) {
        echo "
        <script>
            alert('Product tidak ada di cart');
        </script>
        ";
        return false;
    }

    // Remove product from cart
    unset($_SESSION['cart'][$user_id][$product_id]);

    return true;
}

function getCart($user_id)
{
    if (!isset($_SESSION['cart'][$user_id])) {
        return [];
    }

    return $_SESSION['cart'][$user_id];
}

function getCartTotal($user_id)
{
    global $product;
    $cart = getCart($user_id);
    $total = 0;

    foreach ($cart as $product) {
        $product_id = $product['product_id'];
        $quantity = $product['quantity'];

        $detail = getProduct($product_id);
        $total += $detail['price'] * $quantity;
    }

    return isset($total) ? $total : 0;
}

function uploadImage($file, $target_dir = "assets/uploads/")
{
    $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $filename = time() . '.' . $imageFileType;
    $target_file = $target_dir . $filename;
    $uploadOk = 1;

    // Check if image file is an actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size
    if ($file["size"] > 500000) { // 500KB
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        return false;
    } else {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return "uploads/" . $filename; // Return the file path relative to the uploads directory
        } else {
            echo "Sorry, there was an error uploading your file.";
            return false;
        }
    }
}

function excerpt($text, $length = 100)
{
    $text = strip_tags($text);
    if (strlen($text) > $length) {
        $text = substr($text, 0, $length);
        $text = substr($text, 0, strrpos($text, " "));
        $text .= '...';
    }
    return $text;
}

// is admin check
function isAdmin()
{
    if (isset($_SESSION['login'])) {
        global $conn;
        $user_id = $_SESSION['user_id'];
        $result = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
        $user = mysqli_fetch_assoc($result);
        return $user['is_admin'] == true;
    }
    return false;
}

function getUser($id)
{
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
    return mysqli_fetch_assoc($result);
}

function getUsers()
{
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM users");
    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

// Edit user username, first name, last name, email, phone, address
function editUser($data)
{
    global $conn;

    $id = $data['id'];
    $username = stripslashes($data["username"]);
    $firstname = stripslashes($data["firstname"]);
    $lastname = stripslashes($data["lastname"]);
    $email = stripslashes($data["email"]);
    $phone = stripslashes($data["phone"]);
    $address = stripslashes($data["address"]);

    // Check if username already exists
    $result = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username' AND id != $id");
    if (mysqli_fetch_assoc($result)) {
        echo "
        <script>
            alert('Username sudah terdaftar');
        </script>
        ";
        return false;
    }

    // Update user data
    mysqli_query($conn, "UPDATE users SET username = '$username', first_name = '$firstname', last_name = '$lastname', email = '$email', phone = '$phone', address = '$address' WHERE id = $id");

    return mysqli_affected_rows($conn);
}

function getUserOrders($user_id)
{
    global $conn;

    $orders = [];

    // Prepared statement to fetch transactions
    $stmt = $conn->prepare("
        SELECT t.trans_id, t.total_price, t.created_at, 
        ti.product_id, ti.quantity, ti.price as item_price, 
        p.name as product_name, p.description as product_description
        FROM transactions t
        LEFT JOIN transaction_items ti ON t.trans_id = ti.trans_id
        LEFT JOIN products p ON ti.product_id = p.id
        WHERE t.user_id = ?
        ORDER BY t.created_at DESC, t.trans_id, p.name
    ");

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Process results
    while ($row = $result->fetch_assoc()) {
        $trans_id = $row['trans_id'];
        if (!isset($orders[$trans_id])) {
            $orders[$trans_id] = [
                'trans_id' => $trans_id,
                'total_price' => $row['total_price'],
                'created_at' => $row['created_at'],
                'items' => []
            ];
        }
        $orders[$trans_id]['items'][] = [
            'product_id' => $row['product_id'],
            'product_name' => $row['product_name'],
            'quantity' => $row['quantity'],
            'item_price' => $row['item_price']
        ];
    }

    $stmt->close();

    return $orders;
}


// -----------------------------
// |       Checkout            |
// -----------------------------

function checkout($data)
{
    global $conn;

    $user_id = $data['user_id'];
    $total = $data['total'];

    // Check if cart is empty
    if (empty($total)) {
        echo "
        <script>
            alert('Cart is empty');
        </script>
        ";
        return false;
    }

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Insert order data
        $order_date = date("Y-m-d H:i:s");
        $stmt = $conn->prepare("INSERT INTO transactions (user_id, total_price, created_at) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $user_id, $total, $order_date);
        $stmt->execute();
        $trans_id = $stmt->insert_id;
        $stmt->close();

        foreach ($_SESSION['cart'][$user_id] as $product) {
            $product_id = $product['product_id'];
            $quantity = $product['quantity'];

            // Edit the stock so that it decreases according to the quantity ordered
            $product_detail = getProduct($product_id);
            $stock = $product_detail['stock'] - $quantity;

            if ($stock < 0) {
                // Remove the product from cart
                unset($_SESSION['cart'][$user_id][$product_id]);

                // Rollback transaction
                throw new Exception("Not enough stock for product ID: $product_id");
            }

            $stmt = $conn->prepare("UPDATE products SET stock = ? WHERE id = ?");
            $stmt->bind_param("ii", $stock, $product_id);
            $stmt->execute();
            $stmt->close();

            // Insert into transaction_items
            $price = $product_detail['price'];
            $stmt = $conn->prepare("INSERT INTO transaction_items (trans_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $trans_id, $product_id, $quantity, $price);
            $stmt->execute();
            $stmt->close();
        }

        // Commit transaction
        mysqli_commit($conn);

        // Clear cart
        unset($_SESSION['cart'][$user_id]);

        return true;

    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($conn);

        // Log error
        error_log($e->getMessage());

        echo "
        <script>
            alert('Checkout failed: " . $e->getMessage() . "');
        </script>
        ";
        return false;
    }
}
