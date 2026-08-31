$year = date('Y', strtotime($date));

// Extract month (numeric)
$month = date('m', strtotime($date));
// Optional: Extract month name
$monthName = date('F', strtotime($date));