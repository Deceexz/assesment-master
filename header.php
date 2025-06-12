<?php
// Periksa apakah sesi sudah dimulai sebelumnya
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Periksa apakah pengguna sudah login
$user_logged_in = isset($_SESSION['user_id']);

// Sisipkan koneksi ke database
include 'koneksi.php';
?>
<header class="site-header">
    <div class="header-container">
        <div class="site-logo">
            <a href="index.php">Portal Berita Neuron</a>
        </div>
        <div class="user-info">
            <?php if ($user_logged_in): ?>
                <?php
                $user_id = $_SESSION['user_id'];
                $query = "SELECT first_name FROM act_users WHERE user_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result && $result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $first_name = $row['first_name'];
                ?>
                    <span class="greeting">Selamat datang, <?php echo htmlspecialchars($first_name); ?></span>
                    <div class="user-actions">
                        <a href="update_profile.php" class="profile-btn" title="Lihat/Update Profil">
                            <svg xmlns="http://www.w3.org/2000/svg" class="profile-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A8 8 0 0112 16a8 8 0 016.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </a>
                        <form action="logout.php" method="post" class="logout-form">
                            <button type="submit" class="logout-btn" name="logout">Logout</button>
                        </form>
                    </div>
                <?php } else { ?>
                    <span class="greeting">Selamat datang, Pengguna</span>
                <?php } ?>
            <?php else: ?>
                <a href="login_user.php" class="login-btn">Login</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap');

    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
    }

    .site-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e0e0e0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        padding: 15px 30px;
    }

    .header-container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .site-logo a {
        text-decoration: none;
        font-size: 24px;
        font-weight: 800;
        color: #1a237e;
        border-bottom: 2px solid #1a237e;
        display: inline-block;
        animation: float-glow 3s ease-in-out infinite;
    }

    @keyframes float-glow {
        0% {
            transform: translateX(0);
            text-shadow: 0 0 2px #1a237e;
        }
        50% {
            transform: translateX(5px);
            text-shadow: 0 0 10px #3f51b5;
        }
        100% {
            transform: translateX(0);
            text-shadow: 0 0 2px #1a237e;
        }
    }

    .user-info {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 5px;
        margin-top: 10px;
    }

    .greeting {
        font-size: 14px;
        font-weight: 500;
        color: #333;
    }

    .user-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .login-btn,
    .logout-btn {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }

    .login-btn {
        background-color: #e0e0e0;
        color: #1a237e;
    }

    .login-btn:hover {
        background-color: #5D87FF;
        color: white;
    }

    .logout-btn {
        background-color: #ff6b6b;
        color: white;
    }

    .logout-btn:hover {
        background-color: #e63946;
    }

    .profile-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #e0e0e0;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .profile-btn:hover {
        background-color: #5D87FF;
    }

    .profile-icon {
        width: 20px;
        height: 20px;
        color: #1a237e;
    }

    .profile-btn:hover .profile-icon {
        color: #fff;
    }

    @media screen and (max-width: 600px) {
        .header-container {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .user-info {
            align-items: flex-start;
        }

        .user-actions {
            justify-content: flex-start;
        }
    }
</style>
