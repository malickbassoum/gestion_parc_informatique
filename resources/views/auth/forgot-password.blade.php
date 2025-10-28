<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - Gestion Parc Informatique</title>
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
        
        .password-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            margin: 20px;
        }
        
        .password-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .password-header i {
            font-size: 3rem;
            margin-bottom: 15px;
            display: block;
        }
        
        .password-header h1 {
            font-size: 1.8rem;
            margin: 0;
            font-weight: 600;
        }
        
        .password-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 0.9rem;
        }
        
        .password-body {
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
        
        .btn-password {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-password:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .password-footer {
            text-align: center;
            padding: 20px;
            border-top: 1px solid #e9ecef;
            background: #f8f9fa;
        }
        
        .password-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .password-footer a:hover {
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
    <div class="password-container">
        <!-- En-tête -->
        <div class="password-header">
            <i class="fas fa-key"></i>
            <h1>Mot de passe oublié</h1>
            <p>Réinitialisez votre mot de passe</p>
        </div>
        
        <!-- Corps du formulaire -->
        <div class="password-body">
            <div class="mb-4 text-center">
                <p class="text-muted">
                    <i class="fas fa-info-circle me-2"></i>
                    Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.
                </p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success mb-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

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
                               name="email" value="{{ old('email') }}" required autofocus
                               placeholder="votre@email.com">
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-password btn-lg">
                        <i class="fas fa-paper-plane me-2"></i>Envoyer le lien
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Pied de page -->
        <div class="password-footer">
            <a href="{{ route('login') }}">
                <i class="fas fa-arrow-left me-1"></i>Retour à la connexion
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>