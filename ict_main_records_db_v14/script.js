$(document).ready(function () {
   // Initialize DataTable
       let table = $('#employeeTable').DataTable({
        ajax: {
            url: 'crud.php?action=read',
            dataSrc: '' // Ensure the backend returns a JSON array at the root level
        },
        columns: [
            { data: 'id' },
            { data: 'emp_id' },
            { data: 'name' },
            { data: 'designation' },
            { data: 'place_of_posting' },
            { data: 'training_type' },
            { data: 'training_title' },
            { data: 'ref_no' },
            { data: 'start_date' },
            { data: 'end_date' },
            { data: 't_institute' },
            {
                data: 'order_attachment',
                render: function (data) {
                    return data
                        ? `<a href="uploads/${data}" target="_blank"><i class="btn btn-warning text-center fa fa-paperclip"></i></a>`
                        : 'No Attachment';
                }
            },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-sm btn-primary text-center edit-btn" data-id="${row.id}"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}"><i class="fa fa-trash"></i></button>
                    `;
                }
            }
        ],
        order: [[0, 'desc']] // Default sorting on the first column (ID) in descending order
    });
       
    // <button class="btn btn-sm btn-primary view-btn" data-id="${row.emp_id}"><i class="fa fa-eye"></i> </button>
// 
    // Handle "View" button click
    $('#employeeTable').on('click', '.view-btn', function () {
        let emp_id = $(this).data('id'); // Get the emp_id from the button
        window.location.href = 'training_details.php?emp_id=' + emp_id; // Redirect to the details page
        // window.location.href = 'view_training_details.php?emp_id=' + emp_id; // Redirect to the details page
    });

    $(document).on('click', '.edit-btn', function () {
    let id = $(this).data('id');

    $.ajax({
        url: 'crud.php?action=get&id=' + id,
        type: 'GET',
        success: function (response) {
            console.log(response); // Debug the response
            let employee;
            try {
                employee = JSON.parse(response);
            } catch (e) {
                alert('Invalid JSON response');
                return;
            }

            if (!employee.error) {
                    $('#id').val(employee.id);
                    $('#emp_id').val(employee.emp_id);
                    $('#name').val(employee.name);
                    $('#designation').val(employee.designation);
                    $('#place_of_posting').val(employee.place_of_posting);
                    $('#ref_no').val(employee.ref_no);
                    $('#training_type').val(employee.training_type);
                    $('#training_title').val(employee.training_title);
                    
                    $('#start_date').val(employee.start_date);
                    $('#end_date').val(employee.end_date);
                    $('#t_institute').val(employee.t_institute);

                $('#employeeModalLabel').text('Edit Employee');
                $('#employeeModal').modal('show');
            } else {
                alert('Error: ' + employee.error);
            }
        },
        error: function () {
            alert('Failed to fetch employee data');
        }
    });
});



    // // Edit Employee
    // $('#employeeTable').on('click', '.edit-btn', function () {
    //     let id = $(this).data('id');

    //     $.ajax({
    //         url: 'crud.php?action=get&id=' + id,
    //         type: 'GET',
    //         success: function (response) {
    //             let employee = JSON.parse(response);

    //             if (!employee.error) {
    //                 $('#id').val(employee.id);
    //                 $('#emp_id').val(employee.emp_id);
    //                 $('#name').val(employee.name);
    //                 $('#designation').val(employee.designation);
    //                 $('#place_of_posting').val(employee.place_of_posting);

    //                 $('#ref_id').val(employee.ref_id);


    //                 $('#employeeModalLabel').text('Edit Employee');
    //                 $('#employeeModal').modal('show');
    //             } else {
    //                 alert('Error: ' + employee.error);
    //             }
    //         }
    //     });
    // });

    // Edit button click event
    // $('#employeeTable').on('click', '.edit-btn', function () {
    //     let id = $(this).data('id');
    //     $.get('crud.php?action=get&id=' + id, function (data) {
    //         $('#employeeModalLabel').text('Edit Employee');
    //         $('#id').val(data.id);
    //         $('#emp_id').val(data.emp_id);
    //         $('#name').val(data.name);
    //         $('#designation').val(data.designation);
    //         $('#place_of_posting').val(data.place_of_posting);
    //         $('#training_title').val(data.training_title);
    //         $('#start_date').val(data.start_date);
    //         $('#end_date').val(data.end_date);
    //         $('#t_institute').val(data.t_institute);
    //         $('#employeeModal').modal('show');
    //     });
    // });

    // Delete button click event
    $('#employeeTable').on('click', '.delete-btn', function () {
        let id = $(this).data('id');
        if (confirm('Are you sure you want to delete this record?')) {
            $.post('crud.php?action=delete', { id: id }, function (response) {
                alert(response);
                table.ajax.reload();
            });
        }
    });

        // Open Add Modal
    $('[data-bs-target="#employeeModal"]').on('click', function () {
        $('#employeeForm')[0].reset();
        $('#id').val('');
        $('#employeeModalLabel').text('Add Employee');
    });



    // Form submission event
    $('#employeeForm').submit(function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: 'crud.php?action=save',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                alert(response);
                $('#employeeModal').modal('hide');
                table.ajax.reload();
            }
        });
    });
});
