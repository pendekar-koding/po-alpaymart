// Loading overlay system
const LoadingOverlay = {
    show() {
        if (!document.getElementById('loading-overlay')) {
            const overlay = document.createElement('div');
            overlay.id = 'loading-overlay';
            overlay.innerHTML = `
                <div class="loading-content">
                    <div class="spinner"></div>
                    <p>Memproses...</p>
                </div>
            `;
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.7);
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
            `;
            
            const style = document.createElement('style');
            style.textContent = `
                .loading-content {
                    text-align: center;
                    color: white;
                }
                .spinner {
                    width: 50px;
                    height: 50px;
                    border: 5px solid rgba(255,255,255,0.3);
                    border-top: 5px solid white;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                    margin: 0 auto 20px;
                }
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
            `;
            document.head.appendChild(style);
            document.body.appendChild(overlay);
        }
    },
    
    hide() {
        const overlay = document.getElementById('loading-overlay');
        if (overlay) {
            overlay.remove();
        }
    }
};

// Auto-attach to forms and buttons
document.addEventListener('DOMContentLoaded', function() {
    // Forms
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            LoadingOverlay.show();
        });
    });
    
    // Buttons with data-loading attribute
    document.querySelectorAll('[data-loading]').forEach(btn => {
        btn.addEventListener('click', function() {
            LoadingOverlay.show();
        });
    });
    
    // Links with data-loading attribute
    document.querySelectorAll('a[data-loading]').forEach(link => {
        link.addEventListener('click', function() {
            LoadingOverlay.show();
        });
    });
});

// Global functions
window.showLoading = () => LoadingOverlay.show();
window.hideLoading = () => LoadingOverlay.hide();