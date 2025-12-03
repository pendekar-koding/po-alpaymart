// Popup Alert System untuk Alpay Mart
class PopupAlert {
    constructor() {
        this.loadMobileCSS();
        this.createPopupContainer();
        this.overrideNativeAlerts();
    }

    loadMobileCSS() {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = '/css/mobile-popup.css';
        document.head.appendChild(link);
    }

    createPopupContainer() {
        if (document.getElementById('popup-container')) return;
        
        const container = document.createElement('div');
        container.id = 'popup-container';
        container.innerHTML = `
            <style>
                .popup-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.5);
                    z-index: 9999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    opacity: 0;
                    visibility: hidden;
                    transition: all 0.3s ease;
                }
                
                .popup-overlay.show {
                    opacity: 1;
                    visibility: visible;
                }
                
                .popup-modal {
                    background: white;
                    border-radius: 20px;
                    padding: 0;
                    max-width: 400px;
                    width: 90%;
                    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                    transform: scale(0.7) translateY(-50px);
                    transition: all 0.3s ease;
                    overflow: hidden;
                }
                
                .popup-overlay.show .popup-modal {
                    transform: scale(1) translateY(0);
                }
                
                .popup-header {
                    padding: 1.5rem 1.5rem 1rem;
                    text-align: center;
                    border-bottom: 1px solid #f0f0f0;
                }
                
                .popup-icon {
                    width: 60px;
                    height: 60px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 1rem;
                    font-size: 24px;
                    color: white;
                }
                
                .popup-icon.success {
                    background: linear-gradient(45deg, #28a745, #20c997);
                }
                
                .popup-icon.error {
                    background: linear-gradient(45deg, #dc3545, #fd7e14);
                }
                
                .popup-icon.warning {
                    background: linear-gradient(45deg, #ffc107, #fd7e14);
                }
                
                .popup-icon.info {
                    background: linear-gradient(45deg, #17a2b8, #007bff);
                }
                
                .popup-icon.confirm {
                    background: linear-gradient(45deg, #6f42c1, #e83e8c);
                }
                
                .popup-title {
                    font-size: 1.25rem;
                    font-weight: 600;
                    margin: 0;
                    color: #333;
                }
                
                .popup-body {
                    padding: 1rem 1.5rem;
                    text-align: center;
                }
                
                .popup-message {
                    color: #666;
                    line-height: 1.5;
                    margin: 0;
                }
                
                .popup-footer {
                    padding: 1rem 1.5rem 1.5rem;
                    display: flex;
                    gap: 0.75rem;
                    justify-content: center;
                }
                
                .popup-btn {
                    border: none;
                    border-radius: 25px;
                    padding: 0.75rem 1.5rem;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    min-width: 100px;
                }
                
                .popup-btn-primary {
                    background: linear-gradient(45deg, #007bff, #6610f2);
                    color: white;
                }
                
                .popup-btn-primary:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4);
                }
                
                .popup-btn-success {
                    background: linear-gradient(45deg, #28a745, #20c997);
                    color: white;
                }
                
                .popup-btn-success:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
                }
                
                .popup-btn-danger {
                    background: linear-gradient(45deg, #dc3545, #fd7e14);
                    color: white;
                }
                
                .popup-btn-danger:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
                }
                
                .popup-btn-secondary {
                    background: linear-gradient(45deg, #6c757d, #495057);
                    color: white;
                }
                
                .popup-btn-secondary:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
                }
                
                @media (max-width: 480px) {
                    .popup-modal {
                        width: 95%;
                        margin: 1rem;
                    }
                    
                    .popup-footer {
                        flex-direction: column;
                    }
                    
                    .popup-btn {
                        width: 100%;
                    }
                }
            </style>
        `;
        document.body.appendChild(container);
    }

    show(options) {
        const {
            type = 'info',
            title = 'Notifikasi',
            message = '',
            confirmText = 'OK',
            cancelText = 'Batal',
            showCancel = false,
            onConfirm = null,
            onCancel = null
        } = options;

        const icons = {
            success: 'fas fa-check',
            error: 'fas fa-times',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info',
            confirm: 'fas fa-question'
        };

        const overlay = document.createElement('div');
        overlay.className = 'popup-overlay';
        overlay.innerHTML = `
            <div class="popup-modal">
                <div class="popup-header">
                    <div class="popup-icon ${type}">
                        <i class="${icons[type]}"></i>
                    </div>
                    <h4 class="popup-title">${title}</h4>
                </div>
                <div class="popup-body">
                    <p class="popup-message">${message}</p>
                </div>
                <div class="popup-footer">
                    ${showCancel ? `<button class="popup-btn popup-btn-secondary" data-action="cancel">${cancelText}</button>` : ''}
                    <button class="popup-btn popup-btn-${type === 'error' ? 'danger' : type === 'confirm' ? 'primary' : 'success'}" data-action="confirm">${confirmText}</button>
                </div>
            </div>
        `;

        document.getElementById('popup-container').appendChild(overlay);

        // Event listeners
        overlay.querySelector('[data-action="confirm"]').addEventListener('click', () => {
            this.hide(overlay);
            if (onConfirm) onConfirm();
        });

        if (showCancel) {
            overlay.querySelector('[data-action="cancel"]').addEventListener('click', () => {
                this.hide(overlay);
                if (onCancel) onCancel();
            });
        }

        // Close on overlay click
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                this.hide(overlay);
                if (onCancel) onCancel();
            }
        });

        // Show animation
        setTimeout(() => overlay.classList.add('show'), 10);

        return new Promise((resolve) => {
            overlay.querySelector('[data-action="confirm"]').addEventListener('click', () => resolve(true));
            if (showCancel) {
                overlay.querySelector('[data-action="cancel"]').addEventListener('click', () => resolve(false));
            }
        });
    }

    hide(overlay) {
        overlay.classList.remove('show');
        setTimeout(() => {
            if (overlay.parentNode) {
                overlay.parentNode.removeChild(overlay);
            }
        }, 300);
    }

    // Method shortcuts
    success(message, title = 'Berhasil!') {
        return this.show({
            type: 'success',
            title,
            message,
            confirmText: 'OK'
        });
    }

    error(message, title = 'Error!') {
        return this.show({
            type: 'error',
            title,
            message,
            confirmText: 'OK'
        });
    }

    warning(message, title = 'Peringatan!') {
        return this.show({
            type: 'warning',
            title,
            message,
            confirmText: 'OK'
        });
    }

    info(message, title = 'Informasi') {
        return this.show({
            type: 'info',
            title,
            message,
            confirmText: 'OK'
        });
    }

    confirm(message, title = 'Konfirmasi') {
        return this.show({
            type: 'confirm',
            title,
            message,
            confirmText: 'Ya',
            cancelText: 'Tidak',
            showCancel: true
        });
    }

    // Override native alerts
    overrideNativeAlerts() {
        const self = this;
        
        // Override alert
        window.originalAlert = window.alert;
        window.alert = function(message) {
            self.info(message);
        };

        // Override confirm
        window.originalConfirm = window.confirm;
        window.confirm = function(message) {
            return self.confirm(message);
        };
    }
}

// Initialize popup system when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.popup = new PopupAlert();
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PopupAlert;
}