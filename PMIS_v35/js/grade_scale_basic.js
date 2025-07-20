$(document).ready(function() {
    $(document).on('change', '#grade', function() {
        var gradeID = $(this).val();
        if (gradeID) {
            $.ajax({
                type: 'POST',
                url: 'grade_scale_basic.php',
                data: { 'grade_id': gradeID },
                success: function(result) {
                    $('#scale').html(result);
                    $('#basic').html('<option value="">Basic</option>');
                }
            });
        } else {
            $('#scale').html('<option value="">Scale</option>');
            $('#basic').html('<option value="">Basic</option>');
        }
    });

    $(document).on('change', '#scale', function() {
        var scaleID = $(this).val();
        if (scaleID) {
            $.ajax({
                type: 'POST',
                url: 'grade_scale_basic.php',
                data: { 'scale_id': scaleID },
                success: function(result) {
                    $('#basic').html(result);
                }
            });
        } else {
            $('#basic').html('<option value="">Basic</option>');
        }
    });
});
