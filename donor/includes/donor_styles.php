<style>
    /* Donor Sidebar Styles */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        overflow-y: auto;
        background: #ffffff;
        border-right: 3px solid #850E35;
        box-shadow: 0 4px 10px rgba(133, 14, 53, 0.12);
        z-index: 1000;
    }
    
    .sidebar h4 {
        color: #850E35;
    }
    
    .sidebar small {
        color: #6C757D;
    }
    
    .sidebar hr {
        border-color: #FFC4C4 !important;
    }
    
    main {
        margin-left: 16.666667%;
    }
    
    @media (max-width: 768px) {
        .sidebar {
            position: relative;
            height: auto;
            border-right: none;
            border-bottom: 3px solid #850E35;
        }
        
        main {
            margin-left: 0;
        }
    }
</style>


