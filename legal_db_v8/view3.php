<?php
session_start();

require_once("db/db.php");
include('function.php');

if (isset($_POST["id"])) {
    $id = $_POST["id"];

$kk=0;
    $sql = "SELECT * FROM legal_tbl WHERE id='$id'";
    $query_run = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query_run) > 0) {

        foreach ($query_run as $row) {
           if($row['hearing_result']=="বিচারাধীন")
           {
            $row['hearing_result']="পরবর্তী ধার্য তারিখ";
            $kk=$row['hearing_result'];
           }

            echo '
                <div class="row">
                    <div class="col">
                        <label for="court_type">আদালতের ধরণ </label>
                        <input readonly class="form-control " style="font-weight: bold;"type="text" value="' . $row['court_type'] . '">
                    </div>
                    <div class="col">
                        <label for="court_division">বিভাগ </label>
                        <input readonly class="form-control" type="text" style="font-weight: bold;" value="' . $row['court_division'] . '">
                    </div>
                    <div class="col">
                        <label for="case_type">মামলার ধরণ </label>
                        <input readonly class="form-control" type="text" style="font-weight: bold;" value="' . $row['case_type'] . '">
                    </div>
                </div>

                <div class="row my-2">
                    <div class="col">
                        <div class="form-group">
                            <label for="case_no">মামলার নং</label>
                            <input readonly class="form-control" type="text" style="font-weight: bold;" value="' . $row['case_no'] . '">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="case_date">মামলার সাল/তারিখ</label>
                            <input readonly class="form-control" type="text" style="font-weight: bold;" value="' . $row['case_date'] . '">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="case_duration">মামলা সমাপ্তির তারিখ</label>
                            <input readonly class="form-control" type="date" style="font-weight: bold;" value="' . $row['case_duration'] . '">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="reason_of_case">মামলার বিষয়বস্তু</label>
                    <textarea readonly class="form-control" style="font-weight: bold;" rows="2">' . $row['reason_of_case'] . '</textarea>
                </div>

                <div class="row my-2">
                    <div class="col">
                        <div class="form-group">
                            <label for="plaintiff_name">মামলার বাদী</label>
                            <input readonly class="form-control" style="font-weight: bold;" type="text" value="' . $row['plaintiff_name'] . '">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="defendant_name">বিবাদীর নাম</label>
                            <input readonly class="form-control" style="font-weight: bold;" type="text" value="' . $row['defendant_name'] . '">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="lower_name_address">আইনজীবীর নাম ও ঠিকানা</label>
                    <textarea readonly class="form-control" style="font-weight: bold;" rows="2">' . $row['lower_name_address'] . '</textarea>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="case_filing_date">মামলা দায়েরের তারিখ</label>
                            <input readonly class="form-control" style="font-weight: bold;" type="date" value="' . $row['case_filing_date'] . '">
                        </div>
                    </div>
                    <div class="col">
                <div class="form-group">
                    <label for="hearing_result">শুনানীর ফলাফল</label>
                    <input readonly class="form-control" type="text" style="font-weight: bold;" value="' . $row['hearing_result'] . '">
                    ';

                if ($kk == "পরবর্তী ধার্য তারিখ") {

                    echo '
                    <input readonly class="form-control" type="date" style="font-weight: bold;" value="' . $row['next_hearing_result_date'] . '">
                    ';
                }

                echo '
                </div>

                    </div>
                </div>

                <div class="form-group">
                    <label for="case_filing_accept_steps">মামলার রায়ের প্রেক্ষিতে গৃহীত ব্যবস্থা</label>
                    <textarea readonly class="form-control" style="font-weight: bold;" rows="2">' . $row['case_filing_accept_steps'] . '</textarea>
                </div>

                <div class="form-group">
                    <label for="panel_lower_info">প্যানেল আইনজীবী তথ্য</label>
                    <textarea readonly class="form-control" style="font-weight: bold;" rows="2">' . $row['panel_lower_info'] . '</textarea>
                </div>

                <div class="form-group">
                    <label for="panel_low_adv_info">আইন উপদেষ্টা আইনজীবীর তথ্য</label>
                    <textarea readonly class="form-control" style="font-weight: bold;" rows="2">' . $row['panel_low_adv_info'] . '</textarea>
                </div>

                <div class="form-group">
                    <label for="remarks">মন্তব্য</label>
                    <textarea readonly class="form-control" style="font-weight: bold;" rows="2">' . $row['remarks'] . '</textarea>
                </div>
            ';
        }
    } else {
        // Handle case when no rows are found
    }
}
?>



