
    function previewPassport(input) {
        const file = input.files[0];
        const preview = document.getElementById('passport-preview');
        const placeholder = document.getElementById('upload-placeholder');
        const container = document.querySelector('.passport-container');

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
                container.style.borderStyle = 'solid';
                container.style.borderColor = '#4e73df';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
            placeholder.style.display = 'flex';
            container.style.borderStyle = 'dashed';
            container.style.borderColor = '#c9cfd6';
        }
    }

    // Make the entire passport container clickable
    document.querySelector('.passport-container').addEventListener('click', function() {
        document.getElementById('passport').click();
    });
    
    // Form progress tracking
    document.addEventListener('DOMContentLoaded', function() {
        const requiredFields = document.querySelectorAll('[required]');
        const allFields = document.querySelectorAll('input, select');
        
        function updateProgress() {
            // Basic info section
            const basicInfoFields = document.querySelectorAll('#employee_number, #surname, #first_name, #middle_name, #email, #phone, #dob, #gender, #marital_status');
            const basicInfoRequired = document.querySelectorAll('#employee_number, #surname, #first_name');
            
            let basicInfoFilled = 0;
            basicInfoFields.forEach(field => {
                if (field.value) basicInfoFilled++;
            });
            
            let basicInfoRequiredFilled = 0;
            basicInfoRequired.forEach(field => {
                if (field.value) basicInfoRequiredFilled++;
            });
            
            const basicInfoPercent = basicInfoFields.length > 0 ? Math.round((basicInfoFilled / basicInfoFields.length) * 100) : 0;
            document.getElementById('basic-info-progress').style.width = basicInfoPercent + '%';
            document.getElementById('basic-info-badge').textContent = basicInfoPercent + '%';
            
            // Employment details section
            const employmentFields = document.querySelectorAll('#mda_id, #paygroup_id, #level_id, #step_id, #first_appointment_date, #confirmation_date, #present_appointment_date, #retirement_date, #rank');
            const employmentRequired = document.querySelectorAll('#mda_id, #paygroup_id, #level_id, #step_id');
            
            let employmentFilled = 0;
            employmentFields.forEach(field => {
                if (field.value) employmentFilled++;
            });
            
            let employmentRequiredFilled = 0;
            employmentRequired.forEach(field => {
                if (field.value) employmentRequiredFilled++;
            });
            
            const employmentPercent = employmentFields.length > 0 ? Math.round((employmentFilled / employmentFields.length) * 100) : 0;
            document.getElementById('employment-progress').style.width = employmentPercent + '%';
            document.getElementById('employment-badge').textContent = employmentPercent + '%';
            
            // Additional info section
            const additionalFields = document.querySelectorAll('#religion, #lga, #qualifications, #net_pay, #password, #password_confirmation');
            
            let additionalFilled = 0;
            additionalFields.forEach(field => {
                if (field.value) additionalFilled++;
            });
            
            const additionalPercent = additionalFields.length > 0 ? Math.round((additionalFilled / additionalFields.length) * 100) : 0;
            document.getElementById('additional-progress').style.width = additionalPercent + '%';
            document.getElementById('additional-badge').textContent = additionalPercent + '%';
            
            // Overall progress
            let totalFields = allFields.length;
            let filledFields = 0;
            
            allFields.forEach(field => {
                if (field.value) filledFields++;
            });
            
            const overallPercent = totalFields > 0 ? Math.round((filledFields / totalFields) * 100) : 0;
            document.getElementById('overall-progress').style.width = overallPercent + '%';
            document.getElementById('overall-badge').textContent = overallPercent + '%';
            
            // Check if all required fields are filled
            let allRequiredFilled = true;
            requiredFields.forEach(field => {
                if (!field.value) allRequiredFilled = false;
            });
            
            const submitBtn = document.querySelector('button[type="submit"]');
            if (allRequiredFilled) {
                submitBtn.classList.remove('btn-primary');
                submitBtn.classList.add('btn-success');
            } else {
                submitBtn.classList.remove('btn-success');
                submitBtn.classList.add('btn-primary');
            }
        }
        
        // Add event listeners to all form fields
        allFields.forEach(field => {
            field.addEventListener('input', updateProgress);
            field.addEventListener('change', updateProgress);
        });
        
        // Initialize progress
        updateProgress();
    });

    // Add smooth transitions
    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('mouseover', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseout', function() {
            this.style.transform = 'translateY(0)';
        });
    });


