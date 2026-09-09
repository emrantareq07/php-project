```php
<?php
// =========================================================================
// NPS 2026 BASIC PAY FIXATION CALCULATOR
// =========================================================================


// =========================================================================
// 1. DATA STRUCTURES
// =========================================================================

// -------------------------------------------------------------------------
// NPS 2015 PAY SCALE
// -------------------------------------------------------------------------

$nps_2015_slots = [

    'Grade_1'  => [78000],

    'Grade_2'  => [
        66000, 68480, 71050, 73720, 76490
    ],

    'Grade_3'  => [
        56500, 58760, 61120, 63570,
        66120, 68770, 71530, 74400
    ],

    'Grade_4'  => [
        50000, 52000, 54080, 56250,
        58500, 60840, 63280, 65820,
        68460, 71200
    ],

    'Grade_5'  => [
        43000, 44940, 46970, 49090,
        51300, 53610, 56030, 58560,
        61200, 63960, 66840, 69850
    ],

    'Grade_6'  => [
        35500, 37280, 39150, 41110,
        43170, 45330, 47600, 49980,
        52480, 55110, 57870, 60770,
        63810, 67010
    ],

    'Grade_7'  => [
        29000, 30450, 31980, 33580,
        35260, 37030, 38890, 40840,
        42890, 45040, 47300, 49670,
        52160, 54770, 57510, 60390,
        63410
    ],

    'Grade_8' => [
        23000, 24150, 25360, 26630,
        27970, 29370, 30840, 32390,
        34010, 35720, 37510, 39390,
        41360, 43430, 45610, 47900,
        50300, 52820, 55470
    ],

    'Grade_9' => [
        22000, 23100, 24260, 25480,
        26760, 28100, 29510, 30990,
        32540, 34170, 35880, 37680,
        39570, 41550, 43630, 45820,
        48120, 50530, 53060
    ],

    'Grade_10' => [
        16000, 16800, 17640, 18530,
        19460, 20440, 21470, 22550,
        23680, 24870, 26120, 27430,
        28810, 30260, 31780, 33370,
        35040, 36800, 38640
    ],

    'Grade_11' => [
        12500, 13130, 13790, 14480,
        15210, 15980, 16780, 17620,
        18510, 19440, 20420, 21450,
        22530, 23660, 24850, 26100,
        27410, 28790, 30230
    ],

    'Grade_12' => [
        11300, 11870, 12470, 13100,
        13760, 14450, 15180, 15940,
        16740, 17580, 18460, 19390,
        20360, 21380, 22450, 23580,
        24760, 26000, 27300
    ],

    'Grade_13' => [
        11000, 11550, 12130, 12740,
        13380, 14050, 14760, 15500,
        16280, 17100, 17960, 18860,
        19810, 20810, 21860, 22960,
        24110, 25320, 26590
    ],

    'Grade_14' => [
        10200, 10710, 11250, 11820,
        12420, 13050, 13710, 14400,
        15120, 15880, 16680, 17520,
        18400, 19320, 20290, 21310,
        22380, 23500, 24680
    ],

    'Grade_15' => [
        9700, 10190, 10700, 11240,
        11810, 12410, 13040, 13700,
        14390, 15110, 15870, 16670,
        17510, 18390, 19310, 20280,
        21300, 22370, 23490
    ],

    'Grade_16' => [
        9300, 9770, 10260, 10780,
        11320, 11890, 12490, 13120,
        13780, 14470, 15200, 15960,
        16760, 17600, 18480, 19410,
        20390, 21410, 22490
    ],

    'Grade_17' => [
        9000, 9450, 9930, 10430,
        10960, 11510, 12090, 12700,
        13340, 14010, 14720, 15460,
        16240, 17060, 17920, 18820,
        19770, 20760, 21800
    ],

    'Grade_18' => [
        8800, 9240, 9710, 10200,
        10710, 11250, 11820, 12420,
        13050, 13710, 14400, 15120,
        15880, 16680, 17520, 18400,
        19320, 20290, 21310
    ],

    'Grade_19' => [
        8500, 8930, 9380, 9850,
        10350, 10870, 11420, 12000,
        12600, 13230, 13900, 14600,
        15330, 16100, 16910, 17760,
        18650, 19590, 20570
    ],

    'Grade_20' => [
        8250, 8670, 9110, 9570,
        10050, 10560, 11090, 11650,
        12240, 12860, 13510, 14190,
        14900, 15650, 16440, 17270,
        18140, 19050, 20010
    ]
];


// -------------------------------------------------------------------------
// NPS 2026 PAY SCALE
// -------------------------------------------------------------------------

$nps_2026_slots = [

    'Grade_1'  => [156000],

    'Grade_2'  => [
        132000, 136960, 142100, 147440, 153000
    ],

    'Grade_3'  => [
        113000, 117520, 122240, 127140,
        132240, 137540, 143060, 148800
    ],

    'Grade_4'  => [
        100000, 104000, 108160, 112500,
        117000, 121680, 126560, 131640,
        136920, 142400
    ],

    'Grade_5'  => [
        86000, 89880, 93940, 98180,
        102600, 107220, 112060, 117120,
        122400, 127920, 133680, 139700
    ],

    'Grade_6'  => [
        71000, 74550, 78280, 82200,
        86310, 90630, 95170, 99930,
        104930, 110180, 115690, 121480,
        127560, 134000
    ],

    'Grade_7'  => [
        58000, 60900, 63960, 67160,
        70520, 74060, 77780, 81680,
        85780, 90080, 94600, 99340,
        104320, 109540, 115020, 120780,
        126800
    ],

    'Grade_8' => [
        46000, 48300, 50720, 53260,
        55940, 58740, 61680, 64780,
        68020, 71440, 75020, 78780,
        82720, 86860, 91220, 95800,
        100600, 105640, 110800
    ],

    'Grade_9' => [
        44000, 46200, 48510, 50140,
        53490, 56170, 58980, 61930,
        65030, 68290, 71710, 75300,
        79070, 83030, 87190, 91550,
        96240, 101060, 105900
    ],

    'Grade_10' => [
        32000, 33600, 35280, 37060,
        38920, 40880, 42940, 45100,
        47360, 49740, 52240, 54860,
        57620, 60520, 63560, 66740,
        70080, 73600, 77300
    ],

    'Grade_11' => [
        25000, 26260, 27580, 28960,
        30420, 31960, 33560, 35240,
        37020, 38880, 40840, 42900,
        45060, 47320, 49700, 52200,
        54820, 57580, 60500
    ],

    'Grade_12' => [
        24300, 25520, 26800, 28160,
        29580, 31060, 32620, 34260,
        35980, 37780, 39680, 41680,
        43780, 45980, 48280, 50700,
        53240, 55900, 58700
    ],

    'Grade_13' => [
        24000, 25200, 26460, 27780,
        29180, 30640, 32180, 33800,
        35500, 37280, 39140, 41100,
        43160, 45320, 47600, 49980,
        52480, 55100, 57200
    ],

    'Grade_14' => [
        22500, 23620, 24800, 26040,
        27340, 28720, 30160, 31680,
        33260, 34920, 36680, 38520,
        40460, 42480, 44600, 46840,
        49200, 51680, 54300
    ],

    'Grade_15' => [
        21800, 22880, 24020, 25220,
        26480, 27800, 29200, 30660,
        32200, 33820, 35520, 37300,
        39160, 41120, 43180, 45340,
        47600, 49980, 51700
    ],

    'Grade_16' => [
        21000, 22060, 23160, 24320,
        25540, 26820, 28160, 29580,
        31060, 32620, 34260, 35980,
        37780, 39680, 41680, 43780,
        45980, 48280, 49500
    ],

    'Grade_17' => [
        20800, 21840, 22940, 24080,
        25300, 26560, 27900, 29300,
        30760, 32300, 33920, 35620,
        37400, 39280, 41240, 43300,
        45460, 47740, 48000
    ],

    'Grade_18' => [
        20500, 21520, 22600, 23740,
        24920, 26160, 27480, 28860,
        30300, 31820, 33420, 35100,
        36860, 38700, 40640, 42680,
        44820, 47060, 46900
    ],

    'Grade_19' => [
        20200, 21220, 22280, 23400,
        24580, 25800, 27100, 28460,
        29880, 31380, 32960, 34600,
        36340, 38160, 40060, 42060,
        44160, 46360, 45300
    ],

    'Grade_20' => [
        20000, 21000, 22060, 23160,
        24320, 25540, 26820, 28160,
        29580, 32620, 34260, 35980,
        37780, 39680, 41680, 43780,
        46000, 44000
    ]
];


// =========================================================================
// 2. HELPER FUNCTIONS
// =========================================================================


/**
 * Get first 2026 slot equal to or greater than target.
 */
function getMatchedSlot(array $slots, float $target): float
{
    foreach ($slots as $slot) {

        if ($slot >= $target) {
            return (float) $slot;
        }
    }

    return (float) end($slots);
}


/**
 * Get the next slot after a particular slot.
 */
function getNextSlot(array $slots, float $current): float
{
    foreach ($slots as $slot) {

        if ($slot > $current) {
            return (float) $slot;
        }
    }

    return (float) end($slots);
}


/**
 * Calculate target 2026 basic.
 */
function calculateTarget(
    array $slots2015,
    array $slots2026,
    float $basic
): array {

    $initial2015 =
        (float) $slots2015[0];

    $difference =
        $basic - $initial2015;

    $initial2026 =
        (float) $slots2026[0];

    $target2026 =
        $initial2026 + $difference;

    $matchedSlot =
        getMatchedSlot(
            $slots2026,
            $target2026
        );

    $nextSlot =
        getNextSlot(
            $slots2026,
            $matchedSlot
        );

    return [

        'basic_used' =>
            $basic,

        'initial_basic_2015' =>
            $initial2015,

        'difference' =>
            $difference,

        'initial_basic_2026' =>
            $initial2026,

        'target_basic' =>
            $target2026,

        'matched_slot_2026' =>
            $matchedSlot,

        'matched_slot_increment_2026' =>
            $nextSlot
    ];
}


/**
 * Calculate phase amount.
 *
 * IMPORTANT:
 * Difference is calculated from the ORIGINAL BASIC
 * and the MATCHED 2026 SLOT.
 */
function calculatePhaseAmount(
    float $originalBasic,
    float $matchedSlot,
    float $percentage
): array {

    $difference =
        $matchedSlot - $originalBasic;

    $increase =
        $difference * ($percentage / 100);

    $final =
        $originalBasic + $increase;

    return [

        'difference_with_basic' =>
            $difference,

        'percentage' =>
            $percentage,

        'increase_amount' =>
            $increase,

        'final_pay' =>
            round($final, 2)
    ];
}


/**
 * Get percentages according to grade.
 */
function getPercentages(string $grade): array
{
    $gradeNumber =
        (int) str_replace(
            'Grade_',
            '',
            $grade
        );

    // Grade 1 to 9
    if ($gradeNumber >= 1 && $gradeNumber <= 9) {

        return [
            40,
            70,
            100,
            100
        ];
    }

    // Grade 10 to 20
    return [
        50,
        75,
        100,
        100
    ];
}


/**
 * Format pay scale.
 */
function formatScale(
    array $slots
): string {

    return number_format($slots[0])
        . ' - '
        . number_format(end($slots));
}


/**
 * Get grade number.
 */
function getGradeNumber(
    string $grade
): int {

    return (int) str_replace(
        'Grade_',
        '',
        $grade
    );
}


// =========================================================================
// 3. FORM PROCESSING
// =========================================================================

$results = null;
$error = null;


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $selectedGrade =
        $_POST['grade'] ?? '';

    $currentBasic =
        floatval(
            $_POST['current_basic'] ?? 0
        );


    // ---------------------------------------------------------------------
    // Validate grade
    // ---------------------------------------------------------------------

    if (
        empty($selectedGrade) ||
        !isset(
            $nps_2015_slots[$selectedGrade]
        )
    ) {

        $error =
            "Please select a valid pay grade.";
    }


    // ---------------------------------------------------------------------
    // Validate basic
    // ---------------------------------------------------------------------

    elseif (
        $currentBasic <= 0 ||
        !in_array(
            $currentBasic,
            $nps_2015_slots[$selectedGrade]
        )
    ) {

        $error =
            "Please select a valid current basic salary for this grade.";
    }


    else {

        $slots2015 =
            $nps_2015_slots[$selectedGrade];

        $slots2026 =
            $nps_2026_slots[$selectedGrade];


        $gradeNumber =
            getGradeNumber(
                $selectedGrade
            );


        $percentages =
            getPercentages(
                $selectedGrade
            );


        // -----------------------------------------------------------------
        // Calculate 2026 matching information
        // -----------------------------------------------------------------

        $target =
            calculateTarget(
                $slots2015,
                $slots2026,
                $currentBasic
            );


        $matchedSlot =
            $target['matched_slot_2026'];


        $firstIncrement =
            $target['matched_slot_increment_2026'];


        // -----------------------------------------------------------------
        // PHASE 1
        // 01-07-2026 to 31-12-2026
        // 40% / 50%
        // -----------------------------------------------------------------

        $phase1Amount =
            calculatePhaseAmount(
                $currentBasic,
                $matchedSlot,
                $percentages[0]
            );


        $phase1 =
            array_merge(
                $target,
                $phase1Amount
            );


        // -----------------------------------------------------------------
        // PHASE 2
        // 01-01-2027 to 30-06-2027
        // 70% / 75%
        // -----------------------------------------------------------------

        $phase2Amount =
            calculatePhaseAmount(
                $currentBasic,
                $matchedSlot,
                $percentages[1]
            );


        $phase2 =
            array_merge(
                $target,
                $phase2Amount
            );


        // -----------------------------------------------------------------
        // PHASE 3
        //
        // Scale-up:
        //
        // 50,140 → 53,490 → 56,170
        //
        // The scale-up amount is the NEXT slot after the matched slot.
        // Then the following slot is used for the 100% implementation.
        // -----------------------------------------------------------------

        $scaleUpBasic =
            $firstIncrement;


        $scaleUpNextBasic =
            getNextSlot(
                $slots2026,
                $scaleUpBasic
            );


        // If matched slot is already the last slot,
        // keep the same value.
        if (
            $scaleUpBasic ===
            $matchedSlot &&
            $scaleUpNextBasic ===
            $matchedSlot
        ) {

            $scaleUpNextBasic =
                $scaleUpBasic;
        }


        // -----------------------------------------------------------------
        // PHASE 3 = 100%
        // Final basic becomes scale-up next basic.
        // -----------------------------------------------------------------

        $phase3Difference =
            $scaleUpNextBasic -
            $currentBasic;


        $phase3Increase =
            $phase3Difference * 1.00;


        $phase3Final =
            $currentBasic +
            $phase3Increase;


        $phase3 = [

            'basic_used' =>
                $currentBasic,

            'matched_slot_2026' =>
                $scaleUpBasic,

            'matched_slot_increment_2026' =>
                $scaleUpNextBasic,

            'difference_with_basic' =>
                $phase3Difference,

            'percentage' =>
                100,

            'increase_amount' =>
                $phase3Increase,

            'final_pay' =>
                round(
                    $phase3Final,
                    2
                )
        ];


        // -----------------------------------------------------------------
        // PHASE 4 = 100%
        //
        // Keep the fully implemented basic.
        // -----------------------------------------------------------------

        $phase4 = [

            'basic_used' =>
                $phase3Final,

            'matched_slot_2026' =>
                $scaleUpNextBasic,

            'matched_slot_increment_2026' =>
                $scaleUpNextBasic,

            'difference_with_basic' =>
                $scaleUpNextBasic -
                $currentBasic,

            'percentage' =>
                100,

            'increase_amount' =>
                $scaleUpNextBasic -
                $currentBasic,

            'final_pay' =>
                round(
                    $scaleUpNextBasic,
                    2
                )
        ];


        // -----------------------------------------------------------------
        // FINAL RESULT ARRAY
        // -----------------------------------------------------------------

        $results = [

            'grade' =>
                str_replace(
                    '_',
                    ' ',
                    $selectedGrade
                ),

            'grade_key' =>
                $selectedGrade,

            'grade_number' =>
                $gradeNumber,

            'current_basic' =>
                $currentBasic,

            'old_scale_start' =>
                $slots2015[0],

            'old_scale_end' =>
                end($slots2015),

            'new_scale_start' =>
                $slots2026[0],

            'new_scale_end' =>
                end($slots2026),

            'phase1' =>
                $phase1,

            'phase2' =>
                $phase2,

            'phase3' =>
                $phase3,

            'phase4' =>
                $phase4,

            'matched_slot' =>
                $matchedSlot,

            'scale_up_basic' =>
                $scaleUpBasic,

            'scale_up_next_basic' =>
                $scaleUpNextBasic
        ];
    }
}


// =========================================================================
// 4. RESULT DISPLAY HELPER
// =========================================================================

function renderPhaseCalculation(
    array $phase,
    float $originalBasic,
    bool $showMatched = true
): string {

    $html = '';


    if ($showMatched) {

        $html .= '
        <div class="result-line">
            <span>Matched Slot (2026):</span>
            <strong>
                BDT ' .
                number_format(
                    $phase['matched_slot_2026']
                ) .
                '
            </strong>
        </div>
        ';


        $html .= '
        <div class="result-line">
            <span>Matched Slot with Increment (2026):</span>
            <strong>
                BDT ' .
                number_format(
                    $phase['matched_slot_increment_2026']
                ) .
                '
            </strong>
        </div>
        ';
    }


    $html .= '
    <div class="calculation-box">

        <strong>
            Difference with Basic:
        </strong>

        <br>

        ' .
        number_format(
            $phase['matched_slot_2026']
        ) .
        ' - ' .
        number_format(
            $originalBasic
        ) .
        ' =

        <strong>
            BDT ' .
            number_format(
                $phase['difference_with_basic']
            ) .
            '
        </strong>

    </div>
    ';


    $html .= '
    <div class="percentage-line">

        Difference with basic ×
        <strong>' .
        $phase['percentage'] .
        '%</strong>

        = BDT ' .
        number_format(
            $phase['increase_amount'],
            2
        ) .

        '
    </div>
    ';


    $html .= '
    <div class="final-result">

        Basic Pay:

        <strong>
            BDT ' .
            number_format(
                $phase['final_pay'],
                2
            ) .
            '
        </strong>

    </div>
    ';


    return $html;
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
    NPS 2026 Basic Pay Fixation
</title>


<style>

/* =========================================================================
   GENERAL
   ========================================================================= */

* {
    box-sizing: border-box;
}

body {

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    background:
        #f4f6f9;

    margin: 0;

    padding: 20px;
}


.container {

    max-width: 850px;

    margin:
        0 auto;

    background:
        #ffffff;

    padding:
        30px;

    border-radius:
        8px;

    box-shadow:
        0 4px 15px
        rgba(0,0,0,0.10);
}


h2 {

    text-align:
        center;

    margin-top:
        0;

    color:
        #2c3e50;

    margin-bottom:
        25px;
}


/* =========================================================================
   FORM
   ========================================================================= */

.form-group {

    margin-bottom:
        18px;
}


label {

    display:
        block;

    font-weight:
        bold;

    margin-bottom:
        7px;

    color:
        #333;
}


select {

    width:
        100%;

    padding:
        11px;

    border:
        1px solid #ccc;

    border-radius:
        4px;

    font-size:
        15px;

    background:
        #fff;
}


.button-row {

    display:
        flex;

    gap:
        10px;
}


button {

    flex:
        1;

    padding:
        12px;

    border:
        none;

    border-radius:
        4px;

    font-size:
        16px;

    font-weight:
        bold;

    cursor:
        pointer;

    background:
        #007bff;

    color:
        #fff;
}


button:hover {

    opacity:
        0.9;
}


button:disabled {

    background:
        #9db8d8;

    cursor:
        not-allowed;
}


.reset-btn {

    background:
        #6c757d;
}


/* =========================================================================
   ERROR
   ========================================================================= */

.error {

    background:
        #f8d7da;

    color:
        #721c24;

    padding:
        12px;

    border-radius:
        4px;

    margin-bottom:
        18px;
}


/* =========================================================================
   RESULT BOX
   ========================================================================= */

.phase-box {

    background:
        #ffffff;

    padding:
        22px;

    border:
        1px solid #ddd;

    border-left:
        5px solid #007bff;

    border-radius:
        6px;

    margin-top:
        20px;
}


.phase-box h3 {

    margin-top:
        0;

    color:
        #007bff;

    font-size:
        19px;

    border-bottom:
        1px solid #ddd;

    padding-bottom:
        10px;

    line-height:
        1.5;
}


.scale-title {

    background:
        #f1f3f5;

    padding:
        13px;

    margin-bottom:
        15px;

    line-height:
        1.8;
}


.result-line {

    display:
        flex;

    justify-content:
        space-between;

    gap:
        20px;

    padding:
        8px 0;

    border-bottom:
        1px dashed #ccc;

    line-height:
        1.6;
}


.result-line span {

    font-weight:
        500;
}


.result-line strong {

    text-align:
        right;
}


.calculation-box {

    background:
        #f8f9fa;

    padding:
        13px;

    margin-top:
        15px;

    border-left:
        4px solid #6c757d;

    line-height:
        1.8;
}


.percentage-line {

    background:
        #fff3cd;

    padding:
        12px;

    margin-top:
        12px;

    line-height:
        1.7;
}


.final-result {

    background:
        #d4edda;

    color:
        #155724;

    padding:
        14px;

    margin-top:
        12px;

    font-size:
        18px;

    line-height:
        1.7;

    border-radius:
        4px;
}


.scale-up-box {

    background:
        #e7f1ff;

    padding:
        14px;

    margin:
        10px 0 15px;

    border-left:
        4px solid #007bff;

    font-size:
        17px;

    line-height:
        1.7;
}


.notice {

    background:
        #d4edda;

    color:
        #155724;

    padding:
        15px;

    border-radius:
        4px;

    margin-top:
        20px;

    font-weight:
        bold;

    text-align:
        center;

    font-size:
        18px;
}


.print-btn {

    width:
        100%;

    margin-top:
        20px;

    background:
        #17a2b8;
}


/* =========================================================================
   PRINT
   ========================================================================= */

@media print {

    @page {

        size:
            A4;

        margin:
            15mm;
    }


    body {

        background:
            #fff;

        padding:
            0;

        margin:
            0;
    }


    .container {

        max-width:
            100%;

        width:
            100%;

        padding:
            0;

        margin:
            0;

        box-shadow:
            none;
    }


    h2 {

        color:
            #000;

        font-size:
            22px;

        margin-bottom:
            20px;
    }


    form,
    .print-btn {

        display:
            none !important;
    }


    .phase-box {

        background:
            #fff;

        border:
            1px solid #777;

        border-left:
            4px solid #000;

        border-radius:
            0;

        padding:
            15px;

        margin-top:
            15px;

        page-break-inside:
            avoid;

        break-inside:
            avoid;
    }


    .phase-box h3 {

        color:
            #000;

        font-size:
            16px;
    }


    .scale-title {

        background:
            #f5f5f5;

        font-size:
            13px;
    }


    .result-line {

        font-size:
            13px;
    }


    .calculation-box,
    .percentage-line,
    .scale-up-box {

        font-size:
            13px;
    }


    .final-result {

        font-size:
            15px;
    }


    .notice {

        background:
            #fff;

        color:
            #000;

        border:
            1px solid #333;

        font-size:
            15px;

        page-break-inside:
            avoid;
    }
}

</style>

</head>


<body>


<div class="container">


<h2>
    NPS 2026 Basic Pay Fixation
</h2>


<?php if ($error): ?>

<div class="error">

    <?= htmlspecialchars($error); ?>

</div>

<?php endif; ?>


<!-- =========================================================================
     FORM
     ========================================================================= -->

<form
    method="POST"
    action=""
    id="npsForm"
>


<div class="form-group">

<label for="grade">
    Select Pay Grade:
</label>


<select
    name="grade"
    id="grade"
    required
>

<option value="">
    -- Choose Grade --
</option>


<?php foreach (
    $nps_2015_slots
    as $gradeKey => $slots
): ?>

<option
    value="<?= htmlspecialchars($gradeKey); ?>"
    <?= (
        isset($_POST['grade']) &&
        $_POST['grade'] === $gradeKey
    )
        ? 'selected'
        : ''
    ?>
>

<?= htmlspecialchars(
    str_replace(
        '_',
        ' ',
        $gradeKey
    )
); ?>

(2015 Start:
BDT
<?= number_format($slots[0]); ?>
)

</option>

<?php endforeach; ?>


</select>

</div>


<div class="form-group">

<label for="current_basic">

    Current Basic Salary
    (2015 Scale):

</label>


<select
    name="current_basic"
    id="current_basic"
    required
    disabled
>

<option value="">

    -- Select Grade First --

</option>

</select>

</div>


<div class="button-row">

<button
    type="submit"
    id="submitBtn"
    disabled
>

    Calculate New Basic Pay

</button>


<button
    type="button"
    id="resetBtn"
    class="reset-btn"
>

    Reset

</button>

</div>


</form>


<?php if ($results): ?>


<!-- =========================================================================
     PRINTABLE RESULT
     ========================================================================= -->


<div id="printArea">


<!-- -------------------------------------------------------------------------
     PHASE 1
     ------------------------------------------------------------------------- -->

<div class="phase-box">

<h3>

Basic IN 01-07-2026 to 31-12-2026

(Grade
<?= htmlspecialchars(
    $results['grade']
); ?>
)

</h3>


<div class="scale-title">

<strong>
    Basic Used:
</strong>

BDT
<?= number_format(
    $results['current_basic']
); ?>


&nbsp;&nbsp; | &nbsp;&nbsp;


<strong>
    Scale:
</strong>

<?= number_format(
    $results['old_scale_start']
); ?>

-

<?= number_format(
    $results['old_scale_end']
); ?>

(<?= $results['grade_number']; ?>th grade)

<br>


<strong>
    New Scale:
</strong>

<?= number_format(
    $results['new_scale_start']
); ?>

-

<?= number_format(
    $results['new_scale_end']
); ?>

(<?= $results['grade_number']; ?>th grade)

</div>


<div class="result-line">

<span>
    Initial Basic (2015):
</span>

<strong>
    BDT
    <?= number_format(
        $results['phase1']['initial_basic_2015']
    ); ?>
</strong>

</div>


<div class="result-line">

<span>
    Difference:
</span>

<strong>
    BDT
    <?= number_format(
        $results['phase1']['difference']
    ); ?>
</strong>

</div>


<div class="result-line">

<span>
    Target 2026 Base:
</span>

<strong>
    BDT
    <?= number_format(
        $results['phase1']['target_basic']
    ); ?>
</strong>

</div>


<?= renderPhaseCalculation(
    $results['phase1'],
    $results['current_basic']
); ?>


</div>


<!-- -------------------------------------------------------------------------
     PHASE 2
     ------------------------------------------------------------------------- -->

<div class="phase-box">

<h3>

Basic IN 01-01-2027 to 30-06-2027

(Grade
<?= htmlspecialchars(
    $results['grade']
); ?>
)

</h3>


<?= renderPhaseCalculation(
    $results['phase2'],
    $results['current_basic']
); ?>


</div>


<!-- -------------------------------------------------------------------------
     PHASE 3
     ------------------------------------------------------------------------- -->

<div class="phase-box">

<h3>

Basic IN 01-07-2027 to 31-12-2027

(Grade
<?= htmlspecialchars(
    $results['grade']
); ?>
)

</h3>


<div class="scale-up-box">

<strong>
    After Scale-Up:
</strong>

BDT
<?= number_format(
    $results['scale_up_basic']
); ?>

&nbsp; → &nbsp;

BDT
<?= number_format(
    $results['scale_up_next_basic']
); ?>

</div>


<div class="calculation-box">

<strong>
    Difference with Basic:
</strong>

<br>

<?= number_format(
    $results['scale_up_next_basic']
); ?>

-

<?= number_format(
    $results['current_basic']
); ?>

=

<strong>
BDT
<?= number_format(
    $results['scale_up_next_basic']
        -
    $results['current_basic']
); ?>
</strong>

</div>


<div class="percentage-line">

Difference with basic ×

<strong>
    100%
</strong>

=

BDT
<?= number_format(
    $results['scale_up_next_basic']
        -
    $results['current_basic'],
    2
); ?>

</div>


<div class="final-result">

Basic Pay:

<strong>

BDT
<?= number_format(
    $results['scale_up_next_basic'],
    2
); ?>

</strong>

</div>


</div>


<!-- -------------------------------------------------------------------------
     PHASE 4
     ------------------------------------------------------------------------- -->

<div class="phase-box">

<h3>

Basic IN 01-01-2028 to 30-06-2028

(Grade
<?= htmlspecialchars(
    $results['grade']
); ?>
)

</h3>


<div class="calculation-box">

<strong>
    Difference with Basic:
</strong>

<br>

<?= number_format(
    $results['scale_up_next_basic']
); ?>

-

<?= number_format(
    $results['current_basic']
); ?>

=

<strong>

BDT
<?= number_format(
    $results['scale_up_next_basic']
        -
    $results['current_basic']
); ?>

</strong>

</div>


<div class="percentage-line">

Difference with basic ×

<strong>
    100%
</strong>

=

BDT
<?= number_format(
    $results['scale_up_next_basic']
        -
    $results['current_basic'],
    2
); ?>

</div>


<div class="final-result">

Basic Pay:

<strong>

BDT
<?= number_format(
    $results['scale_up_next_basic'],
    2
); ?>

</strong>

</div>


</div>


<!-- -------------------------------------------------------------------------
     FINAL
     ------------------------------------------------------------------------- -->

<div class="notice">

New Pay Scale Fully Implemented.

</div>


</div>


<button
    type="button"
    id="printBtn"
    class="print-btn"
>

🖨️ Print Results

</button>


<?php endif; ?>


</div>


<script>


// =========================================================================
// JAVASCRIPT
// =========================================================================


const nps2015Slots =
    <?= json_encode(
        $nps_2015_slots
    ); ?>;


const gradeSelect =
    document.getElementById(
        'grade'
    );


const basicSelect =
    document.getElementById(
        'current_basic'
    );


const submitBtn =
    document.getElementById(
        'submitBtn'
    );


const postedGrade =
    <?= json_encode(
        $_POST['grade'] ?? ''
    ); ?>;


const postedBasic =
    <?= json_encode(
        $_POST['current_basic'] ?? ''
    ); ?>;


// =========================================================================
// Populate Basic Salary
// =========================================================================

function populateBasicOptions(
    gradeKey,
    selectedValue
) {

    basicSelect.innerHTML = '';


    if (
        !gradeKey ||
        !nps2015Slots[gradeKey]
    ) {

        basicSelect.innerHTML =
            '<option value="">-- Select Grade First --</option>';

        basicSelect.disabled =
            true;

        submitBtn.disabled =
            true;

        return;
    }


    basicSelect.innerHTML =
        '<option value="">-- Choose Basic --</option>';


    nps2015Slots[
        gradeKey
    ].forEach(
        function(amount) {

            const option =
                document.createElement(
                    'option'
                );


            option.value =
                amount;


            option.textContent =
                'BDT ' +
                amount.toLocaleString(
                    'en-US'
                );


            if (
                selectedValue &&
                parseFloat(
                    selectedValue
                ) === amount
            ) {

                option.selected =
                    true;
            }


            basicSelect.appendChild(
                option
            );
        }
    );


    basicSelect.disabled =
        false;


    submitBtn.disabled =
        !basicSelect.value;
}


// =========================================================================
// Grade Change
// =========================================================================

gradeSelect.addEventListener(
    'change',
    function() {

        populateBasicOptions(
            this.value,
            null
        );
    }
);


// =========================================================================
// Basic Change
// =========================================================================

basicSelect.addEventListener(
    'change',
    function() {

        submitBtn.disabled =
            !this.value;
    }
);


// =========================================================================
// Restore POST values
// =========================================================================

if (postedGrade) {

    populateBasicOptions(
        postedGrade,
        postedBasic
    );
}


// =========================================================================
// Reset
// =========================================================================

document
    .getElementById('resetBtn')
    .addEventListener(
        'click',
        function() {

            window.location.href =
                window.location.pathname;
        }
    );


// =========================================================================
// Print
// =========================================================================

const printBtn =
    document.getElementById(
        'printBtn'
    );


if (printBtn) {

    printBtn.addEventListener(
        'click',
        function() {

            window.print();
        }
    );
}

</script>


</body>

</html>
```
