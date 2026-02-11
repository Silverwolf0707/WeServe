<?php

return [
    'navigation' => [
        'home' => 'HOME',
        'about' => 'ABOUT',
        'services' => 'SERVICES',
        'track_application' => 'TRACK APPLICATION',
        'apply_here' => 'APPLY HERE!',
    ],
    
    'hero' => [
        'title' => 'WELCOME TO WESERVE',
        'description' => 'The :cswd web system for online tracking and application of financial support to residents facing emergencies or difficult situations. Apply now to check if you qualify for assistance.',
        'cswd_full' => 'City Social Welfare and Development Office (CSWD)',
        'start_application' => 'Start Application',
    ],
    
    'about' => [
        'title' => 'ABOUT US',
        'description1' => 'The :cswd_office is dedicated to uplifting vulnerable sectors like children, women, senior citizens, persons with disabilities, and disadvantaged families.',
        'description2' => 'Through financial aid, community development, disaster response, and livelihood support, CSWD works to ensure that every San Pedronian has access to care and assistance when needed.',
        'cswd_full_office' => 'City Social Welfare and Development (CSWD) Office of San Pedro, Laguna',
        'slider_alt' => [
            'community_work' => 'Community Work',
            'cswd_office' => 'CSWD Office',
            'support_services' => 'Support Services',
        ],
    ],
    
    'services' => [
        'title' => 'SERVICE CATEGORIES',
        'educational' => [
            'title' => 'EDUCATIONAL ASSISTANCE',
            'description' => 'Support for school fees.',
        ],
        'burial' => [
            'title' => 'BURIAL ASSISTANCE',
            'description' => 'Help with funeral and burial expenses.',
        ],
        'medical' => [
            'title' => 'MEDICAL ASSISTANCE',
            'description' => 'Support for treatments, medications, and hospital bills.',
        ],
        'emergency' => [
            'title' => 'EMERGENCY ASSISTANCE',
            'description' => 'Immediate support.',
        ],
    ],
    
    'process' => [
        'title' => 'APPLICATION PROCESS',
        'steps' => [
            'application' => [
                'title' => 'Application',
                'description' => 'Fill out the simple online form with your information and financial needs.',
                'button' => 'Start Application',
            ],
            'review' => [
                'title' => 'Review Process',
                'description' => ':cswd or :aksyon will review your application.',
                'button' => 'Track Process',
                'cswd' => 'CSWD Office',
                'aksyon' => 'Aksyon Mamamayan Center',
            ],
            'receive' => [
                'title' => 'Receive Assistance',
                'description' => 'If approved, assistance is claimable through the City Treasury Office',
            ],
        ],
    ],
    
    'contact' => [
        'title' => 'CONTACT US',
        'description' => 'Have questions or need assistance? Reach out to us!',
        'button' => 'Contact Us',
        
        'modal' => [
            'title' => 'Contact Us',
            'close' => 'Close',
            'office_info' => 'Office Information',
            'contact_details' => 'Contact Details',
            
            'info' => [
                'office' => 'Office:',
                'office_value' => 'City Social Welfare and Development Office (CSWD)',
                'address' => 'Address:',
                'address_value' => 'Basement, New City Hall Bldg., Brgy. Poblacion, City of San Pedro, Laguna',
                'hours' => 'Office Hours:',
                'hours_value' => 'Monday to Friday, 8:00 AM - 5:00 PM',
                'hours_note' => 'Closed on weekends and holidays',
            ],
            
            'contact' => [
                'email' => 'Email:',
                'email_value' => 'cswdosanpedro@gmail.com',
                'phone' => 'Phone:',
                'phone_value' => '8-8082020',
                'fax' => 'Fax:',
                'fax_value' => '(049) 555-1234',
            ],
            
            'social' => [
                'title' => 'Follow Us on Social Media',
                'facebook' => 'Facebook',
                'twitter' => 'Twitter',
                'instagram' => 'Instagram',
                'email' => 'Email',
            ],
        ],
    ],
    
    'footer' => [
        'description' => 'Providing support when it\'s needed most. Dedicated to helping communities and individuals achieve their best.',
        
        'quick_links' => [
            'title' => 'Quick Links',
            'home' => 'Home',
            'about' => 'About Us',
            'services' => 'Services',
            'process' => 'Application Process',
            'contact' => 'Contact',
        ],
        
        'contacts' => [
            'title' => 'Contacts',
            'email' => 'Email: cswdosanpedro@gmail.com',
            'phone' => 'Phone: 8-8082020',
            'address' => 'Address: Basement, New City Hall Bldg., Brgy. Poblacion, City of San Pedro, Laguna',
            'hours' => 'Office Hours: Mon - Fri, 8:00 AM - 5:00 PM',
        ],
        
        'social' => [
            'title' => 'Follow Us',
            'facebook' => 'Facebook',
            'twitter' => 'Twitter',
            'instagram' => 'Instagram',
            'linkedin' => 'LinkedIn',
        ],
        
        'bottom' => [
            'copyright' => '&copy; 2026 WeServe. All rights reserved.',
            'terms' => 'Terms and Conditions',
            'privacy' => 'Privacy Policy',
        ],
    ],
    
    'modals' => [
        'application' => [
            'title' => 'Application Form',
            'close' => 'Close',
            
            'fields' => [
                'applicant_name' => 'Applicant Name',
                'age' => 'Age',
                'address' => 'Address',
                'contact_number' => 'Contact Number',
                'claimant_name' => 'Claimant Name',
                'diagnosis' => 'Diagnosis (Optional)',
                'case_type' => 'Type',
                'service_category' => 'Service Category',
            ],
            
            'placeholders' => [
                'select' => 'Please select',
            ],
            
            'validation' => [
                'required' => 'This field is required.',
                'age_range' => 'Please provide a valid age (1-120).',
                'phone_format' => 'Please enter a valid 11-digit Philippine mobile number starting with 09.',
            ],
            
            'requirements' => [
                'title' => 'Required Documents',
                'note' => 'Note: Please refer to the Citizen\'s Charter for the full details of required documents.',
                'citizen_charter' => 'Citizen\'s Charter',
            ],
            
            'buttons' => [
                'cancel' => 'Cancel',
                'review' => 'Review & Submit',
            ],
        ],
        
        'confirmation' => [
            'title' => 'Confirm Application Details',
            'instructions' => 'Please review your application details:',
            'edit' => 'Edit Details',
            'confirm' => 'Confirm & Submit',
        ],
        
        'tracking' => [
            'title' => 'Application Submitted!',
            'message' => 'Your tracking number is:',
            'copy' => 'Copy Tracking Number',
            'copied' => 'Copied!',
        ],
        
        'track' => [
            'title' => 'Track Application',
            'tracking_number' => 'Tracking Number',
            'cancel' => 'Cancel',
            'track' => 'Track Now',
        ],
    ],
    
    'alerts' => [
        'validation_error' => 'Please fill in all required fields correctly.',
        'submitting' => 'Submitting your application...',
        'copy_success' => 'Tracking number copied to clipboard!',
        'copy_error' => 'Failed to copy tracking number. Please copy manually.',
    ],
    
    'misc' => [
        'processing' => 'Processing...',
        'submitting' => 'Submitting...',
        'copying' => 'Copying...',
        'not_provided' => 'Not provided',
    ],
];