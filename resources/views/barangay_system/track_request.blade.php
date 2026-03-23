<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Hulong Duhat - Track Request</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
    
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/floating-actions.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/faq.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            scroll-behavior: smooth;
        }

        /* Full Width Background Sections */
        .section-bg {
            background: #f8f9fa;
            width: 100%;
            padding: 80px 0;
        }

        /* Track Form Section */
        .track-form-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .track-form-card {
            background: white;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.1);
            border: 1px solid #f0f0f0;
        }

        .form-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .form-header .form-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(198, 40, 40, 0.3);
            border: 3px solid #C62828;
        }

        .form-header .form-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .form-header h2 {
            font-size: 2rem;
            color: #333;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .form-header p {
            color: #666;
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        .form-group label i {
            color: #C62828;
            margin-right: 5px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper .form-control {
            padding: 15px 50px 15px 20px;
            border: 2px solid #eee;
            border-radius: 12px;
            font-size: 1.1rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            background: #f8f9fa;
            width: 100%;
        }

        .input-wrapper .form-control:focus {
            border-color: #C62828;
            background: white;
            box-shadow: 0 0 0 4px rgba(198, 40, 40, 0.12);
            outline: none;
        }

        .input-wrapper .input-icon {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #ccc;
            font-size: 1.2rem;
        }

        .form-text {
            display: block;
            margin-top: 8px;
            color: #999;
            font-size: 0.85rem;
        }

        .track-form-card select.form-control {
            padding: 15px 20px;
            border: 2px solid #eee;
            border-radius: 12px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            background: #f8f9fa;
            color: #333;
            cursor: pointer;
            width: 100%;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            padding-right: 40px;
        }

        .track-form-card select.form-control:focus {
            border-color: #C62828;
            background: white;
            box-shadow: 0 0 0 4px rgba(198, 40, 40, 0.12);
        }

        .btn-track {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #C62828, #d32f2f);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.15rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 8px 25px rgba(198, 40, 40, 0.3);
        }

        .btn-track:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(198, 40, 40, 0.4);
        }

        .btn-track:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Track Result Section */
        .track-result-section {
            padding: 0 0 80px;
            background: #f8f9fa;
        }

        .result-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 35px rgba(0,0,0,0.1);
            border: 1px solid #f0f0f0;
        }

        .result-header {
            background: linear-gradient(135deg, #C62828, #7a2323);
            color: white;
            padding: 30px 40px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .result-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }

        /* Status Badge Colors based on definitions */
        .status-badge {
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .status-badge.pending {
            background: rgba(255, 152, 0, 0.2);
            color: #FF9800;
            border: 1px solid rgba(255, 152, 0, 0.3);
        }

        .status-badge.under-review {
            background: rgba(33, 150, 243, 0.2);
            color: #2196F3;
            border: 1px solid rgba(33, 150, 243, 0.3);
        }

        .status-badge.approved {
            background: rgba(76, 175, 80, 0.2);
            color: #4CAF50;
            border: 1px solid rgba(76, 175, 80, 0.3);
        }

        .status-badge.payment-required {
            background: rgba(255, 193, 7, 0.2);
            color: #FFC107;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .status-badge.processing {
            background: rgba(33, 150, 243, 0.2);
            color: #2196F3;
            border: 1px solid rgba(33, 150, 243, 0.3);
        }

        .status-badge.ready-for-pickup {
            background: rgba(0, 150, 136, 0.2);
            color: #009688;
            border: 1px solid rgba(0, 150, 136, 0.3);
        }

        .status-badge.claimed, .status-badge.released {
            background: rgba(76, 175, 80, 0.2);
            color: #4CAF50;
            border: 1px solid rgba(76, 175, 80, 0.3);
        }

        .status-badge.rejected, .status-badge.denied {
            background: rgba(244, 67, 54, 0.2);
            color: #F44336;
            border: 1px solid rgba(244, 67, 54, 0.3);
        }

        .result-body {
            padding: 40px;
        }

        .detail-item {
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-item .label {
            display: block;
            font-size: 0.85rem;
            color: #999;
            margin-bottom: 5px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-item .value {
            display: block;
            font-size: 1.05rem;
            color: #333;
            font-weight: 600;
        }

        /* Progress Timeline */
        .progress-timeline {
            padding: 40px;
            border-top: 1px solid #f0f0f0;
            background: #fafafa;
        }

        .progress-timeline h3 {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .progress-timeline h3 i {
            color: #C62828;
        }

        .timeline {
            position: relative;
            padding-left: 40px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #e0e0e0;
            border-radius: 2px;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 30px;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-marker {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            position: absolute;
            left: -40px;
            z-index: 1;
            flex-shrink: 0;
        }

        .timeline-item.completed .timeline-marker {
            background: #4CAF50;
            color: white;
            box-shadow: 0 3px 10px rgba(76, 175, 80, 0.3);
        }

        .timeline-item.active .timeline-marker {
            background: #FF9800;
            color: white;
            box-shadow: 0 3px 10px rgba(255, 152, 0, 0.3);
        }

        .timeline-item.pending .timeline-marker {
            background: #e0e0e0;
            color: #999;
        }

        .timeline-content {
            margin-left: 0;
        }

        .timeline-content h4 {
            font-size: 1rem;
            color: #333;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .timeline-content p {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 5px;
        }

        .timeline-content .time {
            font-size: 0.8rem;
            color: #999;
            font-style: italic;
        }

        .timeline-item.pending .timeline-content h4,
        .timeline-item.pending .timeline-content p {
            color: #bbb;
        }

        /* Result Actions */
        .result-actions {
            padding: 30px 40px;
            border-top: 1px solid #f0f0f0;
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .btn-print,
        .btn-new-track {
            padding: 14px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-print {
            background: linear-gradient(135deg, #C62828, #d32f2f);
            color: white;
            text-decoration: none;
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(198, 40, 40, 0.3);
        }

        .btn-new-track {
            background: #f8f9fa;
            color: #333;
            border: 2px solid #eee;
            text-decoration: none;
        }

        .btn-new-track:hover {
            background: #eee;
            transform: translateY(-2px);
        }

        /* My Requests Table Section */
        .my-requests-section {
            padding: 80px 0;
            background: white;
        }

        .requests-table-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.1);
            border: 1px solid #f0f0f0;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .table-header h2 {
            font-size: 1.8rem;
            color: #333;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-header h2 i {
            color: #C62828;
        }

        .filter-tabs {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 8px 16px;
            border: 1px solid #ddd;
            border-radius: 25px;
            background: white;
            color: #666;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .filter-tab:hover {
            border-color: #C62828;
            color: #C62828;
        }

        .filter-tab.active {
            background: #C62828;
            color: white;
            border-color: #C62828;
        }

        .table-responsive {
            overflow-x: auto;
            margin-bottom: 30px;
        }

        .requests-table {
            width: 100%;
            border-collapse: collapse;
        }

        .requests-table th {
            text-align: left;
            padding: 15px 10px;
            background: #f8f9fa;
            color: #333;
            font-weight: 600;
            font-size: 0.9rem;
            border-bottom: 2px solid #ddd;
        }

        .requests-table td {
            padding: 15px 10px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        .requests-table tbody tr:hover {
            background: #f8f9fa;
        }

        /* Small Status Badges for Table */
        .status-badge-small {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        .status-pending {
            background: #fff3cd;
            color: #FF9800;
            border: 1px solid #FFE0B2;
        }

        .status-under-review {
            background: #cce5ff;
            color: #2196F3;
            border: 1px solid #BBDEFB;
        }

        .status-approved {
            background: #d4edda;
            color: #4CAF50;
            border: 1px solid #C8E6C9;
        }

        .status-payment-required {
            background: #fff3cd;
            color: #FFC107;
            border: 1px solid #FFE082;
        }

        .status-processing {
            background: #cce5ff;
            color: #2196F3;
            border: 1px solid #BBDEFB;
        }

        .status-ready-for-pickup {
            background: #d1e7dd;
            color: #009688;
            border: 1px solid #B2DFDB;
        }

        .status-claimed, .status-released {
            background: #d4edda;
            color: #4CAF50;
            border: 1px solid #C8E6C9;
        }

        .status-rejected, .status-denied {
            background: #f8d7da;
            color: #F44336;
            border: 1px solid #FFCDD2;
        }

        .btn-view {
            padding: 6px 15px;
            background: #C62828;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
        }

        .btn-view:hover {
            background: #8B0000;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(198, 40, 40, 0.2);
            color: white;
        }

        .btn-view i {
            font-size: 0.8rem;
        }

        .request-type-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            background: #f0f0f0;
            border-radius: 15px;
            font-size: 0.8rem;
            color: #555;
        }

        .assigned-officer {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .assigned-officer i {
            color: #C62828;
            font-size: 0.9rem;
        }

        .no-requests {
            text-align: center;
            padding: 50px 20px;
            color: #999;
        }

        .no-requests i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #ddd;
        }

        .no-requests p {
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .no-requests .sub-text {
            font-size: 0.9rem;
            color: #aaa;
        }

        /* Status Legend Section */
        .status-legend-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
            font-size: 2.2rem;
            font-weight: 700;
            color: #C62828;
        }

        .section-subtitle {
            text-align: center;
            color: #666;
            font-size: 1.1rem;
            margin-top: -35px;
            margin-bottom: 50px;
        }

        .status-card {
            background: white;
            border-radius: 16px;
            padding: 35px 25px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border: 1px solid #eee;
            height: 100%;
            transition: all 0.3s ease;
        }

        .status-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 35px rgba(198, 40, 40, 0.12);
            border-color: rgba(198, 40, 40, 0.2);
        }

        .status-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 1.8rem;
        }

        .status-card.pending .status-icon {
            background: linear-gradient(135deg, #FF9800, #F57C00);
            color: white;
        }

        .status-card.under-review .status-icon {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            color: white;
        }

        .status-card.approved .status-icon {
            background: linear-gradient(135deg, #4CAF50, #388E3C);
            color: white;
        }

        .status-card.payment-required .status-icon {
            background: linear-gradient(135deg, #FFC107, #FFA000);
            color: white;
        }

        .status-card.processing .status-icon {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            color: white;
        }

        .status-card.ready-pickup .status-icon {
            background: linear-gradient(135deg, #009688, #00796B);
            color: white;
        }

        .status-card.completed .status-icon {
            background: linear-gradient(135deg, #9C27B0, #7B1FA2);
            color: white;
        }

        .status-card.rejected .status-icon {
            background: linear-gradient(135deg, #F44336, #D32F2F);
            color: white;
        }

        .status-card h3 {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .status-card p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.5;
            margin: 0;
        }

        /* Quick Links Section */
        .quick-links-section {
            padding: 80px 0;
            background: white;
        }

        .quick-link-card {
            display: block;
            background: white;
            border-radius: 16px;
            padding: 35px 25px;
            text-align: center;
            text-decoration: none;
            color: #333;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border: 1px solid #eee;
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
        }

        .quick-link-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 35px rgba(198, 40, 40, 0.12);
            border-color: rgba(198, 40, 40, 0.2);
            color: #333;
            text-decoration: none;
        }

        .quick-link-card .card-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #C62828, #d32f2f);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 1.8rem;
            transition: all 0.3s ease;
        }

        .quick-link-card:hover .card-icon {
            transform: scale(1.1);
            box-shadow: 0 8px 20px rgba(198, 40, 40, 0.3);
        }

        .quick-link-card h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .quick-link-card p {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 15px;
        }

        .link-arrow {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            background: #f8f9fa;
            border-radius: 50%;
            color: #C62828;
            transition: all 0.3s ease;
        }

        .quick-link-card:hover .link-arrow {
            background: #C62828;
            color: white;
            transform: translateX(5px);
        }

        /* Responsive Design */
        @media (max-width: 1366px) {
            .track-form-card {
                padding: 40px;
            }
            
            .result-body,
            .progress-timeline,
            .result-actions {
                padding: 30px;
            }
            
            .requests-table-card {
                padding: 30px;
            }
        }

        @media (max-width: 1200px) {
            .track-form-section,
            .status-legend-section,
            .quick-links-section,
            .my-requests-section {
                padding: 70px 0;
            }
        }

        @media (max-width: 992px) {
            .track-form-section,
            .status-legend-section,
            .quick-links-section,
            .my-requests-section {
                padding: 60px 0;
            }
            
            .track-form-card {
                padding: 35px;
            }
            
            .result-header {
                flex-direction: column;
                text-align: center;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
            
            .requests-table-card {
                padding: 25px;
            }
        }

        @media (max-width: 768px) {   
            .track-form-card {
                padding: 25px;
            }
            
            .form-header h2 {
                font-size: 1.5rem;
            }
            
            .input-wrapper .form-control {
                font-size: 1rem;
                padding: 12px 45px 12px 16px;
            }
            
            .btn-track {
                font-size: 1rem;
                padding: 14px;
            }
            
            .result-body {
                padding: 20px;
            }
            
            .progress-timeline {
                padding: 25px;
            }
            
            .result-actions {
                padding: 20px;
                flex-direction: column;
            }
            
            .btn-print,
            .btn-new-track {
                width: 100%;
                justify-content: center;
            }
            
            .section-title {
                font-size: 1.6rem;
            }
            
            .section-subtitle {
                font-size: 1rem;
                margin-top: -25px;
            }
            
            .status-card {
                padding: 25px 20px;
            }
            
            .timeline {
                padding-left: 35px;
            }
            
            .timeline-marker {
                width: 28px;
                height: 28px;
                left: -35px;
                font-size: 0.7rem;
            }
            
            .requests-table-card {
                padding: 20px;
            }
            
            .table-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .filter-tabs {
                width: 100%;
                overflow-x: auto;
                padding-bottom: 5px;
            }
            
            .filter-tab {
                white-space: nowrap;
            }
        }

        @media (max-width: 576px) {
            .track-form-section,
            .status-legend-section,
            .quick-links-section,
            .track-result-section,
            .my-requests-section {
                padding: 45px 0;
            }
            
            .track-result-section {
                padding: 0 0 45px;
            }
            
            .track-form-card {
                padding: 20px;
                border-radius: 15px;
            }
            
            .form-header .form-icon {
                width: 60px;
                height: 60px;
            }
            
            .form-header h2 {
                font-size: 1.3rem;
            }
            
            .result-card {
                border-radius: 15px;
            }
            
            .result-header {
                padding: 20px;
            }
            
            .result-header h2 {
                font-size: 1.2rem;
            }
            
            .container {
                padding: 0 15px;
            }
            
            .requests-table-card {
                padding: 15px;
                border-radius: 15px;
            }
        }
    </style>
</head>
@include('chatbot.embed')

<body>
    <!-- Navigation Header -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid nav-container">
            <!-- Logo -->
            <div class="nav-logo">
                <div class="logo"></div>
                <div class="logo-text d-none d-md-block">
                    <span class="logo-title d-block">Barangay</span>
                    <span class="logo-subtitle d-block">Hulong Duhat Portal</span>
                </div>
                <div class="logo-text d-md-none">
                    <span class="logo-subtitle">Hulong Duhat</span>
                </div>
            </div>
            
            <!-- Mobile Menu Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Main Navigation -->
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav w-100 justify-content-around mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('barangay_system.index') }}"><i class="fas fa-home"></i> Home</a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="aboutDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-info-circle"></i> About
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-link" href="{{ route('history') }}"><i class="fas fa-history"></i> History</a></li>
                            <li><a class="dropdown-link" href="{{ route('mission_vision')}}"><i class="fas fa-bullseye"></i> Mission/Vision</a></li>
                            <li><a class="dropdown-link" href="{{ route('map') }}"><i class="fas fa-map"></i> Barangay Map</a></li>
                            <li><a class="dropdown-link" href="{{ route('officials') }}"><i class="fas fa-users"></i> Barangay Officials</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-concierge-bell"></i> Services
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-link dropdown-item-custom" href="{{ route('services') }}"><i class="fas fa-list"></i> All Services</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-link" href="{{ route('clearance') }}"><i class="fas fa-certificate"></i> Barangay Clearance</a></li>
                            <li><a class="dropdown-link" href="{{ route('residency')}}"><i class="fas fa-house-user"></i> Certificate of Residency</a></li>
                            <li><a class="dropdown-link" href="{{ route('indigency') }}"><i class="fas fa-hands-helping"></i> Certificate of Indigency</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-link" href="{{ route('track_request') }}"><i class="fas fa-search"></i> Track Request</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="communityDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-users"></i> Community
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-link" href="{{ route('announcements') }}"><i class="fas fa-bullhorn"></i> Announcements</a></li>
                            <li><a class="dropdown-link" href="{{ route('events_project') }}"><i class="fas fa-calendar-alt"></i> Events/Projects</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="reportDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-exclamation-circle"></i> Report
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-link" href="{{ route('incident') }}"><i class="fas fa-clipboard-list"></i> Incident Report</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contacts') }}"><i class="fas fa-phone"></i> Contact</a>
                    </li>
                    
                    <!-- LogIn-LogOut Actions -->
                    <li class="nav-item d-none d-lg-block">
                        @auth
                            <div class="dropdown d-inline-block">
                                <button class="btn user-dropdown-btn" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle"></i>
                                    <span class="user-name">{{ Auth::user()->name ?? 'User' }}</span>
                                    <i class="fas fa-chevron-down ms-1" style="font-size: 0.8rem;"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-link" href="{{ route('profile') }}"><i class="fas fa-id-card"></i> My Profile</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout.res') }}">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            
                            <form id="logout-form" action="{{ route('logout.res') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        @else
                            <a href="{{ route('login.res') }}" class="login-btn ms-2">
                                <i class="fas fa-sign-in-alt"></i> Log In
                            </a>
                        @endauth
                    </li>

                    {{-- Mobile --}}
                    <li class="nav-item d-lg-none mt-3 pt-2 border-top">
                        @auth
                            <div class="dropdown">
                                <button class="btn btn-link nav-link dropdown-toggle w-100 text-start" type="button" id="mobileUserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle"></i> {{ Auth::user()->name ?? 'User' }}
                                </button>
                                <ul class="dropdown-menu border-0 ps-3" aria-labelledby="mobileUserDropdown">
                                    <li><a class="dropdown-link" href="{{ route('profile') }}"><i class="fas fa-id-card"></i> My Profile</a></li>
                                    <li><hr class="dropdown-divider bg-secondary"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout.res') }}">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <form id="mobile-logout-form" action="{{ route('logout.res') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        @else
                            <a href="{{ route('login.res') }}" class="nav-link">
                                <i class="fas fa-sign-in-alt"></i> Log In
                            </a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1><i class="fas fa-search-location"></i> Track Your Request</h1>
                <p>{{ __('messages.track_hero_subtitle') }}</p>
                <div class="hero-stats">
                    <div class="stat">
                        <i class="fas fa-clock"></i>
                        <span>Real-time Updates</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-bell"></i>
                        <span>Status Notifications</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-shield-alt"></i>
                        <span>Secure Tracking</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content" id="main-content">
        <!-- Track Form Section -->
        <section class="track-form-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="track-form-card">
                            <div class="form-header">
                                <div class="form-icon">
                                    <img src="{{ asset('Images/logo.jpg') }}" alt="Barangay Hulo Logo">
                                </div>
                                <h2>{{ __('messages.track_form_title') }}</h2>
                                <p>{{ __('messages.track_form_subtitle') }}</p>
                            </div>
                            
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('track_request') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="referenceNumber">
                                        <i class="fas fa-hashtag"></i> Reference Number
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" class="form-control @error('reference_number') is-invalid @enderror" 
                                            id="referenceNumber" name="reference_number" 
                                            placeholder="e.g., BC-2026-ABC123 or INC-2026-001" 
                                            value="{{ old('reference_number') }}" required>
                                        <span class="input-icon"><i class="fas fa-barcode"></i></span>
                                        @error('reference_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text">Format: BC-YYYY-XXXXX for clearance, RES-YYYY-XXXXX for residency, IND-YYYY-XXXXX for indigency, INC-YYYY-XXXXX for incidents</small>
                                </div>
                                <button type="submit" class="btn-track">
                                    <i class="fas fa-search"></i> {{ __('messages.track_form_submit_btn') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Track Result Section (shown when tracking data exists) -->
        @if(isset($trackedRequest))
        <section class="track-result-section" id="trackResult">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="result-card">
                            <div class="result-header">
                                @php
                                    $statusClass = '';
                                    $statusLower = strtolower($trackedRequest['status']);
                                    
                                    if(str_contains($statusLower, 'pending')) $statusClass = 'pending';
                                    elseif(str_contains($statusLower, 'under review')) $statusClass = 'under-review';
                                    elseif(str_contains($statusLower, 'approved')) $statusClass = 'approved';
                                    elseif(str_contains($statusLower, 'payment')) $statusClass = 'payment-required';
                                    elseif(str_contains($statusLower, 'process')) $statusClass = 'processing';
                                    elseif(str_contains($statusLower, 'ready for pickup')) $statusClass = 'ready-for-pickup';
                                    elseif(str_contains($statusLower, 'claimed') || str_contains($statusLower, 'released')) $statusClass = 'claimed';
                                    elseif(str_contains($statusLower, 'reject') || str_contains($statusLower, 'denied')) $statusClass = 'rejected';
                                    
                                    $statusIcon = 'fa-hourglass-half';
                                    if(str_contains($statusLower, 'pending')) $statusIcon = 'fa-clock';
                                    elseif(str_contains($statusLower, 'under review')) $statusIcon = 'fa-search';
                                    elseif(str_contains($statusLower, 'approved')) $statusIcon = 'fa-check-circle';
                                    elseif(str_contains($statusLower, 'payment')) $statusIcon = 'fa-credit-card';
                                    elseif(str_contains($statusLower, 'process')) $statusIcon = 'fa-cog fa-spin';
                                    elseif(str_contains($statusLower, 'ready for pickup')) $statusIcon = 'fa-store';
                                    elseif(str_contains($statusLower, 'claimed') || str_contains($statusLower, 'released')) $statusIcon = 'fa-check-double';
                                    elseif(str_contains($statusLower, 'reject') || str_contains($statusLower, 'denied')) $statusIcon = 'fa-times-circle';
                                @endphp
                                <div class="status-badge {{ $statusClass }}">
                                    <i class="fas {{ $statusIcon }}"></i> {{ $trackedRequest['status'] }}
                                </div>
                                <h2>{{ $trackedRequest['type'] }} Details</h2>
                            </div>
                            <div class="result-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <span class="label">Reference Number</span>
                                            <span class="value">{{ $trackedRequest['reference'] }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="label">Request Type</span>
                                            <span class="value">{{ $trackedRequest['type'] }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="label">Applicant Name</span>
                                            <span class="value">{{ $trackedRequest['name'] }}</span>
                                        </div>
                                        @if(isset($trackedRequest['purpose']))
                                        <div class="detail-item">
                                            <span class="label">Purpose</span>
                                            <span class="value">{{ $trackedRequest['purpose'] }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <span class="label">Date Submitted</span>
                                            <span class="value">{{ $trackedRequest['date'] }}</span>
                                        </div>
                                        @if(isset($trackedRequest['fee_label']))
                                        <div class="detail-item">
                                            <span class="label">Fee</span>
                                            <span class="value">{{ $trackedRequest['fee_label'] }}</span>
                                        </div>
                                        @endif
                                        @if(isset($trackedRequest['expected_completion']))
                                        <div class="detail-item">
                                            <span class="label">Expected Completion</span>
                                            <span class="value">{{ $trackedRequest['expected_completion'] }}</span>
                                        </div>
                                        @endif
                                        @if(isset($trackedRequest['remarks']))
                                        <div class="detail-item">
                                            <span class="label">Remarks</span>
                                            <span class="value">{{ $trackedRequest['remarks'] }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Timeline -->
                            <div class="progress-timeline">
                                <h3><i class="fas fa-tasks"></i> Request Progress</h3>
                                <div class="timeline" id="timeline">
                                    @if(isset($trackedRequest['steps']) && count($trackedRequest['steps']) > 0)
                                        @foreach($trackedRequest['steps'] as $step)
                                        <div class="timeline-item {{ $step['status'] }}">
                                            <div class="timeline-marker">
                                                @if($step['status'] == 'completed')
                                                    <i class="fas fa-check"></i>
                                                @elseif($step['status'] == 'active')
                                                    <i class="fas fa-spinner fa-spin"></i>
                                                @else
                                                    <i class="fas fa-circle"></i>
                                                @endif
                                            </div>
                                            <div class="timeline-content">
                                                <h4>{{ $step['label'] }}</h4>
                                                <p>{{ $step['description'] }}</p>
                                                @if($step['status'] == 'active')
                                                    <span class="time">Current Status</span>
                                                @elseif($step['status'] == 'completed')
                                                    <span class="time">Completed</span>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        @php
                                            $isIncident = str_contains(strtolower($trackedRequest['type']), 'incident report') || 
                                                        str_contains(strtolower($trackedRequest['type']), 'incident');
                                            
                                            if($isIncident) {
                                                $steps = [
                                                    ['label' => 'Report Submitted', 'description' => 'Your incident report has been received', 'status' => 'completed'],
                                                    ['label' => 'Report Verified', 'description' => 'Report details have been verified', 'status' => 
                                                        in_array($trackedRequest['status'], ['Under Investigation', 'Resolved', 'Closed']) ? 'completed' : 
                                                        (in_array($trackedRequest['status'], ['Processing']) ? 'active' : 'pending')],
                                                    ['label' => 'Under Investigation', 'description' => 'Case is being investigated by barangay officials', 'status' => 
                                                        in_array($trackedRequest['status'], ['Under Investigation', 'Resolved', 'Closed']) ? 'active' : 
                                                        (in_array($trackedRequest['status'], ['Resolved', 'Closed']) ? 'completed' : 'pending')],
                                                    ['label' => 'Resolved', 'description' => 'Incident report has been resolved', 'status' => 
                                                        in_array($trackedRequest['status'], ['Resolved', 'Closed']) ? 'completed' : 
                                                        (in_array($trackedRequest['status'], ['Closed']) ? 'active' : 'pending')],
                                                    ['label' => 'Closed', 'description' => 'Case has been closed', 'status' => 
                                                        in_array($trackedRequest['status'], ['Closed']) ? 'completed' : 'pending'],
                                                ];
                                            } else {
                                                $steps = [
                                                    ['label' => 'Request Submitted', 'description' => 'Your application has been received', 'status' => 'completed'],
                                                    ['label' => 'Under Review', 'description' => 'Submitted documents are being verified', 'status' => 
                                                        in_array($trackedRequest['status'], ['Under Review', 'Approved', 'Processing', 'Ready for Pickup', 'Claimed']) ? 'completed' : 
                                                        (in_array($trackedRequest['status'], ['Pending']) ? 'active' : 'pending')],
                                                    ['label' => 'Approved', 'description' => 'Request has been approved', 'status' => 
                                                        in_array($trackedRequest['status'], ['Approved', 'Processing', 'Ready for Pickup', 'Claimed']) ? 'completed' : 
                                                        (in_array($trackedRequest['status'], ['Under Review']) ? 'active' : 'pending')],
                                                    ['label' => 'Payment Required', 'description' => 'Waiting for payment confirmation', 'status' => 
                                                        in_array($trackedRequest['status'], ['Payment Required']) ? 'active' : 
                                                        (in_array($trackedRequest['status'], ['Processing', 'Ready for Pickup', 'Claimed']) ? 'completed' : 'pending')],
                                                    ['label' => 'Processing', 'description' => 'Document is being prepared', 'status' => 
                                                        in_array($trackedRequest['status'], ['Processing', 'Ready for Pickup', 'Claimed']) ? 'active' : 
                                                        (in_array($trackedRequest['status'], ['Ready for Pickup', 'Claimed']) ? 'completed' : 'pending')],
                                                    ['label' => 'Ready for Pickup', 'description' => 'Document ready at barangay hall', 'status' => 
                                                        in_array($trackedRequest['status'], ['Ready for Pickup', 'Claimed']) ? 'completed' : 
                                                        (in_array($trackedRequest['status'], ['Processing']) ? 'active' : 'pending')],
                                                    ['label' => 'Claimed', 'description' => 'Document has been claimed', 'status' => 
                                                        in_array($trackedRequest['status'], ['Claimed']) ? 'completed' : 'pending'],
                                                ];
                                            }
                                        @endphp
                                        
                                        @foreach($steps as $step)
                                        <div class="timeline-item {{ $step['status'] }}">
                                            <div class="timeline-marker">
                                                @if($step['status'] == 'completed')
                                                    <i class="fas fa-check"></i>
                                                @elseif($step['status'] == 'active')
                                                    <i class="fas fa-spinner fa-spin"></i>
                                                @else
                                                    <i class="fas fa-circle"></i>
                                                @endif
                                            </div>
                                            <div class="timeline-content">
                                                <h4>{{ $step['label'] }}</h4>
                                                <p>{{ $step['description'] }}</p>
                                                @if($step['status'] == 'active')
                                                    <span class="time">Current Status</span>
                                                @elseif($step['status'] == 'completed')
                                                    <span class="time">{{ $trackedRequest['date'] }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="result-actions">
                                <a href="{{ route('track.request.print', ['reference' => $trackedRequest['reference']]) }}" class="btn-print" target="_blank">
                                    <i class="fas fa-print"></i> Print Details
                                </a>
                                <a href="{{ route('track_request') }}" class="btn-new-track">
                                    <i class="fas fa-redo"></i> Track Another
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif

        <!-- My Requests Table Section -->
        <section class="my-requests-section" id="my-requests">
            <div class="container">
                <div class="requests-table-card">
                    <div class="table-header">
                        <h2>
                            <i class="fas fa-file-alt"></i> My Requests & Reports
                        </h2>
                        <div class="filter-tabs" id="filterTabs">
                            <button class="filter-tab active" data-filter="all">All</button>
                            <button class="filter-tab" data-filter="documents">Documents</button>
                            <button class="filter-tab" data-filter="incidents">Incidents</button>
                            <button class="filter-tab" data-filter="pending">Pending</button>
                            <button class="filter-tab" data-filter="processing">Processing</button>
                            <button class="filter-tab" data-filter="completed">Completed</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="requests-table" id="requestsTable">
                            <thead>
                                <tr>
                                    <th>Reference #</th>
                                    <th>Type</th>
                                    <th>Date Submitted</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @auth
                                    @php
                                        $allRequests = [];
                                        
                                        // Get clearance applications
                                        $clearances = App\Models\BarangayClearance::where('email', Auth::user()->email)
                                            ->orWhere('contact_number', Auth::user()->contact_number ?? '')
                                            ->get()
                                            ->map(function($item) {
                                                return [
                                                    'reference' => $item->reference_number,
                                                    'type' => 'Barangay Clearance',
                                                    'date' => $item->created_at->format('M d, Y'),
                                                    'status' => ucfirst($item->status ?? 'Pending'),
                                                    'last_updated' => $item->updated_at->format('M d, Y'),
                                                    'email' => $item->email,
                                                    'type_code' => 'clearance'
                                                ];
                                            })->toArray();
                                        
                                        // Get residency applications
                                        $residencies = App\Models\ResidencyApplication::where('email', Auth::user()->email)
                                            ->orWhere('contact_number', Auth::user()->contact_number ?? '')
                                            ->get()
                                            ->map(function($item) {
                                                return [
                                                    'reference' => $item->reference_number,
                                                    'type' => 'Certificate of Residency',
                                                    'date' => $item->created_at->format('M d, Y'),
                                                    'status' => ucfirst($item->status ?? 'Pending'),
                                                    'last_updated' => $item->updated_at->format('M d, Y'),
                                                    'email' => $item->email,
                                                    'type_code' => 'residency'
                                                ];
                                            })->toArray();
                                        
                                        // Get indigency applications
                                        $indigencies = App\Models\IndigencyApplication::where('email', Auth::user()->email)
                                            ->orWhere('contact_number', Auth::user()->contact_number ?? '')
                                            ->get()
                                            ->map(function($item) {
                                                return [
                                                    'reference' => $item->reference_number,
                                                    'type' => 'Certificate of Indigency',
                                                    'date' => $item->created_at->format('M d, Y'),
                                                    'status' => ucfirst($item->status ?? 'Pending'),
                                                    'last_updated' => $item->updated_at->format('M d, Y'),
                                                    'email' => $item->email,
                                                    'type_code' => 'indigency'
                                                ];
                                            })->toArray();
                                        
                                        // Get Incident Reports if model exists
                                        if(class_exists('App\Models\BlotterReport')) {
                                            $blotters = App\Models\BlotterReport::where('complainant_email', Auth::user()->email)
                                                ->orWhere('complainant_contact', Auth::user()->contact_number ?? '')
                                                ->get()
                                                ->map(function($item) {
                                                    return [
                                                        'reference' => $item->incident_number ?? $item->reference_number,
                                                        'type' => 'Incident Report',
                                                        'date' => $item->created_at->format('M d, Y'),
                                                        'status' => $item->status ?? 'Pending',
                                                        'last_updated' => $item->updated_at->format('M d, Y'),
                                                        'email' => $item->complainant_email,
                                                        'type_code' => 'incident'
                                                    ];
                                                })->toArray();
                                        } else {
                                            $blotters = [];
                                        }
                                        
                                        // Merge all requests
                                        $allRequests = array_merge($clearances, $residencies, $indigencies, $blotters);
                                        
                                        // Sort by date (most recent first)
                                        usort($allRequests, function($a, $b) {
                                            return strtotime($b['date']) - strtotime($a['date']);
                                        });
                                    @endphp
                                    
                                    @forelse($allRequests as $request)
                                    <tr class="request-row" 
                                        data-type="{{ $request['type_code'] ?? 'document' }}" 
                                        data-status="{{ strtolower($request['status']) }}">
                                        <td><strong>{{ $request['reference'] }}</strong></td>
                                        <td>
                                            <span class="request-type-badge">
                                                @php
                                                    $icon = 'fa-file-alt';
                                                    if(str_contains($request['type'], 'Clearance')) $icon = 'fa-certificate';
                                                    elseif(str_contains($request['type'], 'Residency')) $icon = 'fa-house-user';
                                                    elseif(str_contains($request['type'], 'Indigency')) $icon = 'fa-hands-helping';
                                                    elseif(str_contains($request['type'], 'Incident Report')) $icon = 'fa-clipboard-list';
                                                @endphp
                                                <i class="fas {{ $icon }}"></i> {{ $request['type'] }}
                                            </span>
                                        </td>
                                        <td>{{ $request['date'] }}</td>
                                        <td>
                                            @php
                                                $statusClass = '';
                                                $statusLower = strtolower($request['status']);
                                                
                                                if(str_contains($statusLower, 'pending')) {
                                                    $statusClass = 'status-pending';
                                                }
                                                elseif(str_contains($statusLower, 'under review')) {
                                                    $statusClass = 'status-under-review';
                                                }
                                                elseif(str_contains($statusLower, 'approved')) {
                                                    $statusClass = 'status-approved';
                                                }
                                                elseif(str_contains($statusLower, 'payment')) {
                                                    $statusClass = 'status-payment-required';
                                                }
                                                elseif(str_contains($statusLower, 'processing')) {
                                                    $statusClass = 'status-processing';
                                                }
                                                elseif(str_contains($statusLower, 'ready for pickup')) {
                                                    $statusClass = 'status-ready-for-pickup';
                                                }
                                                elseif(str_contains($statusLower, 'claimed') || str_contains($statusLower, 'released')) {
                                                    $statusClass = 'status-claimed';
                                                }
                                                elseif(str_contains($statusLower, 'rejected') || str_contains($statusLower, 'denied')) {
                                                    $statusClass = 'status-rejected';
                                                }
                                                else {
                                                    $statusClass = 'status-pending'; // default fallback
                                                }
                                            @endphp
                                            <span class="status-badge-small {{ $statusClass }}">
                                                {{ $request['status'] }}
                                            </span>
                                        </td>
                                        <td>{{ $request['last_updated'] }}</td>
                                        <td>
                                            <a href="{{ route('track_request', ['view' => $request['reference']]) }}" class="btn-view">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="no-requests">
                                                <i class="fas fa-inbox"></i>
                                                <p>No requests found</p>
                                                <p class="sub-text">You haven't submitted any requests yet</p>
                                                <div class="mt-3">
                                                    <a href="{{ route('clearance') }}" class="btn-track" style="width: auto; padding: 10px 20px; display: inline-block; margin-right: 10px; text-decoration: none;">
                                                        <i class="fas fa-certificate"></i> Apply for Clearance
                                                    </a>
                                                    <a href="{{ route('incident') }}" class="btn-track" style="width: auto; padding: 10px 20px; display: inline-block; background: #666; text-decoration: none;">
                                                        <i class="fas fa-exclamation-circle"></i> Report Incident
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="no-requests">
                                                <i class="fas fa-sign-in-alt"></i>
                                                <p>Please log in to view your requests</p>
                                                <p class="sub-text">Login to track all your submitted documents and incident reports</p>
                                                <a href="{{ route('login') }}" class="btn-track" style="width: auto; padding: 10px 30px; margin-top: 15px; display: inline-block; text-decoration: none;">
                                                    <i class="fas fa-sign-in-alt"></i> Log In
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endauth
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- Status Legend Section -->
        <section class="status-legend-section">
            <div class="container">
                <h2 class="section-title">Status Guide</h2>
                <p class="section-subtitle">Understanding your request and report status</p>
                
                <div class="row g-4 justify-content-center">
                    <div class="col-xl-3 col-lg-4 col-md-4 col-6">
                        <div class="status-card pending">
                            <div class="status-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h3>Pending</h3>
                            <p>Request submitted but not yet reviewed</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-4 col-6">
                        <div class="status-card under-review">
                            <div class="status-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h3>Under Review</h3>
                            <p>Barangay staff is verifying your request</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-4 col-6">
                        <div class="status-card approved">
                            <div class="status-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h3>Approved</h3>
                            <p>Request approved and ready for processing</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-4 col-6">
                        <div class="status-card payment-required">
                            <div class="status-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <h3>Payment Required</h3>
                            <p>Waiting for payment confirmation</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-4 col-6">
                        <div class="status-card processing">
                            <div class="status-icon">
                                <i class="fas fa-cog fa-spin"></i>
                            </div>
                            <h3>Processing</h3>
                            <p>Document is being prepared</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-4 col-6">
                        <div class="status-card ready-pickup">
                            <div class="status-icon">
                                <i class="fas fa-store"></i>
                            </div>
                            <h3>Ready for Pickup</h3>
                            <p>Document waiting at barangay hall</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-4 col-6">
                        <div class="status-card completed">
                            <div class="status-icon">
                                <i class="fas fa-flag-checkered"></i>
                            </div>
                            <h3>Claimed / Released</h3>
                            <p>Document has been picked up</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-4 col-6">
                        <div class="status-card rejected">
                            <div class="status-icon">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <h3>Rejected / Denied</h3>
                            <p>Request cannot be approved</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quick Links Section -->
        <section class="quick-links-section">
            <div class="container">
                <h2 class="section-title">Apply for Documents</h2>
                <p class="section-subtitle">Need to submit a new request? Choose a service below.</p>
                
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <a href="{{ route('clearance') }}" class="quick-link-card">
                            <div class="card-icon">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <h3>Barangay Clearance</h3>
                            <p>Official document certifying good moral character and residency</p>
                            <span class="link-arrow"><i class="fas fa-arrow-right"></i></span>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <a href="{{ route('residency') }}" class="quick-link-card">
                            <div class="card-icon">
                                <i class="fas fa-house-user"></i>
                            </div>
                            <h3>Certificate of Residency</h3>
                            <p>Proof of residence within the barangay jurisdiction</p>
                            <span class="link-arrow"><i class="fas fa-arrow-right"></i></span>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <a href="{{ route('indigency') }}" class="quick-link-card">
                            <div class="card-icon">
                                <i class="fas fa-hands-helping"></i>
                            </div>
                            <h3>Certificate of Indigency</h3>
                            <p>Document for financial assistance and government programs</p>
                            <span class="link-arrow"><i class="fas fa-arrow-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="faq-section">
            <div class="container">
                <h2 class="section-title">Frequently Asked Questions</h2>
                
                <div class="faq-container">
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Where can I find my reference number?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Your reference number was provided when you submitted your request. It was sent to your email and also displayed on the confirmation page. The format varies by request type: BC-YYYY-XXXXX for clearance, RES-YYYY-XXXXX for residency, IND-YYYY-XXXXX for indigency, and INC-YYYY-XXXXX for incident reports.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>How long does processing usually take?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Processing times vary by document type: Barangay Clearance (1-3 days), Certificate of Residency (1-2 days), Certificate of Indigency (1-2 days). Incident reports are typically investigated within 3-5 business days.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>What do the different statuses mean?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p><strong>Pending:</strong> Request submitted but not yet reviewed<br>
                            <strong>Under Review:</strong> Barangay staff is verifying your request details<br>
                            <strong>Approved:</strong> Request approved and can proceed to processing<br>
                            <strong>Payment Required:</strong> Waiting for payment confirmation (if applicable)<br>
                            <strong>Processing:</strong> Barangay staff is preparing the official document<br>
                            <strong>Ready for Pickup:</strong> Document completed and waiting at barangay hall<br>
                            <strong>Claimed/Released:</strong> Document has been picked up<br>
                            <strong>Rejected/Denied:</strong> Request cannot be approved (reason provided)</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>What if I lost my reference number?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>If you're logged in, you can view all your requests in the "My Requests" table above. Otherwise, visit the barangay hall with a valid ID and our staff can look up your request using your name and date of application.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@include('barangay_system.partials.fab_footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
    
    <script src="{{ asset('js/navbar.js') }}"></script>
    <script src="{{ asset('js/floating-actions.js') }}"></script>
    <script src="{{ asset('js/dark-mode.js') }}"></script>
    <script src="{{ asset('js/faq.js') }}"></script>
    
    <script>
        // Table filtering
        document.addEventListener('DOMContentLoaded', function() {
            const filterTabs = document.querySelectorAll('.filter-tab');
            const rows = document.querySelectorAll('.request-row');
            
            filterTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Update active tab
                    filterTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    const filter = this.dataset.filter;
                    
                    rows.forEach(row => {
                        const type = row.dataset.type;
                        const status = row.dataset.status;
                        
                        let show = false;
                        
                        switch(filter) {
                            case 'all':
                                show = true;
                                break;
                            case 'documents':
                                show = type !== 'incident';
                                break;
                            case 'incidents':
                                show = type === 'incident';
                                break;
                            case 'pending':
                                show = status.includes('pending');
                                break;
                            case 'processing':
                                show = status.includes('process') || status.includes('investigation') || status.includes('review');
                                break;
                            case 'completed':
                                show = status.includes('complete') || status.includes('resolved') || status.includes('closed') || status.includes('approved') || status.includes('claimed') || status.includes('released');
                                break;
                        }
                        
                        row.style.display = show ? '' : 'none';
                    });
                });
            });
            
            // Check if we should show a specific request from URL
            const urlParams = new URLSearchParams(window.location.search);
            const highlightRef = urlParams.get('highlight');
            if(highlightRef) {
                const targetRow = Array.from(rows).find(row => 
                    row.cells[0].textContent.trim() === highlightRef
                );
                if(targetRow) {
                    targetRow.style.backgroundColor = '#fff3cd';
                    setTimeout(() => {
                        targetRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 500);
                }
            }
        });
    </script>
</body>
</html>