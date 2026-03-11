// Logout Confirmation Handler
document.addEventListener('DOMContentLoaded', function() {
    // Wait for the page to fully load
    setTimeout(function() {
        // Find all logout links
        const logoutLinks = document.querySelectorAll('a[href*="logout"], button[wire\\:click*="logout"]');
        
        logoutLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const href = this.getAttribute('href');
                const wireClick = this.getAttribute('wire:click');
                
                // Show confirmation dialog
                if (confirm('Are you sure you want to logout from your account?')) {
                    if (href) {
                        window.location.href = href;
                    } else if (wireClick) {
                        // Trigger Livewire action
                        eval(wireClick);
                    }
                }
            });
        });
        
        // Also handle user menu logout
        const userMenu = document.querySelector('[x-data*="userMenu"]');
        if (userMenu) {
            const observer = new MutationObserver(function(mutations) {
                const logoutItem = document.querySelector('[data-filament-user-menu-item="logout"]');
                if (logoutItem) {
                    logoutItem.addEventListener('click', function(e) {
                        if (!confirm('Are you sure you want to logout from your account?')) {
                            e.preventDefault();
                            e.stopPropagation();
                        }
                    });
                }
            });
            
            observer.observe(userMenu, { childList: true, subtree: true });
        }
    }, 1000);
});
