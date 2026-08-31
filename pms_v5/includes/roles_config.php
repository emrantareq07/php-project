<?php
/**
 * Central role -> dashboard -> menu configuration.
 *
 * This is the ONE file to edit when:
 *  - a role is renamed
 *  - a dashboard filename changes
 *  - a menu item needs to be added/removed for a role
 *
 * auth_guard.php, sidebar.php, and index.php's redirect map all read from here,
 * so a change made once here is reflected everywhere.
 */

return [

    'doctor' => [
        'label'     => 'Doctor',
        'dashboard' => 'doctor_dashboard.php',
        'menu' => [
            ['label' => 'Dashboard',             'href' => 'doctor_dashboard.php',  'icon' => 'home'],
            ['label' => 'Patients Management',   'href' => 'patient_mgtm.php',      'icon' => 'users'],
            ['label' => 'Add New Patients',      'href' => 'employee_management.php',  'icon' => 'file-plus'],
            ['label' => 'New Prescription',      'href' => 'booked_med_entry.php',  'icon' => 'file-plus'],
            ['label' => 'Prescription History',  'href' => 'booked_med_list.php',   'icon' => 'file-text'],
            ['label' => 'Special Medicine',      'href' => 'special_medicine.php',  'icon' => 'flask'],
            ['label' => 'Medicine Reports',      'href' => 'reports.php',  'icon' => 'flask'],
            ['label' => 'Prescription Reports',   'href' => 'prescription_reports.php',  'icon' => 'flask'],
        ],
    ],

    'pharmacist' => [
        'label'     => 'Pharmacist',
        'dashboard' => 'pharmacist_dashboard.php',
        'menu' => [
            ['label' => 'Dashboard',              'href' => 'pharmacist_dashboard.php', 'icon' => 'home'],
            ['label' => 'Dispense Prescriptions',  'href' => 'dispense_list.php',        'icon' => 'pill'],
            ['label' => 'Medicine Stock',          'href' => 'medicine_stock_view.php',  'icon' => 'box'],
            ['label' => 'Dispensing History',      'href' => 'dispense_history.php',     'icon' => 'clock'],
        ],
    ],

    'store_incharge' => [
        'label'     => 'Store In-Charge',
        'dashboard' => 'store_dashboard.php',
        'menu' => [
            ['label' => 'Dashboard',        'href' => 'store_dashboard.php', 'icon' => 'home'],
            ['label' => 'Manage Medicines', 'href' => 'medicine_manage.php', 'icon' => 'pill'],
            ['label' => 'Stock Levels',     'href' => 'stock_levels.php',    'icon' => 'box'],
            ['label' => 'Restock Entry',    'href' => 'restock_entry.php',   'icon' => 'truck'],
        ],
    ],

    'sadmin' => [
        'label'     => 'System Admin',
        'dashboard' => 'sadmin_dashboard.php',
        'menu' => [
            ['label' => 'Dashboard',           'href' => 'sadmin_dashboard.php',    'icon' => 'home'],
            ['label' => 'User Management',     'href' => 'user_management.php',     'icon' => 'user-cog'],
            ['label' => 'Employee Management', 'href' => 'employee_management.php', 'icon' => 'id-badge'],
            ['label' => 'System Logs',         'href' => 'system_logs.php',         'icon' => 'list'],
        ],
    ],

    'user' => [
        'label'     => 'Employee',
        'dashboard' => 'user_dashboard.php',
        'menu' => [
            ['label' => 'Dashboard',        'href' => 'user_dashboard.php',   'icon' => 'home'],
            ['label' => 'My Prescriptions', 'href' => 'my_prescriptions.php', 'icon' => 'file-text'],
            ['label' => 'My Profile',       'href' => 'my_profile.php',       'icon' => 'user'],
        ],
    ],

];
