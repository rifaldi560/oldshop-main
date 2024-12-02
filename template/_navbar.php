<?php
// Check if the user is logged in and get user details
if (isset($_SESSION['login'])) {
  $user = getUser($_SESSION['user_id']);
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" href="/">BoShop</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link text-light" href="/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-light" href="/about.php">About</a>
        </li>
      </ul>
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <?php if (!isset($_SESSION['login'])): ?>
          <li class="nav-item">
            <a class="nav-link text-light" href="/auth/login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-light" href="/auth/register.php">Register</a>
          </li>
        <?php else: ?>
          <!-- Profile -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-light" href="#" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              <i class="bi bi-person-fill"></i> <?= htmlspecialchars($user['username']) ?>
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="/checkout.php"><i class="bi bi-box-seam"></i> Checkout</a></li>
              <li><a class="dropdown-item" href="/history.php"><i class="bi bi-clock-history"></i> History</a></li>
              <li><a class="dropdown-item" href="/user.php"><i class="bi bi-person-fill-gear"></i> Data User</a></li>
            </ul>
          </li>
          <?php if ($_SESSION['admin']): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-light" href="#" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                Admin
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="/product/add.php">Add Product</a></li>
                <li><a class="dropdown-item" href="/admin/users.php">User List</a></li>
              </ul>
            </li>
          <?php endif; ?>
          <li class="nav-item">
            <a class="nav-link text-light" href="/auth/logout.php">
              <i class="bi bi-box-arrow-right"></i> Logout
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-light" href="/cart.php"><i class="bi bi-cart-fill"></i></a>
          </li>
        <?php endif; ?>
        <?php if (basename($_SERVER['PHP_SELF']) !== 'search.php'): ?>
          <li class="nav-item">
            <form class="d-flex ms-3" role="search" action="search.php" method="get">
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search">
              <button class="btn btn-outline-success" type="submit">Cari</button>
            </form>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>