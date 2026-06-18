<?php
session_start();

// Check if admin is logged in
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

include "config/DBConn.php";

// Handle approval when admin clicks the Approve button
if(isset($_GET['approve'])) {
    $user_id = $_GET['approve'];
    $update_sql = "UPDATE users SET status = 'approved' WHERE id = $user_id";
    $conn->query($update_sql);
    
    // Redirect to login page with success message for the user
    header("Location: login.php?approved=1");
    exit();
}

// Get all pending users
$pending_sql = "SELECT * FROM users WHERE status = 'pending' ORDER BY created_at DESC";
$pending_result = $conn->query($pending_sql);

// Get all approved users
$approved_sql = "SELECT * FROM users WHERE status = 'approved' ORDER BY created_at DESC";
$approved_result = $conn->query($approved_sql);

// Get counts for statistics
$pending_count = $pending_result->num_rows;
$approved_count = $approved_result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Second Hand Fit</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Stats Cards */
        .stats-container {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            flex: 1;
            min-width: 150px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .stat-card h3 {
            font-size: 36px;
            color: #ff6b35;
            margin-bottom: 10px;
        }
        
        .stat-card p {
            color: #666;
            font-size: 14px;
        }
        
        /* Section Titles */
        .section-title {
            color: #ff6b35;
            margin: 30px 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #ff6b35;
            display: inline-block;
        }
        
        /* User Cards */
        .user-card {
            background: white;
            border: 1px solid #e0e0e0;
            padding: 20px;
            margin: 15px 0;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .user-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .pending {
            border-left: 5px solid #ff9800;
            background: linear-gradient(135deg, #fff9f0 0%, white 100%);
        }
        
        .approved {
            border-left: 5px solid #4caf50;
        }
        
        .user-info {
            margin-bottom: 10px;
        }
        
        .user-label {
            font-weight: bold;
            color: #333;
            display: inline-block;
            width: 100px;
        }
        
        .user-value {
            color: #666;
        }
        
        /* Buttons */
        .approve-btn {
            background: #4caf50;
            color: white;
            padding: 10px 25px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            margin-top: 10px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        
        .approve-btn:hover {
            background: #45a049;
            transform: scale(1.02);
        }
        
        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 8px 20px;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background: #c82333;
        }
        
        /* Empty State */
        .empty-state {
            background: #f9f9f9;
            padding: 30px;
            text-align: center;
            border-radius: 12px;
            color: #666;
        }
        
        /* Navbar */
        .navbar {
            background: #1a1a1a;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .logo a {
            color: white;
            font-size: 20px;
            text-decoration: none;
            font-weight: bold;
        }
        
        .admin-name {
            color: white;
            margin-right: 15px;
        }
        
        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .page-header h1 {
            color: #333;
        }
        
        @media (max-width: 768px) {
            .stats-container {
                flex-direction: column;
            }
            .navbar {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <a href="index.php">👕 Second Hand Fit</a>
        </div>
        <div>
            <span class="admin-name">👋 Welcome, Admin</span>
            <a href="admin_logout.php" class="logout-btn">🚪 Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>📊 Admin Dashboard</h1>
        </div>
        
        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <h3><?php echo $pending_count; ?></h3>
                <p>⏳ Pending Approvals</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $approved_count; ?></h3>
                <p>✅ Approved Users</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $pending_count + $approved_count; ?></h3>
                <p>👥 Total Users</p>
            </div>
        </div>
        
        <!-- Pending Users Section -->
        <h2 class="section-title">⏳ Pending Users (Need Approval)</h2>
        
        <?php if($pending_count > 0): ?>
            <?php while($user = $pending_result->fetch_assoc()): ?>
                <div class="user-card pending">
                    <div class="user-info">
                        <span class="user-label">👤 Username:</span>
                        <span class="user-value"><?php echo htmlspecialchars($user['username']); ?></span>
                    </div>
                    <div class="user-info">
                        <span class="user-label">📧 Email:</span>
                        <span class="user-value"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    <div class="user-info">
                        <span class="user-label">📅 Registered:</span>
                        <span class="user-value"><?php echo $user['created_at']; ?></span>
                    </div>
                    <a href="?approve=<?php echo $user['id']; ?>" class="approve-btn" onclick="return confirm('Approve this user? They will be able to login immediately.')">
                        ✅ Approve User
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                🎉 No pending users! All users are approved.
            </div>
        <?php endif; ?>
        
        <!-- Approved Users Section -->
        <h2 class="section-title">✅ Approved Users</h2>
        
        <?php if($approved_count > 0): ?>
            <?php while($user = $approved_result->fetch_assoc()): ?>
                <div class="user-card approved">
                    <div class="user-info">
                        <span class="user-label">👤 Username:</span>
                        <span class="user-value"><?php echo htmlspecialchars($user['username']); ?></span>
                    </div>
                    <div class="user-info">
                        <span class="user-label">📧 Email:</span>
                        <span class="user-value"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    <div class="user-info">
                        <span class="user-label">✅ Status:</span>
                        <span class="user-value" style="color: #4caf50; font-weight: bold;">Approved</span>
                    </div>
                    <div class="user-info">
                        <span class="user-label">📅 Since:</span>
                        <span class="user-value"><?php echo $user['created_at']; ?></span>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                📭 No approved users yet. Approve some users to see them here!
            </div>
        <?php endif; ?>
    </div>
</body>
</html>