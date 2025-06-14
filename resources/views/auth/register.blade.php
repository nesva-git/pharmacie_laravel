<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Gestion de Pharmacie') }} - Inscription</title>

    <!-- Bootstrap CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem 0;
        }
        .register-card {
            max-width: 600px;
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .register-header {
            background-color: #007bff;
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        .register-body {
            padding: 2rem;
            background-color: white;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="register-card">
                    <div class="register-header">
                        <h3 class="m-0"><i class="fas fa-clinic-medical me-2"></i>Gestion de Pharmacie</h3>
                        <p class="mb-0 mt-2">Créez votre compte</p>
                    </div>
                    
                    <div class="register-body">
                        <!-- Validation Errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row">
                                <!-- Informations de base -->
                                <div class="col-md-6">
                                    <!-- Name -->
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nom complet</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus>
                                        </div>
                                    </div>

                                    <!-- Email Address -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required>
                                        </div>
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Mot de passe</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password">
                                        </div>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rôle caché défini automatiquement comme pharmacien -->
                                <input type="hidden" name="role" value="pharmacien">

                                <!-- Champs spécifiques aux pharmaciens -->
                                <div class="col-md-6">
                                    <!-- Spécialité -->
                                    <div class="mb-3">
                                        <label for="specialite" class="form-label">Spécialité</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-stethoscope"></i></span>
                                            <input id="specialite" class="form-control" type="text" name="specialite" value="{{ old('specialite') }}">
                                        </div>
                                    </div>

                                    <!-- Téléphone -->
                                    <div class="mb-3">
                                        <label for="telephone" class="form-label">Téléphone</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <input id="telephone" class="form-control" type="text" name="telephone" value="{{ old('telephone') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">S'inscrire</button>
                            </div>

                            <div class="mt-3 text-center">
                                <p>Vous avez déjà un compte ? <a href="{{ route('login') }}">Connectez-vous</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle avec Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script pour le formulaire -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Code JavaScript supplémentaire peut être ajouté ici si nécessaire
        });
    </script>
</body>
</html>
