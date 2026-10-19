<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Đề Tài</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/dangky.css') }}">
</head>
<body>

<!-- Thanh Điều Hướng (Navbar) -->
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-white tracking-tight" href="/dashboard">
            <i class="bi bi-hdd-network text-primary me-2"></i>SOA_PROJECT
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/dashboard"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/sinhvien"><i class="bi bi-person-lines-fill me-1"></i> Quản lý Sinh Viên</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/de-tai"><i class="bi bi-journal-text me-1"></i> Quản lý Đề Tài</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="/dang-ky"><i class="bi bi-check2-square me-1"></i> Đăng Ký Đề Tài</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Header Section -->
<div class="header-section">
    <div class="container">
        <h3 class="fw-bold mb-1"><i class="bi bi-pencil-square me-2"></i>Quản Lý Đăng Ký Đề Tài</h3>
        <p class="mb-0 opacity-75 small">Thực hiện đăng ký đề tài đồ án cho sinh viên</p>
    </div>
</div>

<!-- Main Content -->
<div class="container dashboard-content mb-5">
    
    <!-- Thông báo Trạng thái (Alert UI) -->
    <div id="alertBox" class="alert alert-dismissible fade show d-none mb-4 shadow-sm" role="alert">
        <span id="alertMessage"></span>
        <button type="button" class="btn-close" onclick="hideAlert()"></button>
    </div>

    <div class="row g-4 justify-content-center">
        <!-- Form 1: Đăng Ký Đề Tài -->
        <div class="col-12 col-md-8 col-lg-6">
            <div class="form-card">
                <div class="form-card-header">
                    <i class="bi bi-journal-plus text-primary fs-5 me-2"></i>
                    <h6 class="fw-bold text-dark mb-0">1. ĐĂNG KÝ ĐỀ TÀI</h6>
                </div>
                <div class="form-card-body">
                    <form id="formDangKy">
                <div class="mb-3">
                    <label for="dk_masv" class="form-label text-secondary small fw-semibold">Sinh Viên</label>
                    <!-- Đổi id thành dk_masv -->
                    <select id="dk_masv" class="form-select form-input-custom" required>
                        <option value="">-- Đang tải danh sách sinh viên... --</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="dk_madt" class="form-label text-secondary small fw-semibold">Đề Tài Đồ Án</label>
                    <!-- Đổi id thành dk_madt -->
                    <select id="dk_madt" class="form-select form-input-custom" required>
                        <option value="">-- Đang tải danh sách đề tài... --</option>
                    </select>
                </div>
                <button type="submit" id="btnDangKy" class="btn btn-primary-custom w-100">
                    <i class="bi bi-check-circle me-1"></i> Xác Nhận Đăng Ký
                </button>
            </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/dangky.js') }}"></script>
</body>
</html>