
    document.addEventListener('DOMContentLoaded', function () {
        // Enhanced form validation with better UX
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    // Add shake animation to invalid form
                    form.style.animation = 'shake 0.5s';
                    setTimeout(() => {
                        form.style.animation = '';
                    }, 500);
                }
                form.classList.add('was-validated');
            }, false);
        });
        
        // Enhanced file input handling
        const fileInput = document.getElementById('inputGroupFile');
        const fileLabel = document.querySelector('.file-input-label');
        
        fileInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                fileLabel.innerHTML = `
                    <div>
                        <i class="ri-file-excel-2-line file-input-icon" style="color: #10b981;"></i>
                        <div class="file-input-text" style="color: #10b981;">${fileName}</div>
                        <div class="file-input-subtext">File selected successfully</div>
                    </div>
                `;
                fileLabel.style.background = 'linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%)';
                fileLabel.style.borderColor = '#10b981';
            }
        });
        
        // Drag and drop functionality
        fileLabel.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.background = 'linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%)';
            this.style.borderColor = '#3b82f6';
            this.style.transform = 'scale(1.02)';
        });
        
        fileLabel.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.style.background = 'linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%)';
            this.style.borderColor = '#cbd5e1';
            this.style.transform = 'scale(1)';
        });
        
        fileLabel.addEventListener('drop', function(e) {
            e.preventDefault();
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                const event = new Event('change', { bubbles: true });
                fileInput.dispatchEvent(event);
            }
            this.style.background = 'linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%)';
            this.style.borderColor = '#cbd5e1';
            this.style.transform = 'scale(1)';
        });
        
        // Auto close alerts with fade out effect
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'all 0.5s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 500);
            });
        }, 5000);
        
        // Add loading state to submit button
        const submitBtn = document.querySelector('button[type="submit"]');
        const form = document.querySelector('form');
        
        form.addEventListener('submit', function(e) {
            if (form.checkValidity()) {
                submitBtn.innerHTML = `
                    <i class="ri-loader-4-line align-middle me-2" style="animation: spin 1s linear infinite;"></i>
                    Processing...
                `;
                submitBtn.disabled = true;
            }
        });
    });

    // Add CSS keyframes for animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);

