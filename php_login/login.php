<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
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
            --danger-bg: #fdecec;
            --danger: #b3261e;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: "Manrope", "Segoe UI", Arial, sans-serif;
            color: var(--ink);
            min-height: 100vh;
            background: #eef2fb;
        }

        .form-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }
        form {
            width: 100%;
            max-width: 420px;
            background: #fff;
            padding: 44px 40px 40px;
            border-radius: 20px;
            box-shadow: 0 1px 2px rgba(20, 33, 61, 0.06), 0 18px 48px rgba(37, 71, 208, 0.12);
        }
        form h2 { font-size: 2rem; font-weight: 800; letter-spacing: -0.02em; }
        .sub { color: var(--muted); margin: 8px 0 30px; line-height: 1.5; }

        .error {
            background: var(--danger-bg);
            color: var(--danger);
            border-left: 4px solid var(--danger);
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 0.92rem;
            font-weight: 500;
            margin-bottom: 22px;
        }

        label { display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; }
        .field { margin-bottom: 20px; }
        .input-box { position: relative; }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 15px 16px;
            border: 1.5px solid var(--line);
            border-radius: 10px;
            font: inherit;
            color: var(--ink);
            background: #f8faff;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }
        input[type="password"] { padding-right: 70px; }
        input:hover { border-color: #b9c4dd; }
        input:focus {
            outline: none;
            background: #fff;
            border-color: var(--blue);
            box-shadow: 0 0 0 4px var(--sky);
        }
        .toggle {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            padding: 6px 10px;
            background: none;
            border: none;
            border-radius: 6px;
            color: var(--blue);
            font: inherit;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
        }
        .toggle:hover { background: var(--sky); }
        .toggle:focus-visible { outline: 2px solid var(--blue); }

        .btn {
            width: 100%;
            margin-top: 12px;
            padding: 15px;
            border: none;
            border-radius: 10px;
            background: var(--blue);
            color: #fff;
            font: inherit;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(37, 71, 208, 0.3);
            transition: background .15s, transform .1s, box-shadow .15s;
        }
        .btn:hover { background: var(--blue-dark); box-shadow: 0 10px 24px rgba(37, 71, 208, 0.38); }
        .btn:active { transform: translateY(1px); box-shadow: 0 4px 12px rgba(37, 71, 208, 0.3); }
        .btn:focus-visible { outline: 3px solid var(--sky); outline-offset: 2px; }

        @media (max-width: 480px) {
            form { padding: 32px 24px; }
        }
    </style>
</head>
<body>

    <main class="form-wrap">
        <form method="POST" action="verify.php">
            <h2>Log in</h2>
            <p class="sub">Enter your username and password.</p>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="error" role="alert">
                    <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="field">
                <label for="username">Username</label>
                <div class="input-box"><input type="text" id="username" name="username" autocomplete="username" required autofocus></div>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <div class="input-box">
                    <input type="password" id="password" name="password" autocomplete="current-password" required>
                    <button type="button" class="toggle" id="toggle">Show</button>
                </div>
            </div>

            <button type="submit" class="btn">Log in</button>
        </form>
    </main>

    <script>
        var pw = document.getElementById("password");
        var tg = document.getElementById("toggle");
        tg.addEventListener("click", function () {
            var hidden = pw.type === "password";
            pw.type = hidden ? "text" : "password";
            tg.textContent = hidden ? "Hide" : "Show";
        });
    </script>
</body>
</html>