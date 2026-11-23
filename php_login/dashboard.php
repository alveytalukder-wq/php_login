<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$username = htmlspecialchars($_SESSION['username']);
$user_id  = (int) $_SESSION['user_id'];
$initial  = strtoupper(substr($username, 0, 1));

$total_users = "-";
$conn = @mysqli_connect("localhost", "root", "", "testdb");
if ($conn) {
    $res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
    if ($res) {
        $row = mysqli_fetch_assoc($res);
        $total_users = (int) $row['total'];
    }
    mysqli_close($conn);
}

$hour = (int) date("G");
if ($hour < 12)      $greeting = "Welcome";
elseif ($hour < 18)  $greeting = "Welcome";
else                 $greeting = "Welcome";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #14213d;
            --blue: #2547d0;
            --blue-dark: #1a35a8;
            --sky: #e8eefc;
            --line: #d5dbe8;
            --muted: #5b6577;
            --bg: #f3f6fc;
            --green: #1a7f4b;
            --green-bg: #e4f5ec;
            --amber: #a85d00;
            --amber-bg: #fdf0dc;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: "Manrope", "Segoe UI", Arial, sans-serif;
            color: var(--ink);
            background: var(--bg);
            min-height: 100vh;
            display: grid;
            grid-template-columns: 250px 1fr;
        }

        .sidebar {
            background: var(--blue);
            color: #fff;
            padding: 28px 18px;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow: hidden;
        }
        .sidebar::after {
            content: "";
            position: absolute;
            width: 280px; height: 280px;
            border-radius: 50%;
            border: 34px solid rgba(255, 255, 255, 0.07);
            bottom: -110px; left: -90px;
        }
        .brand { font-size: 1.3rem; font-weight: 800; letter-spacing: -0.01em; padding: 0 12px 28px; }
        .menu { list-style: none; position: relative; z-index: 1; }
        .menu a {
            display: block;
            padding: 12px 14px;
            border-radius: 10px;
            color: #cdd8f7;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 4px;
            transition: background .15s, color .15s;
        }
        .menu a:hover { background: rgba(255, 255, 255, 0.1); color: #fff; }
        .menu a.active { background: #fff; color: var(--blue); }
        .menu a:focus-visible { outline: 2px solid #fff; outline-offset: 2px; }
        .side-logout {
            margin-top: auto;
            position: relative;
            z-index: 1;
            display: block;
            text-align: center;
            padding: 12px;
            border-radius: 10px;
            border: 1.5px solid rgba(255, 255, 255, 0.35);
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            transition: background .15s;
        }
        .side-logout:hover { background: rgba(255, 255, 255, 0.12); }

        .main { padding: 28px 36px 56px; min-width: 0; }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }
        .topbar .date { color: var(--muted); font-weight: 500; }
        .user { display: flex; align-items: center; gap: 12px; }
        .avatar {
            width: 42px; height: 42px;
            border-radius: 50%;
            background: var(--blue);
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 700;
        }
        .user-name { font-weight: 700; line-height: 1.2; }
        .user-role { font-size: 0.8rem; color: var(--muted); }

        h1 { font-size: 2.1rem; font-weight: 800; letter-spacing: -0.02em; }
        .lead { color: var(--muted); margin-top: 6px; }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-top: 28px;
        }
        .card {
            background: #fff;
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 1px 2px rgba(20, 33, 61, 0.05), 0 8px 24px rgba(37, 71, 208, 0.06);
        }
        .stat .label { color: var(--muted); font-size: 0.88rem; font-weight: 500; }
        .stat .value { font-size: 2rem; font-weight: 800; margin-top: 6px; letter-spacing: -0.02em; }
        .badge {
            display: inline-block;
            margin-top: 8px;
            padding: 4px 12px;
            border-radius: 999px;
            background: var(--green-bg);
            color: var(--green);
            font-weight: 600;
            font-size: 0.82rem;
        }

        .grid2 {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 18px;
            margin-top: 18px;
        }
        .card h2 { font-size: 1.1rem; font-weight: 800; margin-bottom: 16px; }

        .profile-row {
            display: flex;
            justify-content: space-between;
            padding: 13px 0;
            border-bottom: 1px solid #edf0f7;
        }
        .profile-row:last-child { border-bottom: none; }
        .profile-row span:first-child { color: var(--muted); }
        .profile-row span:last-child { font-weight: 600; }

        .activity { list-style: none; }
        .activity li {
            display: flex;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #edf0f7;
            align-items: flex-start;
        }
        .activity li:last-child { border-bottom: none; }
        .dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: var(--blue);
            margin-top: 6px;
            flex-shrink: 0;
        }
        .activity .what { font-weight: 600; }
        .activity .when { color: var(--muted); font-size: 0.85rem; }

        @media (max-width: 900px) {
            body { grid-template-columns: 1fr; }
            .sidebar {
                position: static;
                height: auto;
                flex-direction: row;
                align-items: center;
                flex-wrap: wrap;
                padding: 14px 16px;
                gap: 8px;
            }
            .sidebar::after { display: none; }
            .brand { padding: 0; margin-right: auto; }
            .menu { display: none; }
            .side-logout { margin-top: 0; padding: 9px 16px; }
            .main { padding: 22px 16px 40px; }
            .user-name, .user-role { display: none; }
            h1 { font-size: 1.7rem; }
            .stats, .grid2 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="brand">MyApp</div>
        <ul class="menu">
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="#profile">Profile</a></li>
            <li><a href="#activity">Activity</a></li>
        </ul>
        <a class="side-logout" href="logout.php">Log out</a>
    </aside>

    <main class="main">
        <div class="topbar">
            <span class="date"><?php echo date("l, d F Y"); ?></span>
            <div class="user">
                <div>
                    <div class="user-name"><?php echo $username; ?></div>
                    <div class="user-role">Member</div>
                </div>
                <div class="avatar"><?php echo $initial; ?></div>
            </div>
        </div>

        <h1><?php echo $greeting; ?>, <?php echo $username; ?></h1>
        <p class="lead">Here is a quick look at your account.</p>

        <section class="stats">
            <div class="card stat">
                <div class="label">Total users</div>
                <div class="value"><?php echo $total_users; ?></div>
            </div>
            <div class="card stat">
                <div class="label">Your user ID</div>
                <div class="value">#<?php echo $user_id; ?></div>
            </div>
            <div class="card stat">
                <div class="label">Session</div>
                <!-- <div class="value"><?php echo date("h:i A"); ?></div> -->
                <span class="badge">Active</span>
            </div>
        </section>

        <section class="grid2">
            <div class="card" id="profile">
                <h2>Profile</h2>
                <div class="profile-row"><span>Username</span><span><?php echo $username; ?></span></div>
                <div class="profile-row"><span>User ID</span><span>#<?php echo $user_id; ?></span></div>
                <div class="profile-row"><span>Role</span><span>Member</span></div>
                <div class="profile-row"><span>Status</span><span>Signed in</span></div>
            </div>

            <div class="card" id="activity">
                <h2>Recent activity</h2>
                <ul class="activity">
                    <li><span class="dot"></span><div><div class="what">Signed in successfully</div><div class="when">Just now</div></div></li>
                    <li><span class="dot"></span><div><div class="what">Password verified</div><div class="when">Just now</div></div></li>
                    <li><span class="dot"></span><div><div class="what">Session started</div><div class="when">Just now</div></div></li>
                </ul>
            </div>
        </section>
    </main>
</body>
</html>