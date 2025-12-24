<script>
$(document).ready(function() {
    let rowCount = $('#worker_body .worker-row').length;
    let isEditing = <?php echo $is_editing ? 'true' : 'false'; ?>;
    let editingRecordId = <?php echo $editing_record_id ? $editing_record_id : 'null'; ?>;
    let monthlyEntryExists = <?php echo $monthly_entry_exists ? 'true' : 'false'; ?>;

    // Initialize row count based on existing rows
    updateRowCount();

    // Add new row
    $('#add_worker').on('click', function() {
        if (monthlyEntryExists && !isEditing) {
            alert('Cannot add new entry. An entry already exists for this month.');
            return;
        }

        const newRow = `
            <tr class="worker-row">
                <td>
                    <input type="text" class="form-control designation" name="designation[]" placeholder="Enter designation" required>
                </td>
                <td>
                    <select class="form-select grade-select" name="grade[]" required>
                        <option value="">Select Grade</option>
                        <?php for($i = 1; $i <= 16; $i++): ?>
                        <option value="Grade <?php echo $i; ?>">Grade <?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                    <div class="duplicate-error">This grade has already been selected</div>
                </td>
                <td>
                    <input type="number" class="form-control sanctioned-post" name="sanctioned_post[]" min="0" value="0" required>
                </td>
                <td>
                    <input type="number" class="form-control male" name="male[]" min="0" value="0" required>
                </td>
                <td>
                    <input type="number" class="form-control female" name="female[]" min="0" value="0" required>
                </td>
                <td>
                    <input type="number" class="form-control total total-cell" name="total[]" min="0" value="0" readonly>
                </td>
                <td>
                    <input type="number" class="form-control vacant-post" name="vacant_post[]" min="0" value="0" readonly>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-worker">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#worker_body').append(newRow);
        updateRowCount();
        checkDuplicateGrades();
    });

    // Remove row
    $(document).on('click', '.remove-worker', function() {
        if (rowCount > 1) {
            $(this).closest('tr').remove();
            updateRowCount();
            checkDuplicateGrades();
        }
    });

    // Update row count and button states
    function updateRowCount() {
        rowCount = $('#worker_body .worker-row').length;
        
        // Enable/disable remove buttons
        if (rowCount > 1) {
            $('.remove-worker').prop('disabled', false);
        } else {
            $('.remove-worker:first').prop('disabled', true);
        }
    }

    // Calculate totals and vacant posts
    $(document).on('input', '.male, .female, .sanctioned-post', function() {
        const row = $(this).closest('tr');
        calculateRowTotals(row);
    });

    function calculateRowTotals(row) {
        const male = parseFloat(row.find('.male').val()) || 0;
        const female = parseFloat(row.find('.female').val()) || 0;
        const sanctioned = parseFloat(row.find('.sanctioned-post').val()) || 0;
        
        const total = male + female;
        const vacant = sanctioned - total;
        
        row.find('.total').val(total);
        row.find('.vacant-post').val(vacant);
        
        if (vacant < 0) {
            row.find('.vacant-post').addClass('text-danger');
        } else {
            row.find('.vacant-post').removeClass('text-danger');
        }
    }

    // Check for duplicate grades
    // $(document).on('change', '.grade-select', checkDuplicateGrades);

    // function checkDuplicateGrades() {
    //     const selectedGrades = [];
    //     let hasDuplicates = false;
        
    //     $('.grade-select').each(function() {
    //         const grade = $(this).val();
    //         if (grade) {
    //             if (selectedGrades.includes(grade)) {
    //                 $(this).addClass('is-invalid');
    //                 $(this).siblings('.duplicate-error').show();
    //                 hasDuplicates = true;
    //             } else {
    //                 selectedGrades.push(grade);
    //                 $(this).removeClass('is-invalid');
    //                 $(this).siblings('.duplicate-error').hide();
    //             }
    //         } else {
    //             $(this).removeClass('is-invalid');
    //             $(this).siblings('.duplicate-error').hide();
    //         }
    //     });
        
    //     return hasDuplicates;
    // }

    // Save New Record
    $('#workerForm').on('submit', function(e) {
        e.preventDefault();
        
        // Check monthly restriction
        if (monthlyEntryExists && !isEditing) {
            alert('An entry already exists for this month. Please edit the existing entry.');
            return;
        }
        
        if (!validateForm()) return;
        
        if (checkDuplicateGrades()) {
            alert('Please fix duplicate grades before saving.');
            return;
        }

        const submitBtn = $('#save_btn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

        // Debug: Check what data is being sent
        console.log('Form Data:', $(this).serialize());

        $.ajax({
            url: 'save_worker.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('✅ ' + response.message);
                    window.location.href = 'workers_info_1.php';
                } else {
                    alert('❌ Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Save Error:', error);
                console.error('Response:', xhr.responseText);
                alert('❌ Error saving data. Check console for details.');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Update Record
    $('#update_btn').on('click', function() {
        if (!validateForm()) return;
        
        if (checkDuplicateGrades()) {
            alert('Please fix duplicate grades before saving.');
            return;
        }

        const updateBtn = $(this);
        const originalText = updateBtn.html();
        updateBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');

        // Debug: Check what data is being sent
        console.log('Update Form Data:', $('#workerForm').serialize());

        $.ajax({
            url: 'update_worker.php',
            type: 'POST',
            data: $('#workerForm').serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('✅ ' + response.message);
                    window.location.href = 'workers_info_1.php';
                } else {
                    alert('❌ Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Update Error:', error);
                console.error('Response:', xhr.responseText);
                
                // Handle non-JSON responses gracefully
                if (xhr.responseText.includes('<!DOCTYPE') || xhr.responseText.includes('<br />')) {
                    alert('❌ Server error occurred. Please check the console for details.');
                } else {
                    alert('❌ Error: ' + error);
                }
            },
            complete: function() {
                updateBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Cancel Edit
    $('#cancel_edit').on('click', function() {
        if (confirm('Are you sure you want to cancel editing?')) {
            window.location.href = 'workers_info_1.php';
        }
    });

    // Date change handler
    $('#date').on('change', function() {
        if (!isEditing) {
            checkMonthlyRestriction();
        }
    });

    // Check monthly restriction via AJAX
    function checkMonthlyRestriction() {
        const date = $('#date').val();
        const factoryName = '<?php echo $_SESSION['username']; ?>';
        
        if (!date) return;
        
        $.ajax({
            url: 'check_monthly_entry.php',
            type: 'POST',
            data: { 
                date: date,
                factory_name: factoryName
            },
            dataType: 'json',
            success: function(response) {
                monthlyEntryExists = response.exists;
                
                if (monthlyEntryExists) {
                    $('#save_btn').prop('disabled', true).addClass('btn-disabled-override');
                    $('#add_worker').prop('disabled', true);
                    $('.monthly-warning').remove();
                    
                    const warningMsg = `An entry already exists for ${response.month_year}. Please edit the existing entry.`;
                    $('.worker-section').prepend(`
                        <div class="monthly-warning alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> ${warningMsg}
                        </div>
                    `);
                } else {
                    $('#save_btn').prop('disabled', false).removeClass('btn-disabled-override');
                    $('#add_worker').prop('disabled', false);
                    $('.monthly-warning').remove();
                }
            },
            error: function(xhr, status, error) {
                console.error('Monthly check error:', error);
            }
        });
    }

    function validateForm() {
        const date = $('#date').val();
        if (!date) {
            alert('Please select a date.');
            $('#date').addClass('is-invalid');
            return false;
        } else {
            $('#date').removeClass('is-invalid');
        }

        let isValid = true;
        $('input[required], select[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            alert('Please fill all required fields.');
            return false;
        }
        
        return true;
    }

    // Update edit button behavior
    $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        window.location.href = 'workers_info_1.php?edit_id=' + id;
    });

    // Add the missing CRUD functions
    $(document).on('click', '.view-btn', function() {
        const id = $(this).data('id');
        viewRecord(id);
    });

    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        const date = $(this).closest('tr').find('td:first').text().trim();
        
        $('#deleteDate').text(date);
        $('#deleteModal').data('id', id).modal('show');
    });

    // Confirm delete
    $('#confirmDelete').on('click', function() {
        const id = $('#deleteModal').data('id');
        deleteRecord(id);
    });
});

// Add the missing functions outside document ready
function showDetails(type, data) {
    const items = data.split(',');
    let title = '';
    let content = '';
    
    switch(type) {
        case 'designation':
            title = 'Designations';
            content = items.map((item, index) => 
                `<div class="worker-details">
                    <strong>${index + 1}.</strong> ${item.trim()}
                </div>`
            ).join('');
            break;
            
        case 'grade':
            title = 'Grades';
            content = items.map((item, index) => 
                `<div class="worker-details">
                    <strong>${index + 1}.</strong> ${item.trim()}
                </div>`
            ).join('');
            break;
            
        case 'sanctioned_post':
            title = 'Sanctioned Posts';
            content = items.map((item, index) => 
                `<div class="worker-details">
                    <strong>${index + 1}.</strong> ${parseInt(item).toLocaleString()}
                </div>`
            ).join('');
            break;
            
        case 'male':
            title = 'Male Workers';
            content = items.map((item, index) => 
                `<div class="worker-details">
                    <strong>${index + 1}.</strong> ${parseInt(item).toLocaleString()}
                </div>`
            ).join('');
            break;
            
        case 'female':
            title = 'Female Workers';
            content = items.map((item, index) => 
                `<div class="worker-details">
                    <strong>${index + 1}.</strong> ${parseInt(item).toLocaleString()}
                </div>`
            ).join('');
            break;
            
        case 'total':
            title = 'Total Workers';
            const total = items.reduce((sum, item) => sum + parseInt(item), 0);
            content = items.map((item, index) => 
                `<div class="worker-details">
                    <strong>${index + 1}.</strong> ${parseInt(item).toLocaleString()}
                </div>`
            ).join('') + 
            `<div class="worker-details bg-primary text-white mt-2">
                <strong>Grand Total:</strong> ${total.toLocaleString()}
            </div>`;
            break;
    }
    
    $('#detailsModalTitle').text(title);
    $('#detailsModalBody').html(content);
    $('#detailsModal').modal('show');
}

function viewRecord(id) {
    $.ajax({
        url: 'get_worker_record.php',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                let content = `
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Date:</strong> ${data.date}
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong> 
                            <span class="badge ${data.status === 'active' ? 'badge-success' : 'badge-secondary'}">
                                ${data.status}
                            </span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Designation</th>
                                    <th>Grade</th>
                                    <th>Sanctioned Post</th>
                                    <th>Male</th>
                                    <th>Female</th>
                                    <th>Total</th>
                                    <th>Vacant</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                const designations = data.designation.split(',');
                const grades = data.grade.split(',');
                const sanctionedPosts = data.sanctioned_post.split(',');
                const males = data.male.split(',');
                const females = data.female.split(',');
                const totals = data.total.split(',');
                
                let grandTotalMale = 0;
                let grandTotalFemale = 0;
                let grandTotal = 0;
                let grandSanctioned = 0;
                
                for (let i = 0; i < designations.length; i++) {
                    if (designations[i].trim()) {
                        const male = parseInt(males[i]) || 0;
                        const female = parseInt(females[i]) || 0;
                        const total = parseInt(totals[i]) || 0;
                        const sanctioned = parseInt(sanctionedPosts[i]) || 0;
                        const vacant = sanctioned - total;
                        
                        grandTotalMale += male;
                        grandTotalFemale += female;
                        grandTotal += total;
                        grandSanctioned += sanctioned;
                        
                        content += `
                            <tr>
                                <td>${i + 1}</td>
                                <td>${designations[i].trim()}</td>
                                <td>${grades[i].trim()}</td>
                                <td class="text-end">${sanctioned.toLocaleString()}</td>
                                <td class="text-end">${male.toLocaleString()}</td>
                                <td class="text-end">${female.toLocaleString()}</td>
                                <td class="text-end"><strong>${total.toLocaleString()}</strong></td>
                                <td class="text-end ${vacant < 0 ? 'text-danger' : ''}">
                                    <strong>${vacant.toLocaleString()}</strong>
                                </td>
                            </tr>
                        `;
                    }
                }
                
                const grandVacant = grandSanctioned - grandTotal;
                
                content += `
                            <tr class="table-primary">
                                <td colspan="3"><strong>Grand Total</strong></td>
                                <td class="text-end"><strong>${grandSanctioned.toLocaleString()}</strong></td>
                                <td class="text-end"><strong>${grandTotalMale.toLocaleString()}</strong></td>
                                <td class="text-end"><strong>${grandTotalFemale.toLocaleString()}</strong></td>
                                <td class="text-end"><strong>${grandTotal.toLocaleString()}</strong></td>
                                <td class="text-end ${grandVacant < 0 ? 'text-danger' : ''}">
                                    <strong>${grandVacant.toLocaleString()}</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                `;
                
                $('#detailsModalTitle').text('Complete Worker Details - ' + data.date);
                $('#detailsModalBody').html(content);
                $('#detailsModal').modal('show');
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('Error loading record details.');
        }
    });
}

function deleteRecord(id) {
    $.ajax({
        url: 'delete_worker.php',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert('✅ ' + response.message);
                location.reload();
            } else {
                alert('❌ Error: ' + response.message);
            }
        },
        error: function() {
            alert('Error deleting record.');
        },
        complete: function() {
            $('#deleteModal').modal('hide');
        }
    });
}
</script>