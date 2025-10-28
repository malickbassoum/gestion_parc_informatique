<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Gestion Parc Informatique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            margin: 20px;
        }
        
        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .register-header i {
            font-size: 3rem;
            margin-bottom: 15px;
            display: block;
        }
        
        .register-header h1 {
            font-size: 1.8rem;
            margin: 0;
            font-weight: 600;
        }
        
        .register-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 0.9rem;
        }
        
        .register-body {
            padding: 30px;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .register-footer {
            text-align: center;
            padding: 20px;
            border-top: 1px solid #e9ecef;
            background: #f8f9fa;
        }
        
        .register-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .register-footer a:hover {
            color: #764ba2;
        }
        
        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-right: none;
        }
        
        .form-control.with-icon {
            border-left: none;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <!-- En-tête -->
        <div class="register-header">
            <i class="fas fa-user-plus"></i>
            <h1>Créer un compte</h1>
            <p>Rejoignez notre plateforme de gestion</p>
        </div>
        
        <!-- Corps du formulaire -->
        <div class="register-body">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">
                        <i class="fas fa-user me-2 text-primary"></i>Nom complet
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-id-card"></i>
                        </span>
                        <input id="name" type="text" class="form-control with-icon @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                               placeholder="Votre nom complet">
                    </div>
                    @error('name')
                        <div class="invalid-feedback d-block">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope me-2 text-primary"></i>Adresse Email
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-at"></i>
                        </span>
                        <input id="email" type="email" class="form-control with-icon @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required autocomplete="email"
                               placeholder="votre@email.com">
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-2 text-primary"></i>Mot de passe
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-key"></i>
                        </span>
                        <input id="password" type="password" class="form-control with-icon @error('password') is-invalid @enderror" 
                               name="password" required autocomplete="new-password"
                               placeholder="Minimum 8 caractères">
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">
                        <i class="fas fa-lock me-2 text-primary"></i>Confirmer le mot de passe
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-key"></i>
                        </span>
                        <input id="password_confirmation" type="password" class="form-control with-icon" 
                               name="password_confirmation" required autocomplete="new-password"
                               placeholder="Confirmez votre mot de passe">
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-register btn-lg">
                        <i class="fas fa-user-plus me-2"></i>S'inscrire
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Pied de page -->
        <div class="register-footer">
            <a href="{{ route('login') }}">
                <i class="fas fa-sign-in-alt me-1"></i>Déjà un compte ? Se connecter
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>