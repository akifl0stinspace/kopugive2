<style>
    /* ============================================
       KopuGive - Professional Pink & Burgundy Theme
       ============================================ */
    
    :root {
        /* Primary Color Palette */
        --burgundy-primary: #850E35;      /* Dark burgundy - primary actions */
        --burgundy-dark: #6a0b2a;         /* Darker shade for hover */
        --coral-pink: #EE6983;            /* Coral pink - secondary actions */
        --light-pink: #FFC4C4;            /* Light pink - accents, hover */
        --cream-bg: #FCF5EE;              /* Cream - backgrounds */
        
        /* Neutral Colors */
        --white: #FFFFFF;
        --light-gray: #F8F9FA;
        --medium-gray: #6C757D;
        --dark-gray: #343A40;
        --border-light: #FFC4C4;
        --border-gray: #DEE2E6;
        
        /* Background Palette */
        --bg-primary: #FFFFFF;
        --bg-secondary: #FCF5EE;
        --bg-accent: #FFC4C4;
        --text-primary: #2C3E50;
        --text-secondary: #6C757D;
        
        /* Shadows - Soft & Professional */
        --shadow-sm: 0 2px 4px rgba(133, 14, 53, 0.08);
        --shadow-md: 0 4px 10px rgba(133, 14, 53, 0.12);
        --shadow-lg: 0 6px 16px rgba(133, 14, 53, 0.15);
    }
    
    /* ============================================
       Global Styles
       ============================================ */
    
    body {
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--text-primary);
        background-color: var(--cream-bg);
        line-height: 1.6;
    }
    
    /* ============================================
       Navigation & Sidebar
       ============================================ */
    
    .navbar-custom {
        background: var(--white);
        box-shadow: var(--shadow-sm);
        border-bottom: 3px solid var(--burgundy-primary);
    }
    
    .navbar-custom .navbar-brand {
        color: var(--text-primary) !important;
        font-weight: 700;
        font-size: 1.5rem;
    }
    
    .navbar-custom .nav-link {
        color: var(--text-primary) !important;
        font-weight: 500;
        transition: all 0.3s ease;
        padding: 0.5rem 1rem !important;
        border-radius: 6px;
        margin: 0 0.2rem;
    }
    
    .navbar-custom .nav-link:hover,
    .navbar-custom .nav-link.active {
        background: var(--light-pink);
        color: var(--burgundy-primary) !important;
    }
    
    .navbar-custom .dropdown-menu {
        border: 1px solid var(--border-gray);
        box-shadow: var(--shadow-md);
    }
    
    /* Admin Sidebar */
    .sidebar {
        min-height: 100vh;
        background: var(--white);
        box-shadow: var(--shadow-md);
        border-right: 3px solid var(--burgundy-primary);
    }
    
    .sidebar .nav-link {
        color: var(--text-primary);
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin: 0.3rem 0.5rem;
        transition: all 0.3s ease;
        font-weight: 500;
        border-left: 3px solid transparent;
    }
    
    .sidebar .nav-link:hover {
        background: var(--light-pink);
        color: var(--burgundy-primary);
        border-left-color: var(--coral-pink);
    }
    
    .sidebar .nav-link.active {
        background: var(--burgundy-primary);
        color: var(--white);
        border-left-color: var(--coral-pink);
        font-weight: 600;
    }
    
    .sidebar .nav-link.active i {
        color: var(--white);
    }
    
    .sidebar .nav-link i {
        width: 20px;
        margin-right: 10px;
        color: var(--text-secondary);
    }
    
    /* ============================================
       Buttons
       ============================================ */
    
    .btn-primary {
        background: var(--burgundy-primary);
        border-color: var(--burgundy-primary);
        color: var(--white);
        font-weight: 600;
        padding: 0.6rem 1.5rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
    }
    
    .btn-primary:hover,
    .btn-primary:focus {
        background: var(--burgundy-dark);
        border-color: var(--burgundy-dark);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }
    
    .btn-outline-primary {
        border-color: var(--burgundy-primary);
        color: var(--burgundy-primary);
        font-weight: 600;
        padding: 0.6rem 1.5rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        background: transparent;
    }
    
    .btn-outline-primary:hover,
    .btn-outline-primary:focus {
        background: var(--burgundy-primary);
        border-color: var(--burgundy-primary);
        color: var(--white);
    }
    
    .btn-secondary {
        background: var(--white);
        border: 2px solid var(--light-pink);
        color: var(--burgundy-primary);
        font-weight: 500;
        border-radius: 8px;
    }
    
    .btn-secondary:hover {
        background: var(--light-pink);
        border-color: var(--coral-pink);
    }
    
    /* ============================================
       Cards
       ============================================ */
    
    .card {
        border: 1px solid var(--border-light);
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        background: var(--white);
    }
    
    .card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }
    
    .card-header {
        background: var(--cream-bg);
        color: var(--burgundy-primary);
        border-bottom: 2px solid var(--light-pink);
        border-radius: 12px 12px 0 0 !important;
        font-weight: 600;
        padding: 1rem 1.5rem;
    }
    
    .card-header.bg-primary {
        background: var(--burgundy-primary) !important;
        color: var(--white) !important;
        border-bottom: none;
    }
    
    /* Stat Cards */
    .stat-card {
        border-left: 4px solid var(--burgundy-primary);
        transition: all 0.3s ease;
        background: var(--white);
        border-radius: 12px;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-left-color: var(--coral-pink);
    }
    
    .stat-card .text-primary {
        color: var(--burgundy-primary) !important;
    }
    
    /* Campaign Cards */
    .campaign-card {
        transition: all 0.3s ease;
        border: 2px solid var(--border-light);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        background: var(--white);
    }
    
    .campaign-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
        border-color: var(--coral-pink);
    }
    
    .campaign-image {
        height: 200px;
        object-fit: cover;
        background: var(--cream-bg);
    }
    
    /* ============================================
       Badges
       ============================================ */
    
    .badge.bg-primary {
        background: var(--burgundy-primary) !important;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 6px;
    }
    
    .badge.bg-secondary {
        background: var(--light-pink) !important;
        color: var(--burgundy-primary) !important;
        border: 1px solid var(--coral-pink);
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 6px;
    }
    
    .badge.bg-info {
        background: var(--cream-bg) !important;
        color: var(--burgundy-primary) !important;
        border: 1px solid var(--light-pink);
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 6px;
    }
    
    /* ============================================
       Progress Bars
       ============================================ */
    
    .progress {
        background-color: var(--light-pink);
        border-radius: 12px;
        overflow: hidden;
        height: 10px;
    }
    
    .progress-bar {
        background: linear-gradient(90deg, var(--burgundy-primary) 0%, var(--coral-pink) 100%);
        transition: width 1s ease;
    }
    
    .progress-bar.bg-success {
        background: linear-gradient(90deg, var(--burgundy-primary) 0%, var(--coral-pink) 100%) !important;
    }
    
    .progress-large {
        height: 24px;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 12px;
    }
    
    /* ============================================
       Forms
       ============================================ */
    
    .form-control,
    .form-select {
        border-radius: 8px;
        border: 2px solid var(--border-light);
        transition: all 0.3s ease;
    }
    
    .form-control:focus,
    .form-select:focus {
        border-color: var(--burgundy-primary);
        box-shadow: 0 0 0 0.25rem rgba(133, 14, 53, 0.15);
    }
    
    .input-group-text {
        background: var(--cream-bg);
        border-color: var(--border-light);
        border-radius: 8px;
    }
    
    /* ============================================
       Tables
       ============================================ */
    
    .table-hover tbody tr:hover {
        background-color: var(--light-pink);
    }
    
    .table thead {
        background: var(--cream-bg);
        color: var(--burgundy-primary);
        font-weight: 600;
        border-bottom: 3px solid var(--burgundy-primary);
    }
    
    /* ============================================
       Alerts
       ============================================ */
    
    .alert-primary {
        background-color: rgba(133, 14, 53, 0.08);
        border-color: var(--burgundy-primary);
        border-left: 4px solid var(--burgundy-primary);
        color: var(--burgundy-primary);
        border-radius: 8px;
    }
    
    .alert-info {
        background-color: var(--light-pink);
        border-color: var(--coral-pink);
        border-left: 4px solid var(--coral-pink);
        color: var(--burgundy-primary);
        border-radius: 8px;
    }
    
    /* ============================================
       Welcome Banner
       ============================================ */
    
    .welcome-banner {
        background: linear-gradient(135deg, var(--burgundy-primary) 0%, var(--coral-pink) 100%);
        color: var(--white);
        border-radius: 16px;
        padding: 2.5rem;
        box-shadow: var(--shadow-lg);
    }
    
    .welcome-banner h3 {
        color: var(--white);
        font-weight: 700;
    }
    
    .welcome-banner p {
        color: rgba(255, 255, 255, 0.95);
    }
    
    /* ============================================
       Campaign Banner
       ============================================ */
    
    .campaign-banner {
        height: 400px;
        background: var(--cream-bg);
        object-fit: cover;
    }
    
    /* ============================================
       Text Colors
       ============================================ */
    
    .text-primary {
        color: var(--burgundy-primary) !important;
    }
    
    .text-maroon {
        color: var(--burgundy-primary) !important;
    }
    
    .text-gold {
        color: var(--coral-pink) !important;
    }
    
    .bg-primary {
        background: var(--burgundy-primary) !important;
        color: var(--white) !important;
    }
    
    .bg-gold {
        background: var(--cream-bg) !important;
    }
    
    /* ============================================
       Links
       ============================================ */
    
    a {
        color: var(--burgundy-primary);
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    a:hover {
        color: var(--coral-pink);
    }
    
    /* ============================================
       Pagination
       ============================================ */
    
    .pagination .page-link {
        color: var(--burgundy-primary);
        border-color: var(--border-light);
        background: var(--white);
        border-radius: 8px;
        margin: 0 0.25rem;
    }
    
    .pagination .page-link:hover {
        background-color: var(--light-pink);
        border-color: var(--coral-pink);
        color: var(--burgundy-primary);
    }
    
    .pagination .page-item.active .page-link {
        background-color: var(--burgundy-primary);
        border-color: var(--burgundy-primary);
    }
    
    /* ============================================
       Dropdown Menu
       ============================================ */
    
    .dropdown-item:hover,
    .dropdown-item:focus {
        background-color: var(--light-pink);
        color: var(--burgundy-primary);
    }
    
    /* ============================================
       Accent Elements
       ============================================ */
    
    .border-primary {
        border-color: var(--burgundy-primary) !important;
    }
    
    .border-gold {
        border-color: var(--coral-pink) !important;
    }
    
    /* Accent line for sections */
    .section-divider {
        border-top: 3px solid var(--light-pink);
        margin: 2rem 0;
    }
    
    /* ============================================
       Print Styles
       ============================================ */
    
    @media print {
        .sidebar,
        .navbar,
        .btn,
        .no-print {
            display: none !important;
        }
        
        main {
            margin-left: 0 !important;
            width: 100% !important;
        }
        
        .card {
            page-break-inside: avoid;
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
        
        .badge {
            border: 1px solid #000;
        }
    }
    
    /* ============================================
       Responsive Design
       ============================================ */
    
    @media (max-width: 768px) {
        .navbar-custom .navbar-brand {
            font-size: 1.2rem;
        }
        
        .campaign-card {
            margin-bottom: 1rem;
        }
        
        .stat-card {
            margin-bottom: 1rem;
        }
    }
    
    /* ============================================
       Subtle Animations
       ============================================ */
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .fade-in {
        animation: fadeIn 0.4s ease-in;
    }
    
    /* ============================================
       Custom Scrollbar (Webkit browsers)
       ============================================ */
    
    ::-webkit-scrollbar {
        width: 10px;
    }
    
    ::-webkit-scrollbar-track {
        background: var(--cream-bg);
    }
    
    ::-webkit-scrollbar-thumb {
        background: var(--light-pink);
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: var(--coral-pink);
    }
</style>
