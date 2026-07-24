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
            ->darkMode(true)
            ->colors([
                'primary' => Color::Purple,
                'danger' => Color::Red,
                'gray' => Color::Slate,
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
            ->renderHook(
                'panels::auth.login.form.before',
                fn (): string => '
                <div class="flex flex-col items-center mb-6">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-tr from-purple-600 to-violet-500 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Lesgo <span class="text-purple-600">Admin</span>
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Courier Service</p>
                </div>
                '
            )
            ->renderHook(
                'panels::auth.login.form.after',
                fn (): string => '
                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        &copy; ' . date('Y') . ' Lesgo Logistics. All rights reserved.
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
