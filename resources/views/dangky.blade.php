<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký & Chấm Điểm</title>

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
                    <a class="nav-link active" href="/dang-ky"><i class="bi bi-check2-square me-1"></i> Đăng Ký & Chấm Điểm</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Header Section -->
<div class="header-section">
    <div class="container">
        <h3 class="fw-bold mb-1"><i class="bi bi-pencil-square me-2"></i>Quản Lý Đăng Ký & Chấm Điểm</h3>
        <p class="mb-0 opacity-75 small">Thực hiện đăng ký đề tài đồ án và nhập điểm xếp loại cho sinh viên</p>
    </div>
</div>

<!-- Main Content -->
<div class="container dashboard-content mb-5">
    <div class="row g-4">
        <!-- Form 1: Đăng Ký Đề Tài -->
        <div class="col-md-6">
            <div class="form-card">
                <div class="form-card-header">
                    <i class="bi bi-journal-plus text-primary fs-5 me-2"></i>
                    <h6 class="fw-bold text-dark mb-0">1. ĐĂNG KÝ ĐỀ TÀI</h6>
                </div>
                <div class="form-card-body">
                    <form id="formDangKy">
                        <div class="mb-3">
                            <label for="dk_sinhvien_id" class="form-label text-secondary small fw-semibold">ID Sinh viên</label>
                            <input type="number" id="dk_sinhvien_id" class="form-input-custom" placeholder="Nhập ID sinh viên (VD: 1)" required>
                        </div>
                        <div class="mb-4">
                            <label for="dk_detai_id" class="form-label text-secondary small fw-semibold">ID Đề tài</label>
                            <input type="number" id="dk_detai_id" class="form-input-custom" placeholder="Nhập ID đề tài (VD: 1)" required>
                        </div>
                        <button type="submit" id="btnDangKy" class="btn btn-primary-custom w-100">
                            <i class="bi bi-check-circle me-1"></i> Xác Nhận Đăng Ký
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Form 2: Chấm Điểm Đồ Án -->
        <div class="col-md-6">
            <div class="form-card">
                <div class="form-card-header">
                    <i class="bi bi-award text-warning fs-5 me-2"></i>
                    <h6 class="fw-bold text-dark mb-0">2. CHẤM ĐIỂM ĐỒ ÁN</h6>
                </div>
                <div class="form-card-body">
                    <form id="formNhapDiem">
                        <div class="mb-3">
                            <label for="nd_dang_ky_id" class="form-label text-secondary small fw-semibold">ID Đăng ký (dang_ky_id)</label>
                            <input type="number" id="nd_dang_ky_id" class="form-input-custom" placeholder="Nhập ID lượt đăng ký (VD: 1)" required>
                        </div>
                        <div class="mb-4">
                            <label for="nd_diem" class="form-label text-secondary small fw-semibold">Điểm số (Thang 10)</label>
                            <input type="number" step="0.1" min="0" max="10" id="nd_diem" class="form-input-custom" placeholder="VD: 8.5" required>
                        </div>
                        <button type="submit" id="btnNhapDiem" class="btn btn-warning-custom w-100">
                            <i class="bi bi-star me-1"></i> Cập Nhật Điểm
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Console Log Trả Về Từ API để Test-->
    <div class="console-card">
        <div class="console-header">
            <span class="console-title"><i class="bi bi-terminal me-1"></i> Response Console (API Return Value)</span>
            <button onclick="clearLog()" class="btn btn-sm btn-outline-light py-0 px-2" style="font-size: 0.75rem;">Clear</button>
        </div>
        <div class="console-body">
            <pre id="outputLog" class="console-output text-success-custom">Kết quả gọi API sẽ hiển thị ở đây...</pre>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/dangky.js') }}"></script>
</body>
</html>