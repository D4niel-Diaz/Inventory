<?php 
include_once '../connection.php';
session_start();

// Check if user is logged in as resident
if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'resident') {
    header("Location: ../login.php");
    exit();
}

// Get user information
$user_id = $_SESSION['user_id'];
$stmt_user = $con->prepare("SELECT * FROM `users` WHERE `id` = ?");
$stmt_user->bind_param('s', $user_id);
$stmt_user->execute();
$row_user = $stmt_user->get_result()->fetch_assoc();
$full_name = $row_user['first_name'] . ' ' . $row_user['last_name'];

// Sample items data (in real app, this would come from database)
$items = [
    [
        'id' => 1,
        'item_name' => 'Chairs',
        'ched_req_name' => 'Folding Chairs',
        'on_hand' => 20,
        'borrowed' => 5,
        'image' => 'chair.png'
    ],
    [
        'id' => 2,
        'item_name' => 'Tables',
        'ched_req_name' => 'Folding Tables',
        'on_hand' => 10,
        'borrowed' => 3,
        'image' => 'table.png'
    ],
    [
        'id' => 3,
        'item_name' => 'Tents',
        'ched_req_name' => 'Event Tents',
        'on_hand' => 5,
        'borrowed' => 2,
        'image' => 'tent.png'
    ]
];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['borrow'])) {
        $item_id = $_POST['item_id'];
        $quantity = (int)$_POST['borrow_quantity'];
        
        foreach ($items as &$item) {
            if ($item['id'] == $item_id) {
                $available = $item['on_hand'] - $item['borrowed'];
                if ($quantity <= $available) {
                    $item['borrowed'] += $quantity;
                    $success = "Successfully requested to borrow $quantity {$item['item_name']}";
                } else {
                    $error = "Not enough items available (Only $available left)";
                }
                break;
            }
        }
    } elseif (isset($_POST['return'])) {
        $item_id = $_POST['item_id'];
        $quantity = (int)$_POST['return_quantity'];
        
        foreach ($items as &$item) {
            if ($item['id'] == $item_id) {
                if ($quantity <= $item['borrowed']) {
                    $item['borrowed'] -= $quantity;
                    $success = "Successfully returned $quantity {$item['item_name']}";
                } else {
                    $error = "You can't return more than you borrowed (Currently borrowed: {$item['borrowed']})";
                }
                break;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Barangay Items - User Panel</title>
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../assets/plugins/toastr/toastr.min.css">
    <style>
        :root {
            --primary-color: #0037af;
            --secondary-color: #f8f9fa;
            --success-color: #28a745;
            --danger-color: #dc3545;
        }
        
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            background-color: white;
        }
        
        .navbar-brand {
            font-weight: 600;
            color: var(--primary-color) !important;
        }
        
        .nav-link {
            color: #495057 !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            border-radius: 4px;
            transition: all 0.3s;
        }
        
        .nav-link:hover {
            background-color: rgba(0, 55, 175, 0.1);
            color: var(--primary-color) !important;
        }
        
        .nav-link.active {
            color: var(--primary-color) !important;
            background-color: rgba(0, 55, 175, 0.1);
        }
        
        .user-panel {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0056b3 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .item-card {
            border: none;
            border-radius: 10px;
            margin-bottom: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }
        
        .item-img {
            height: 180px;
            object-fit: cover;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }
        
        .item-img i {
            font-size: 3rem;
        }
        
        .card-header {
            background-color: white;
            border-bottom: none;
            padding: 15px 20px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .quantity-input {
            border-radius: 5px;
            border: 1px solid #ced4da;
            padding: 8px 12px;
            width: 80px;
            text-align: center;
        }
        
        .btn-borrow {
            background-color: var(--primary-color);
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
            transition: all 0.3s;
        }
        
        .btn-borrow:hover {
            background-color: #002b8c;
            transform: translateY(-2px);
        }
        
        .btn-return {
            background-color: var(--success-color);
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
            transition: all 0.3s;
        }
        
        .btn-return:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }
        
        .availability-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .available {
            background-color: rgba(40, 167, 69, 0.2);
            color: var(--success-color);
        }
        
        .not-available {
            background-color: rgba(220, 53, 69, 0.2);
            color: var(--danger-color);
        }
        
        .stats-card {
            background-color: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .stats-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .stats-label {
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        @media (max-width: 768px) {
            .item-img {
                height: 120px;
            }
            
            .navbar-nav {
                flex-direction: row;
                justify-content: flex-end;
            }
            
            .nav-link {
                padding: 0.5rem;
                margin: 0 0.1rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <div class="container">
            <a href="dashboard.php" class="navbar-brand d-flex align-items-center">
                <img src="../assets/dist/img/barangay-logo.png" alt="Logo" class="brand-image img-circle elevation-3 mr-2" style="width: 40px;">
                <span class="font-weight-bold">Barangay Items</span>
            </a>
            
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
                        <i class="fas fa-home mr-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="profile.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : '' ?>">
                        <i class="fas fa-user mr-1"></i> Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../logout.php" class="nav-link">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Content Wrapper -->
    <div class="content-wrapper" style="background-color: #f5f5f5;">
        <div class="content-header">
            <div class="container">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="user-panel">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h3 class="mb-1"><i class="fas fa-box-open mr-2"></i> Barangay Items</h3>
                                    <p class="mb-0">Request to borrow or return barangay equipment and facilities</p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <div class="stats-card">
                                        <div class="stats-value">
                                            <?= count($items) ?>
                                        </div>
                                        <div class="stats-label">
                                            Available Items
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container">
                <!-- Messages -->
                <?php if(isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fas fa-check-circle mr-2"></i> <?= $success ?>
                    </div>
                <?php endif; ?>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fas fa-exclamation-circle mr-2"></i> <?= $error ?>
                    </div>
                <?php endif; ?>

                <!-- Items List -->
                <div class="row">
                    <?php foreach ($items as $item): 
                        $available = $item['on_hand'] - $item['borrowed'];
                        $is_available = $available > 0;
                    ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card item-card">
                                <div class="item-img">
                                    <?php if(isset($item['image'])): ?>
                                        <img src="../assets/dist/img/items/<?= $item['image'] ?>" alt="<?= $item['item_name'] ?>" class="img-fluid">
                                    <?php else: ?>
                                        <i class="fas fa-box"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="card-header">
                                    <h5 class="card-title mb-1"><?= htmlspecialchars($item['item_name']) ?></h5>
                                    <p class="card-subtitle text-muted mb-2"><?= htmlspecialchars($item['ched_req_name']) ?></p>
                                    <span class="availability-badge <?= $is_available ? 'available' : 'not-available' ?>">
                                        <?= $is_available ? 'Available' : 'Not Available' ?>
                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-3">
                                        <div>
                                            <small class="text-muted">Total</small>
                                            <h5 class="mb-0"><?= $item['on_hand'] ?></h5>
                                        </div>
                                        <div>
                                            <small class="text-muted">Available</small>
                                            <h5 class="mb-0 <?= $is_available ? 'text-success' : 'text-danger' ?>"><?= $available ?></h5>
                                        </div>
                                        <div>
                                            <small class="text-muted">Borrowed</small>
                                            <h5 class="mb-0"><?= $item['borrowed'] ?></h5>
                                        </div>
                                    </div>
                                    
                                    <!-- Borrow Form -->
                                    <form method="post" class="mb-3">
                                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                        <div class="form-group mb-2">
                                            <label class="font-weight-bold">Borrow Quantity</label>
                                            <div class="input-group">
                                                <input type="number" name="borrow_quantity" 
                                                       class="form-control quantity-input" 
                                                       min="1" max="<?= $available ?>"
                                                       <?= !$is_available ? 'disabled' : '' ?> required>
                                                <div class="input-group-append">
                                                    <button type="submit" name="borrow" class="btn btn-borrow text-white"
                                                            <?= !$is_available ? 'disabled' : '' ?>>
                                                        <i class="fas fa-hand-holding mr-1"></i> Borrow
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    
                                    <!-- Return Form -->
                                    <form method="post">
                                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                        <div class="form-group mb-0">
                                            <label class="font-weight-bold">Return Quantity</label>
                                            <div class="input-group">
                                                <input type="number" name="return_quantity" 
                                                       class="form-control quantity-input" 
                                                       min="1" max="<?= $item['borrowed'] ?>"
                                                       <?= $item['borrowed'] == 0 ? 'disabled' : '' ?> required>
                                                <div class="input-group-append">
                                                    <button type="submit" name="return" class="btn btn-return text-white"
                                                            <?= $item['borrowed'] == 0 ? 'disabled' : '' ?>>
                                                        <i class="fas fa-undo mr-1"></i> Return
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="main-footer text-center py-3">
        <strong>Barangay Management System &copy; <?= date('Y') ?></strong>
    </footer>
</div>

<!-- JavaScript -->
<script src="../assets/plugins/jquery/jquery.min.js"></script>
<script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/plugins/toastr/toastr.min.js"></script>
<script>
$(document).ready(function() {
    // Validate quantity inputs with better UX
    $('input[name="borrow_quantity"], input[name="return_quantity"]').on('change input', function() {
        const max = parseInt($(this).attr('max'));
        let value = parseInt($(this).val()) || 0;
        
        if (value < 1) {
            $(this).val(1);
            toastr.warning('Minimum quantity is 1');
        } else if (value > max) {
            $(this).val(max);
            toastr.warning(`Maximum quantity is ${max}`);
        }
    });
    
    // Smooth animations
    $('.item-card').hover(
        function() {
            $(this).css('transition', 'all 0.3s ease');
        }, 
        function() {
            $(this).css('transition', 'all 0.3s ease');
        }
    );
});
</script>
</body>
</html>