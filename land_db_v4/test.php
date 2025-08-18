<?php
require_once("config/config.php");
require_once("db/db.php");
include_once("includes/header.php");

function convertBengaliToArabic($number) {
    $bengali_digits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    $arabic_digits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    return str_replace($bengali_digits, $arabic_digits, $number);
}

function convertArabicToBengali($number) {
    $arabic_digits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    $bengali_digits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    return str_replace($arabic_digits, $bengali_digits, $number);
}

?>

<div class="container">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>প্রতিষ্ঠান </th>
                <th>মোট জমির পরিমান(একর)</th>
                <th>ব্যক্তি মালিকানা(একর)</th>
                <th>খাস জমি(একর)</th>
                <th>লীজকৃত জমি(একর)</th>
                <th>মালিকাধীন(একর)</th>
                <th>ক্রয়কৃত জমি(একর)</th>
                <th>অধিগ্রহনকৃত জমি(একর)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT org_name, land_type, land_size FROM land_tbl";
            $result = mysqli_query($conn, $query);

            $land_data = [];

            while ($row = mysqli_fetch_assoc($result)) {
                $org_name = $row['org_name'];
                $land_type = $row['land_type'];
                $land_size = floatval(convertBengaliToArabic($row['land_size']));

                if (!isset($land_data[$org_name])) {
                    $land_data[$org_name] = [
                        'ব্যক্তি মালিকানা' => 0,
                        'খাস জমি' => 0,
                        'লীজকৃত জমি' => 0,
                        'মালিকাধীন' => 0,
                        'ক্রয়কৃত জমি' => 0,
                        'অধিগ্রহনকৃত জমি' => 0,
                    ];
                }

                $land_data[$org_name][$land_type] += $land_size;
            }

            foreach ($land_data as $org_name => $land_sizes) {
                $total_land = array_sum($land_sizes);
                echo "<tr>
                    <td>{$org_name}</td>
                    <td>" . convertArabicToBengali((string)$total_land) . "</td>
                    <td>" . convertArabicToBengali((string)$land_sizes['ব্যক্তি মালিকানা']) . "</td>
                    <td>" . convertArabicToBengali((string)$land_sizes['খাস জমি']) . "</td>
                    <td>" . convertArabicToBengali((string)$land_sizes['লীজকৃত জমি']) . "</td>
                    <td>" . convertArabicToBengali((string)$land_sizes['মালিকাধীন']) . "</td>
                    <td>" . convertArabicToBengali((string)$land_sizes['ক্রয়কৃত জমি']) . "</td>
                    <td>" . convertArabicToBengali((string)$land_sizes['অধিগ্রহনকৃত জমি']) . "</td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
