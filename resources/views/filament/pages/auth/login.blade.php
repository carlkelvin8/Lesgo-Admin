<x-filament-panels::page.simple>
    <style>
        /* Force full height and centering */
        html, body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .fi-simple-page {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-height: 100vh !important;
            width: 100vw !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            padding: 20px !important;
            box-sizing: border-box !important;
            overflow: hidden !important;
        }
        
        .dark .fi-simple-page {
            background: linear-gradient(135deg, #1e1b4b 0%, #4c1d95 100%) !important;
        }
        
        /* Animated Background */
        .fi-simple-page::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(-50%, -50%) rotate(0deg); }
            50% { transform: translate(-50%, -50%) rotate(180deg); }
        }
        
        /* Login Card - Glassmorphism */
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 50px 45px;
            box-shadow: 
                0 32px 64px rgba(0, 0, 0, 0.25),
                0 0 0 1px rgba(255, 255, 255, 0.2);
            width: 100%;
            max-width: 460px;
            text-align: center;
            position: relative;
            z-index: 10;
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .dark .login-card {
            background: rgba(30, 27, 75, 0.9);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(168, 85, 247, 0.2);
            box-shadow: 
                0 32px 64px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(168, 85, 247, 0.1);
        }
        
        /* Floating Elements */
        .login-card::before,
        .login-card::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            animation: pulse 4s ease-in-out infinite;
        }
        
        .login-card::before {
            width: 120px;
            height: 120px;
            top: -60px;
            right: -60px;
            animation-delay: 0s;
        }
        
        .login-card::after {
            width: 80px;
            height: 80px;
            bottom: -40px;
            left: -40px;
            animation-delay: 2s;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.3; }
            50% { transform: scale(1.2); opacity: 0.6; }
        }
        
        /* Brand Section */
        .brand {
            margin-bottom: 40px;
            position: relative;
        }
        
        .brand-icon {
            font-size: 64px;
            margin-bottom: 20px;
            display: inline-block;
            animation: bounce 3s ease-in-out infinite;
            filter: drop-shadow(0 8px 16px rgba(0, 0, 0, 0.1));
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        
        .brand-name {
            font-size: 32px;
            font-weight: 900;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
            letter-spacing: -1px;
        }
        
        .dark .brand-name {
            background: linear-gradient(135deg, #a855f7 0%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .brand-tagline {
            font-size: 15px;
            color: #6b7280;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        .dark .brand-tagline {
            color: #c4b5fd;
        }
        
        /* Welcome Section */
        .welcome {
            margin-bottom: 35px;
        }
        
        .welcome h1 {
            font-size: 28px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }
        
        .dark .welcome h1 {
            color: #f3e8ff;
        }
        
        .welcome p {
            font-size: 16px;
            color: #6b7280;
            font-weight: 500;
            line-height: 1.5;
        }
        
        .dark .welcome p {
            color: #c4b5fd;
        }
        
        /* Form Styling */
        .login-form {
            text-align: left;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 700;
            color: #374151;
            margin-bottom: 8px;
            letter-spacing: 0.3px;
        }
        
        .dark .form-label {
            color: #e9d5ff;
        }
        
        .form-input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 14px;
            font-size: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #fafafa;
            color: #111827;
            font-weight: 500;
        }
        
        .form-input::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #667eea;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }
        
        .dark .form-input {
            background: rgba(88, 28, 135, 0.15);
            border-color: rgba(168, 85, 247, 0.3);
            color: #f3e8ff;
        }
        
        .dark .form-input::placeholder {
            color: #a78bfa;
        }
        
        .dark .form-input:focus {
            border-color: #a855f7;
            background: rgba(88, 28, 135, 0.25);
            box-shadow: 0 0 0 4px rgba(168, 85, 247, 0.15);
        }
        
        /* Submit Button - Premium */
        .submit-btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 800;
            color: white;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 25px;
            box-shadow: 
                0 12px 32px rgba(102, 126, 234, 0.4),
                0 0 0 1px rgba(102, 126, 234, 0.1);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            position: relative;
            overflow: hidden;
        }
        
        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .submit-btn:hover::before {
            left: 100%;
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 
                0 20px 40px rgba(102, 126, 234, 0.5),
                0 0 0 1px rgba(102, 126, 234, 0.2);
        }
        
        .submit-btn:active {
            transform: translateY(-1px);
        }
        
        .dark .submit-btn {
            background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);
            box-shadow: 
                0 12px 32px rgba(168, 85, 247, 0.4),
                0 0 0 1px rgba(168, 85, 247, 0.1);
        }
        
        .dark .submit-btn:hover {
            box-shadow: 
                0 20px 40px rgba(168, 85, 247, 0.5),
                0 0 0 1px rgba(168, 85, 247, 0.2);
        }
        
        /* Error Messages */
        .error-message {
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 14px;
            font-weight: 600;
            text-align: left;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.1);
        }
        
        .dark .error-message {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.15), rgba(220, 38, 38, 0.1));
            border-color: rgba(220, 38, 38, 0.3);
            color: #fca5a5;
        }
        
        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #f3f4f6;
        }
        
        .dark .footer {
            border-top-color: rgba(168, 85, 247, 0.2);
        }
        
        .footer p {
            font-size: 13px;
            color: #9ca3af;
            font-weight: 500;
        }
        
        .dark .footer p {
            color: #a78bfa;
        }
        
        /* Responsive */
        @media (max-width: 640px) {
            .fi-simple-page {
                padding: 15px !important;
            }
            
            .login-card {
                padding: 40px 30px;
                border-radius: 20px;
            }
            
            .brand-icon {
                font-size: 52px;
            }
            
            .brand-name {
                font-size: 26px;
            }
            
            .welcome h1 {
                font-size: 24px;
            }
            
            .form-input {
                padding: 14px 18px;
            }
            
            .submit-btn {
                padding: 16px;
            }
        }
    </style>
    
    <div class="login-card">
        <!-- Brand -->
        <div class="brand">
            <div class="brand-icon">🚚</div>
            <div class="brand-name">Lesgo Admin</div>
            <div class="brand-tagline">Logistics Management</div>
        </div>
        
        <!-- Welcome -->
        <div class="welcome">
            <h1>Welcome Back!</h1>
            <p>Sign in to access your dashboard and manage your logistics operations</p>
        </div>
        
        @if ($errors->any())
            <div class="error-message">
                {{ $errors->first() }}
            </div>
        @endif
        
        <!-- Form -->
        <form wire:submit="authenticate" class="login-form">
            {{ $this->form }}
            
            <button type="submit" class="submit-btn">
                Sign In
            </button>
        </form>
        
        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} Lesgo Admin. All rights reserved.</p>
        </div>
    </div>
</x-filament-panels::page.simple>