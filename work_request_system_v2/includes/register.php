<?php
session_name('factory_work_request_db');
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .container {
            width: 100%;
            max-width: 600px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header p {
            opacity: 0.9;
        }
        
        .form-container {
            padding: 40px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }
        
        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5eb;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        input:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .error {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-submit:active {
            transform: translateY(0);
        }
        
        .login-link {
            text-align: center;
            margin-top: 25px;
            color: #666;
        }
        
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .message {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: none;
        }
        
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .form-container {
                padding: 30px 20px;
            }
            
            .header {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>User Registration</h1>
            <p>Create your account to get started</p>
        </div>
        
        <div id="message" class="message"></div>
        
        <div class="form-container">
            <form id="registrationForm" method="POST" action="register_process.php">
                <div class="form-row">
                    <div class="form-group">
                        <label for="emp_id">Employee ID *</label>
                        <input type="text" id="emp_id" name="emp_id" required 
                               placeholder="Enter your employee ID">
                        <div class="error" id="emp_id_error"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="full_name">Full Name *</label>
                        <input type="text" id="full_name" name="full_name" required 
                               placeholder="Enter your full name">
                        <div class="error" id="full_name_error"></div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="designation">Designation *</label>
                        <input type="text" id="designation" name="designation" required 
                               placeholder="Enter your designation">
                        <div class="error" id="designation_error"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="division">Division *</label>
                        <select id="division" name="division" required>
                            <option value="">Select Division</option>
                            <option value="IT">IT Department</option>
                            <option value="HR">Human Resources</option>
                            <option value="Finance">Finance</option>
                            <option value="Operations">Operations</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Sales">Sales</option>
                            <option value="R&D">Research & Development</option>
                        </select>
                        <div class="error" id="division_error"></div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="section">Section *</label>
                        <input type="text" id="section" name="section" required 
                               placeholder="Enter your section">
                        <div class="error" id="section_error"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" id="password" name="password" required 
                               placeholder="Create a password">
                        <div class="error" id="password_error"></div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" required 
                           placeholder="Confirm your password">
                    <div class="error" id="confirm_password_error"></div>
                </div>
                
                <button type="submit" class="btn-submit">Register</button>
            </form>
            
            <div class="login-link">
                Already have an account? <a href="../index.php">Login here</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            clearErrors();
            
            let isValid = true;
            
            // Get form values
            const empId = document.getElementById('emp_id').value.trim();
            const fullName = document.getElementById('full_name').value.trim();
            const designation = document.getElementById('designation').value.trim();
            const division = document.getElementById('division').value;
            const section = document.getElementById('section').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            // Validation rules
            if (!empId) {
                showError('emp_id_error', 'Employee ID is required');
                isValid = false;
            }
            
            if (!fullName) {
                showError('full_name_error', 'Full name is required');
                isValid = false;
            } else if (fullName.length < 2) {
                showError('full_name_error', 'Full name must be at least 2 characters');
                isValid = false;
            }
            
            if (!designation) {
                showError('designation_error', 'Designation is required');
                isValid = false;
            }
            
            if (!division) {
                showError('division_error', 'Please select a division');
                isValid = false;
            }
            
            if (!section) {
                showError('section_error', 'Section is required');
                isValid = false;
            }
            
            if (!password) {
                showError('password_error', 'Password is required');
                isValid = false;
            } else if (password.length < 8) {
                showError('password_error', 'Password must be at least 8 characters');
                isValid = false;
            } else if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(password)) {
                showError('password_error', 'Password must contain uppercase, lowercase, and numbers');
                isValid = false;
            }
            
            if (!confirmPassword) {
                showError('confirm_password_error', 'Please confirm your password');
                isValid = false;
            } else if (password !== confirmPassword) {
                showError('confirm_password_error', 'Passwords do not match');
                isValid = false;
            }
            
            if (isValid) {
                // Submit form via AJAX
                submitForm();
            }
        });
        
        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }
        
        function clearErrors() {
            const errors = document.querySelectorAll('.error');
            errors.forEach(error => {
                error.textContent = '';
                error.style.display = 'none';
            });
            
            // Clear any existing messages
            const messageDiv = document.getElementById('message');
            messageDiv.style.display = 'none';
            messageDiv.className = 'message';
        }
        
        function submitForm() {
            const form = document.getElementById('registrationForm');
            const formData = new FormData(form);
            
            // Show loading state
            const submitBtn = document.querySelector('.btn-submit');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Registering...';
            submitBtn.disabled = true;
            
            fetch('register_process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('Registration successful! Redirecting to login...', 'success');
                    
                    // Reset form
                    form.reset();
                    
                    // Redirect to login page after 2 seconds
                    setTimeout(() => {
                        window.location.href = '../index.php';
                    }, 2000);
                } else {
                    showMessage(data.message || 'Registration failed. Please try again.', 'error');
                }
            })
            .catch(error => {
                showMessage('An error occurred. Please try again.', 'error');
                console.error('Error:', error);
            })
            .finally(() => {
                // Reset button state
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        }
        
        function showMessage(message, type) {
            const messageDiv = document.getElementById('message');
            messageDiv.textContent = message;
            messageDiv.className = `message ${type}`;
            messageDiv.style.display = 'block';
            
            // Scroll to message
            messageDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        
        // Real-time password validation
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const errorElement = document.getElementById('password_error');
            
            if (password.length > 0 && password.length < 8) {
                showError('password_error', 'Password must be at least 8 characters');
            } else if (password.length >= 8 && !/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(password)) {
                showError('password_error', 'Password must contain uppercase, lowercase, and numbers');
            } else {
                errorElement.style.display = 'none';
            }
        });
        
        // Real-time confirm password validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            const errorElement = document.getElementById('confirm_password_error');
            
            if (confirmPassword && password !== confirmPassword) {
                showError('confirm_password_error', 'Passwords do not match');
            } else if (errorElement.style.display === 'block') {
                errorElement.style.display = 'none';
            }
        });
    </script>
</body>
</html>