<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VoteMe - Plateforme de Vote en Ligne</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #2a2a2a 100%);
            color: #ffffff;
            height: 100vh;
            overflow: hidden;
            position: relative;
        }

        /* Effet de particules en arrière-plan */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                    radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.02) 2px, transparent 2px),
                    radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.02) 2px, transparent 2px),
                    radial-gradient(circle at 40% 80%, rgba(255, 255, 255, 0.02) 2px, transparent 2px);
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
            z-index: 1;
        }

        .welcome-card {
            background: rgba(20, 20, 20, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 60px 50px;
            text-align: center;
            box-shadow:
                    0 25px 50px rgba(0, 0, 0, 0.5),
                    inset 0 1px 0 rgba(255, 255, 255, 0.1);
            max-width: 500px;
            width: 90%;
            transform: translateY(0);
            transition: transform 0.3s ease;
        }

        .welcome-card:hover {
            transform: translateY(-5px);
        }

        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            border-radius: 20px;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: bold;
            color: white;
            box-shadow: 0 10px 25px rgba(255, 107, 53, 0.3);
        }

        .title {
            font-size: 2.8em;
            font-weight: 700;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #ffffff, #cccccc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            font-size: 1.3em;
            color: #b0b0b0;
            margin-bottom: 40px;
            line-height: 1.5;
        }

        .feature-list {
            margin-bottom: 40px;
            text-align: left;
        }

        .feature {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            color: #e0e0e0;
        }

        .feature-icon {
            width: 20px;
            height: 20px;
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            border-radius: 50%;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: white;
        }

        .btn-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .btn {
            padding: 18px 35px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            color: white;
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(255, 107, 53, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: #ffffff;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .footer-text {
            margin-top: 30px;
            font-size: 0.9em;
            color: #888;
        }

        /* Animation d'entrée */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .welcome-card {
            animation: fadeInUp 0.8s ease-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .welcome-card {
                padding: 40px 30px;
                margin: 20px;
            }

            .title {
                font-size: 2.2em;
            }

            .subtitle {
                font-size: 1.1em;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="welcome-card">
        <div class="logo">V</div>

        <h1 class="title">VotMe</h1>
        <p class="subtitle">Plateforme sécurisée de vote en ligne<br><strong>En cours de développement</strong></p>

        <div class="status-badge ">
            <span class="status-dot"></span>
            Bientôt disponible
        </div>

        <div class="feature-list">
            <div class="feature">
                <div class="feature-icon">⚡</div>
                <span>Sécurité maximale et chiffrement</span>
            </div>
            <div class="feature">
                <div class="feature-icon">⚡</div>
                <span>Interface simple et intuitive</span>
            </div>
            <div class="feature">
                <div class="feature-icon">⚡</div>
                <span>Résultats en temps réel</span>
            </div>
        </div>

        <div class="btn-container">
            <a href="#" class="btn btn-primary">M'informer du lancement</a>
        </div>

        <p class="footer-text">Restez connecté pour la révolution du vote numérique</p>
    </div>
</div>
</body>
</html>