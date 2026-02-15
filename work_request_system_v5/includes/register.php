<?php
// incoming_work_request.php - SIMPLE VERSION
session_name('factory_work_request_db');

require_once '../db/config.php';
require_once 'header_reg.php';
?>

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
                        <label for="emp_type">EMP Type *</label>
                        <select id="emp_type" name="emp_type" required>
                            <option value="">--Select--</option>
                            <option value="Officer">Officer</option>
                            <option value="Staff">Staff</option>
                            <option value="Worker">Worker</option>
                            <option value="Technician">Technician</option>
                        </select>
                        <div class="error" id="designation_error"></div>
                    </div>
                     <div class="form-group">
                        <label for="emp_id">Employee ID *</label>
                        <input type="text" id="emp_id" name="emp_id" required 
                               placeholder="Enter your employee ID">
                        <div class="error" id="emp_id_error"></div>
                    </div>
                </div>
                <div class="form-row">  
                    <div class="form-group">
                        <label for="full_name">Full Name *</label>
                        <input type="text" id="full_name" name="full_name" required 
                               placeholder="Enter your full name">
                        <div class="error" id="full_name_error"></div>
                    </div>
                     <div class="form-group">
                        <label for="designation">Designation *</label>
                        <select id="designation" name="designation" required>
                            <option value="">Select Designation</option>
                            <?php 
                            $sql_desg = "SELECT designation FROM designation";
                            $result_desg = mysqli_query($conn, $sql_desg);
                            if ($result_desg && mysqli_num_rows($result_desg) > 0) {
                                while ($row_desg = mysqli_fetch_assoc($result_desg)) {
                                    $designation = htmlspecialchars($row_desg['designation']);
                                    echo "<option value=\"$designation\">$designation</option>";
                                }
                            } else {
                                echo "<option disabled>No Designation found</option>";
                            }
                            ?>
                        </select>
                        <div class="error" id="designation_error"></div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="division">Division *</label>
                        <select id="division" name="division" required>
                            <option value="">Select Division</option>
                            <?php 
                            $sql_com = "SELECT division FROM division";
                            $result_committe = mysqli_query($conn, $sql_com);
                            if ($result_committe && mysqli_num_rows($result_committe) > 0) {
                                while ($row_committe = mysqli_fetch_assoc($result_committe)) {
                                    $division = htmlspecialchars($row_committe['division']);
                                    echo "<option value=\"$division\">$division</option>";
                                }
                            } else {
                                echo "<option disabled>No division found</option>";
                            }
                            ?>
                        </select>
                        <div class="error" id="division_error"></div>
                    </div>
                     <div class="form-group">
                        <label for="section">Section *</label>
                        <select id="section" name="section" required>
                            <option value="">Select Section</option>
                            <?php 
                            $sql_section = "SELECT name FROM section";
                            $result_section = mysqli_query($conn, $sql_section);
                            if ($result_section && mysqli_num_rows($result_section) > 0) {
                                while ($row_section = mysqli_fetch_assoc($result_section)) {
                                    $section = htmlspecialchars($row_section['name']);
                                    echo "<option value=\"$section\">$section</option>";
                                }
                            } else {
                                echo "<option disabled>No section found</option>";
                            }
                            ?>
                        </select>
                        <div class="error" id="section_error"></div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" id="password" name="password" required 
                               placeholder="Create a password">
                        <div class="error" id="password_error"></div>
                    </div>
                    <div class="form-group">
                    <label for="confirm_password">Confirm Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" required 
                           placeholder="Confirm your password">
                    <div class="error" id="confirm_password_error"></div>
                </div>                
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
            const emp_type = document.getElementById('emp_type').value.trim();
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