<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INOVA LTD - Inventory Management System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            color: #2c3e50;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        .header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px 0;
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo i {
            font-size: 24px;
            color: white;
        }

        .brand-text h1 {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 2px;
        }

        .brand-text p {
            font-size: 12px;
            color: #7f8c8d;
            font-weight: 500;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            padding: 80px 0;
            text-align: center;
        }

        .hero h2 {
            font-size: 48px;
            font-weight: 300;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 20px;
            color: #7f8c8d;
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }

        /* Features Section */
        .features {
            padding: 80px 0;
            background: white;
        }

        .features h3 {
            text-align: center;
            font-size: 36px;
            font-weight: 300;
            color: #2c3e50;
            margin-bottom: 50px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }

        .feature-card {
            text-align: center;
            padding: 30px 20px;
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .feature-icon i {
            font-size: 28px;
            color: white;
        }

        .feature-card h4 {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .feature-card p {
            color: #7f8c8d;
            font-size: 16px;
        }

        /* Footer */
        .footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 40px 0;
        }

        .footer p {
            font-size: 14px;
            opacity: 0.8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav {
                flex-direction: column;
                gap: 20px;
            }

            .hero h2 {
                font-size: 36px;
            }

            .hero p {
                font-size: 18px;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 200px;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="nav">
                <div class="logo-section">
                    <div class="logo">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="brand-text">
                        <h1>INOVA LTD</h1>
                        <p>Inventory Management</p>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h2>Streamline Your Inventory</h2>
            <p>Professional inventory management system designed to help businesses track, manage, and optimize their stock efficiently.</p>
            
            <div class="cta-buttons">
                <a href="auth/login.php" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    Login
                </a>
                <a href="auth/register1.php" class="btn btn-secondary">
                    <i class="fas fa-user-plus"></i>
                    Create Account
                </a>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <h3>Key Features</h3>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4>Real-time Tracking</h4>
                    <p>Monitor your inventory levels in real-time with automated alerts for low stock items.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Secure & Reliable</h4>
                    <p>Your data is protected with enterprise-grade security and regular backups.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h4>Easy Management</h4>
                    <p>Intuitive interface makes it simple to add, edit, and organize your inventory items.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 INOVA LTD. All rights reserved. | Inventory Management System</p>
        </div>
    </footer>
</body>
</html>