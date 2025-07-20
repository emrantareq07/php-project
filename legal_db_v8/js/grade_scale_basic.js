$(document).ready(function() {
    $(document).on('change', '#court_type', function() {
        var court_typeID = $(this).val();
        if (court_typeID) {
            $.ajax({
                type: 'POST',
                url: 'grade_scale_basic.php',
                data: { 'court_typeid': court_typeID },
                success: function(result) {
                    $('#court_division').html(result);
                    $('#case_type').html('<option value="">আদালতের ধরণ</option>');
                }
            });
        } else {
            $('#court_division').html('<option value="">বিভাগ</option>');
            $('#case_type').html('<option value="">মামলার ধরণ</option>');
        }
    });

    $(document).on('change', '#court_division', function() {
        var court_divisionID = $(this).val();
        if (court_divisionID) {
            $.ajax({
                type: 'POST',
                url: 'grade_scale_basic.php',
                data: { 'court_divisionid': court_divisionID },
                success: function(result) {
                    $('#case_type').html(result);
                }
            });
        } else {
            $('#case_type').html('<option value="">মামলার ধরণ</option>');
        }
    });
});
