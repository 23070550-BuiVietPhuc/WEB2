<!DOCTYPE html>
<html lang="en">
<meta charset="utf-8">
<head>
    <title>User login and Registration</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fffdf5;
            margin: 0;
            padding: 0;
        }

        h2 {
            color: #d68b00;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .container {
            width: 80%;
            max-width: 900px;
            margin: 40px auto;
            display: flex;
            gap: 40px;
            justify-content: center;
        }

        .box {
            flex: 1;
            background-color: #ffffff;
            border: 1px solid #f2e3b3;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.07);
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #444;
            font-size: 14px;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #e0c77f;
            border-radius: 8px;
            margin-bottom: 15px;
            background-color: #fffdf7;
        }

        input:focus {
            outline: none;
            border-color: #d28a00;
            background-color: #fff9e6;
        }

        button {
            background-color: #ffb100;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            transition: 0.2s ease;
        }

        button:hover {
            background-color: #e29a00;
        }

    </style>

</head>
<body>

    <div class="container">
        
        <!-- Login -->
        <div class="box">
            <h2>Login Here</h2>
            <form action="validation.php" method="post">
                <div>
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>

                <div>
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <button type="submit">Login</button>
            </form>
        </div>

        <!-- Registration -->
        <div class="box">
            <h2>Registration Here</h2>
            <form action="registration.php" method="post">

                <div>
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>

                <div>
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <div>
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

                <div>
                    <label>Phone Number</label>
                    <input type="text" name="phone" required>
                </div>

                <button type="submit">Register</button>
            </form>
        </div>

    </div>

</body>
</html>
