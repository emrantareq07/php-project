<?php
// Determine the table name based on the recipient

      function table_name_find($dest_drop){    
            
            if ($dest_drop == "পরিচালক (অর্থ)") {
                $table_name2 = 'dirfin';
            } elseif ($dest_drop == "পরিচালক (পরিকল্পনা ও বাস্তবায়ন)") {
                $table_name2 = 'dirpi';
            } elseif ($dest_drop == "পরিচালক (বাণিজ্যিক)") {
                $table_name2 = 'dircom';
            }
            elseif ($dest_drop == "আইসিটি বিভাগ") {
            $table_name2 = 'ict';
            }
            elseif ($dest_drop == "হিসাব নিয়ন্ত্রক") {
            $table_name2 = 'accounts';
            }
            elseif ($dest_drop == "কর্মচারী প্রধান") {
            $table_name2 = 'cop';
            }
           elseif ($dest_drop == "চেয়ারম্যান") {
            $table_name2 = 'chairman';
            }
            elseif ($dest_drop == "পরিচালক (কারিগরি ও প্রকৌশল)") {
            $table_name2 = 'dirte';
            }
            elseif ($dest_drop == "পরিচালক (উৎপাদন ও গবেষণা)") {
            $table_name2 = 'dirpr';
            }
            elseif ($dest_drop == "ঊর্ধ্বতন মহাব্যবস্থাপক (প্রশাসন)") {
            $table_name2 = 'srgmadmin';
            }
            elseif ($dest_drop == "ক্রয় বিভাগ") {
            $table_name2 = 'purchase';
            }
            elseif ($dest_drop == "এমআইএস বিভাগ") {
            $table_name2 = 'mis';
            }
            elseif ($dest_drop == "এমটিএস বিভাগ") {
            $table_name2 = 'mts';
            }
            elseif ($dest_drop == "জনসংযোগ বিভাগ") {
            $table_name2 = 'prd';
            }
            elseif ($dest_drop == "প্রকল্প বাস্তবায়ন বিভাগ") {
            $table_name2 = 'pid';
            }
            elseif ($dest_drop == "গবেষণা ও উৎপাদনশৈলী বিভাগ") {
            $table_name2 = 'rpd';
            }
            elseif ($dest_drop == "বিপণন বিভাগ") {
            $table_name2 = 'mkt';
            }
            elseif ($dest_drop == "নিরীক্ষা বিভাগ") {
            $table_name2 = 'audit';
            }
            elseif ($dest_drop == "অর্থ বিভাগ") {
            $table_name2 = 'finance';
            }
            elseif ($dest_drop == "জনসংযোগ বিভাগ") {
            $table_name2 = 'prd';
            }
            elseif ($dest_drop == "প্রকল্প বাস্তবায়ন বিভাগ") {
            $table_name2 = 'pid';
            }
               elseif ($dest_drop == "গবেষণা ও উৎপাদনশৈলী বিভাগ") {
            $table_name2 = 'rpd';
            }
            elseif ($dest_drop == "বিপণন বিভাগ") {
            $table_name2 = 'mkt';
            }
            elseif ($dest_drop == "নিরীক্ষা বিভাগ") {
            $table_name2 = 'audit';
            }
            elseif ($dest_drop == "অর্থ বিভাগ") {
            $table_name2 = 'finance';
            }
            elseif ($dest_drop == "এস্টেট ম্যানেজমেন্ট ডিপার্টমেন্ট (ইএমডি)") {
            $table_name2 = 'emd';
            }
            elseif ($dest_drop == "পরিকল্পনা বিভাগ") {
            $table_name2 = 'planing';
            }
            elseif ($dest_drop == "উৎপাদন বিভাগ") {
            $table_name2 = 'production';
            }
            elseif ($dest_drop == "সাধারণ কর্মশাখা") {
            $table_name2 = 'csd';
            }
            elseif ($dest_drop == "আইন উপ-বিভাগ") {
            $table_name2 = 'legal';
            }
            elseif ($dest_drop == "বোর্ড ও সমন্বয় উপ-বিভাগ") {
            $table_name2 = 'board';
            }
            elseif ($dest_drop == "কোম্পানী উপ-বিভাগ") {
            $table_name2 = 'comaffairs';
            }
            elseif ($dest_drop == "প্রজেক্ট ডিজাইন ডিভিশন") {
            $table_name2 = 'pdd';
            }
            elseif ($dest_drop == "নির্মাণ বিভাগ") {
            $table_name2 = 'construction';
            }
            return $table_name2;

      }
   
?>