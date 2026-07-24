<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(\App\Filament\Pages\Auth\Login::class)
            ->brandName('Lesgo Admin')
            ->brandLogo(fn () => view('filament.components.logo'))
            ->darkModeBrandLogo(fn () => view('filament.components.logo'))
            ->brandLogoHeight('2.5rem')
            ->colors([
                'primary' => Color::Sky,
                'danger' => Color::Red,
                'gray' => Color::Zinc,
                'info' => Color::Blue,
                'success' => Color::Green,
                'warning' => Color::Amber,
            ])
            ->font('Inter')
            ->theme(asset('css/filament/admin/theme.css'))
            ->maxContentWidth('full')
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('16rem')
            ->navigationGroups([
                'Dashboard',
                'User Management',
                'Business',
                'Operations',
                'Finance',
                'System',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets are auto-discovered
            ])
            ->databaseNotifications(false)
            ->databaseNotificationsPolling('30s')
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->spa()
            ->renderHook(
                'panels::auth.login.form.before',
                fn (): string => '
                <div class="flex flex-col items-center mb-6">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-tr from-sky-500 to-indigo-500 shadow-lg shadow-sky-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-black tracking-tight text-gray-900 dark:text-white">
                        Lesgo <span class="text-sky-500">Admin</span>
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Premium Logistics Suite</p>
                </div>
                '
            )
            ->renderHook(
                'panels::auth.login.form.after',
                fn (): string => '
                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        &copy; ' . date('Y') . ' Lesgo Logistics &bull; All rights reserved
                    </p>
                </div>
                '
            )
            ->renderHook(
                'panels::body.end',
                fn (): string => '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        setTimeout(function() {
                            const logoutButtons = document.querySelectorAll(\'[data-filament-user-menu-item="logout"]\');
                            logoutButtons.forEach(function(button) {
                                button.addEventListener("click", function(e) {
                                    if (!confirm("Are you sure you want to logout from your account?")) {
                                        e.preventDefault();
                                        e.stopPropagation();
                                        return false;
                                    }
                                });
                            });
                        }, 500);
                    });
                </script>'
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
