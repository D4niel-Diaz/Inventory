<?php 
include_once '../connection.php';
session_start();

try {
    if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'resident') {
        header("Location: ../login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $sql_user = "SELECT * FROM `users` WHERE `id` = ?";
    $stmt_user = $con->prepare($sql_user) or die ($con->error);
    $stmt_user->bind_param('s', $user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $row_user = $result_user->fetch_assoc();
    $first_name_user = $row_user['first_name'];
    $last_name_user = $row_user['last_name'];
    $user_type = $row_user['user_type'];
    $user_image = $row_user['image'];

    $sql_resident = "SELECT * FROM residence_information WHERE residence_id = '$user_id'";
    $query_resident = $con->query($sql_resident) or die ($con->error);
    $row_resident = $query_resident->fetch_assoc();

    $sql = "SELECT * FROM `barangay_information`";
    $query = $con->prepare($sql) or die ($con->error);
    $query->execute();
    $result = $query->get_result();
    while($row = $result->fetch_assoc()) {
        $barangay = $row['barangay'];
        $zone = $row['zone'];
        $district = $row['district'];
        $image = $row['image'];
        $image_path = $row['image_path'];
        $id = $row['id'];
        $postal_address = $row['postal_address'];
    }

} catch(Exception $e) {
    echo $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resident Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        
        .feature-card {
            border: none;
            border-radius: 10px;
            margin-bottom: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            height: 100%;
            text-align: center;
            padding: 30px 20px;
            background-color: white;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--primary-color);
        }
        
        .feature-title {
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .feature-description {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .welcome-card {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            text-align: center;
            background: linear-gradient(rgba(255,255,255,0.9), rgba(255,255,255,0.9)), 
                        url('../assets/logo/cover.jpg') no-repeat center center;
            background-size: cover;
            animation: fadeIn 1s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .user-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            margin-bottom: 15px;
        }
        
        .user-name {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 5px;
        }
        
        .user-location {
            color: #6c757d;
            font-size: 1rem;
            margin-bottom: 20px;
        }
        
        .barangay-logo {
            height: 80px;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .feature-card {
                padding: 20px 15px;
            }
            
            .feature-icon {
                font-size: 2rem;
            }
            
            .welcome-card {
                padding: 20px;
            }
            
            .user-avatar {
                width: 80px;
                height: 80px;
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
                <img src="../assets/dist/img/<?= $image ?>" alt="Logo" class="brand-image img-circle elevation-3 mr-2" style="width: 40px;">
                <span class="font-weight-bold"><?= $barangay ?> <?= $zone ?>, <?= $district ?></span>
            </a>
            
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link active">
                        <i class="fas fa-home mr-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="profile.php" class="nav-link">
                        <i class="fas fa-user mr-1"></i> <?= $last_name_user ?>
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
        <div class="content">
            <div class="container">
                <!-- Welcome Card -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="welcome-card">
                            <img src="../assets/dist/img/<?= $image ?>" alt="Barangay Logo" class="barangay-logo">
                            <?php if(!empty($user_image)): ?>
                                <img src="../assets/dist/img/<?= $user_image ?>" class="user-avatar">
                            <?php else: ?>
                                <div class="user-avatar bg-primary d-flex align-items-center justify-content-center text-white">
                                    <i class="fas fa-user fa-2x"></i>
                                </div>
                            <?php endif; ?>
                            <h2 class="user-name">WELCOME, <?= strtoupper($first_name_user) ?></h2>
                            <p class="user-location"><?= $barangay ?> <?= $zone ?>, <?= $district ?></p>
                        </div>
                    </div>
                </div>

                <!-- Features Grid -->
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <a href="myInfo.php" class="text-decoration-none">
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h4 class="feature-title">My Information</h4>
                                <p class="feature-description">
                                    View and update your personal information and residency details
                                </p>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <a href="certificate.php" class="text-decoration-none">
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="fas fa-certificate"></i>
                                </div>
                                <h4 class="feature-title">Certificates</h4>
                                <p class="feature-description">
                                    Request and manage your barangay certificates and documents
                                </p>
                            </div>
                        </a>
                    </div>
                    
                    
                    
                   
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="main-footer text-center py-3">
        <strong><i class="fas fa-map-marker-alt mr-1"></i> <?= $postal_address ?></strong>
    </footer>
</div>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Add hover effects
document.addEventListener('DOMContentLoaded', function() {
    const featureCards = document.querySelectorAll('.feature-card');
    
    featureCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transition = 'all 0.3s ease';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transition = 'all 0.3s ease';
        });
    });
});
</script>
</body>
</html>