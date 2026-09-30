<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cổng Thông Tin Đồ Án</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --bg-main: #f3f4f6;
            --blue-dark: #0f172a; 
            --blue-navy: #1e3a8a; 
            --text-muted: #64748b;
        }
        
        body { 
            background-color: var(--bg-main); 
            color: #334155;
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* Navbar Tối màu chuyên nghiệp */
        .navbar-custom {
            background-color: var(--blue-dark);
            border-bottom: 1px solid rgba(255,255,255,0.05);
            padding: 0.8rem 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar-custom .nav-link {
            color: #94a3b8;
            font-weight: 500;
            padding: 0.5rem 1.2rem;
            margin-left: 0.5rem;
            border-radius: 6px;
            transition: 0.3s;
        }
        .navbar-custom .nav-link:hover, .navbar-custom .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }

        /* Header xanh trầm */
        .header-section {
            background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue-navy) 100%);
            color: white;
            padding: 2.5rem 0 4.5rem 0;
            border-bottom: 4px solid #3b82f6;
        }

        .dashboard-content { 
            margin-top: -3rem; 
            position: relative;
            z-index: 10;
        }
        
        /* Card thống kê */
        .stat-card { 
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            align-items: center;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.08); }
        
        .icon-circle {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 1.25rem;
            flex-shrink: 0;
        }
        .bg-blue-light { background-color: #eff6ff; color: #2563eb; }
        .bg-teal-light { background-color: #f0fdf4; color: #16a34a; }
        .bg-orange-light { background-color: #fff7ed; color: #ea580c; }
        .bg-purple-light { background-color: #faf5ff; color: #9333ea; }

        .stat-info { display: flex; flex-direction: column; }
        .stat-label { color: var(--text-muted); font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-value { font-size: 1.5rem; font-weight: 700; color: #1e293b; line-height: 1.2; margin-top: 0.2rem; }

        /* Bảng dữ liệu */
        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            margin-top: 2rem; /* Tạo khoảng cách đẹp với hàng thống kê */
        }
        .table-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #e2e8f0; background-color: #ffffff; }
        .table thead th { 
            font-weight: 600; color: var(--text-muted); padding: 1rem 1.5rem;
            background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; font-size: 0.85rem; text-transform: uppercase;
        }
        .table tbody td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; color: #475569; }
        .table tbody tr:hover { background-color: #f8fafc; }
        .topic-text { color: var(--blue-navy); font-weight: 500; }
        
        .btn-sync {
            background-color: rgba(255,255,255,0.15);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            font-weight: 500;
            transition: 0.3s;
        }
        .btn-sync:hover { background-color: white; color: var(--blue-navy); }
    </style>
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
            <!-- Đẩy các menu sang góc phải bằng ms-auto -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="/dashboard"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/sinhvien"><i class="bi bi-person-lines-fill me-1"></i> Quản lý Sinh Viên</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/de-tai"><i class="bi bi-journal-text me-1"></i> Quản lý Đề Tài</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/dang-ky"><i class="bi bi-check2-square me-1"></i> Đăng Ký & Chấm Điểm</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="header-section">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-1">Cổng Thông Tin Đồ Án </h3>
            <p class="mb-0 opacity-75 small">Bảng điều khiển tổng hợp dữ liệu </p>
        </div>
        <button class="btn btn-sync px-3 py-2 rounded-2" onclick="loadDashboardData()">
            <i class="bi bi-arrow-clockwise me-2"></i>Làm mới bảng
        </button>
    </div>
</div>

<div class="container dashboard-content mb-5">
    <!-- Hàng thống kê -->
    <div class="row g-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="icon-circle bg-blue-light"><i class="bi bi-people-fill"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Tổng Sinh Viên</span>
                    <span class="stat-value" id="stat-sv">0</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="icon-circle bg-teal-light"><i class="bi bi-journal-bookmark-fill"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Tổng Đề Tài</span>
                    <span class="stat-value" id="stat-dt">0</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="icon-circle bg-orange-light"><i class="bi bi-ui-checks"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Lượt Đăng Ký</span>
                    <span class="stat-value" id="stat-dk">0</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="icon-circle bg-purple-light"><i class="bi bi-award-fill"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Đã Có Điểm</span>
                    <span class="stat-value" id="stat-cham-diem">0</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng dữ liệu -->
    <div class="table-card">
        <div class="table-header d-flex align-items-center">
            <i class="bi bi-table text-primary me-2 fs-5"></i>
            <h6 class="fw-bold text-dark mb-0">DANH SÁCH BÁO CÁO CHI TIẾT</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center" width="5%">STT</th>
                        <th width="12%">Mã SV</th>
                        <th width="20%">Họ và Tên</th>
                        <th width="10%">Lớp</th>
                        <th width="33%">Tên Đề Tài</th>
                        <th width="15%">Giảng Viên HD</th>
                        <th class="text-center" width="5%">Điểm</th>
                    </tr>
                </thead>
                <tbody id="reportTableBody">
                    <tr><td colspan="7" class="text-center py-5 text-muted">Đang tải dữ liệu từ máy chủ API...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    async function loadDashboardData() {
        const tableBody = document.getElementById('reportTableBody');
        try {
            const response = await fetch('/api/dashboard/bao-cao');
            const result = await response.json();

            if (result.status === 'success') {
                document.getElementById('stat-sv').textContent = result.summary.tong_sinh_vien;
                document.getElementById('stat-dt').textContent = result.summary.tong_de_tai;
                document.getElementById('stat-dk').textContent = result.summary.tong_dang_ky;
                document.getElementById('stat-cham-diem').textContent = result.summary.da_cham_diem;

                if (result.data.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-5 text-muted">Chưa có sinh viên nào đăng ký đề tài.</td></tr>';
                    return;
                }

                let html = '';
                result.data.forEach((row, index) => {
                    let diemBadge = '<span class="badge bg-light text-secondary border px-2 py-1">Chưa chấm</span>';
                    if (row.diem !== null) {
                        const diem = parseFloat(row.diem);
                        if (diem >= 8.5) diemBadge = `<span class="badge bg-success px-2 py-1 fs-6">${diem.toFixed(1)}</span>`;
                        else if (diem >= 7.0) diemBadge = `<span class="badge bg-primary px-2 py-1 fs-6">${diem.toFixed(1)}</span>`;
                        else diemBadge = `<span class="badge bg-warning text-dark px-2 py-1 fs-6">${diem.toFixed(1)}</span>`;
                    }

                    html += `
                        <tr>
                            <td class="text-center text-muted">${index + 1}</td>
                            <td class="font-monospace text-secondary">${row.ma_sv}</td>
                            <td class="fw-bold text-dark">${row.ten_sinh_vien}</td>
                            <td><span class="badge bg-light text-dark border px-2">${row.lop}</span></td>
                            <td class="topic-text">${row.ten_de_tai}</td>
                            <td>${row.giang_vien_huong_dan}</td>
                            <td class="text-center">${diemBadge}</td>
                        </tr>
                    `;
                });
                tableBody.innerHTML = html;
            }
        } catch (error) {
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-5 text-danger fw-bold">Lỗi kết nối đến máy chủ API!</td></tr>';
        }
    }

    document.addEventListener('DOMContentLoaded', loadDashboardData);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>