<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đề Tài - SOA_PROJECT</title>
    <!-- File CSS -->
    <link href="{{ asset('css/detai.css') }}" rel="stylesheet">
</head>
<body>

  
    <!-- 2. Hero Banner Header -->
    <header class="hero-banner">
        <div class="hero-content">
            <div class="hero-title">
                <h1>Quản Lý Danh Sách Đề Tài</h1>
                <p>Thêm mới, cập nhật thông tin và quản lý danh sách đề tài đồ án</p>
            </div>
            <button class="btn-back" onclick="window.history.back()">← Quay lại</button>
        </div>
    </header>

    <!-- 3. Nội dung chính -->
    <main class="main-container">
        <!-- Form Thêm / Sửa -->
        <div class="card">
            <div class="card-title" id="form-title">
                <span>➕</span> Thêm mới Đề Tài
            </div>
            <input type="hidden" id="de-tai-id">
            
            <div class="input-group-wrapper">
                <input type="text" id="ma_dt" placeholder="Mã Đề Tài (VD: DT01)">
                <input type="text" id="ten_dt" placeholder="Tên Đề Tài đồ án...">
                <input type="text" id="giang_vien_hd" placeholder="Giảng Viên Hướng Dẫn">
            </div>

            <div class="button-group">
                <button onclick="saveDeTai()" id="btn-save">Lưu Đề Tài</button>
                <button onclick="resetForm()" style="display:none;" id="btn-cancel">Hủy</button>
            </div>
        </div>

        <!-- Bảng hiển thị danh sách -->
        <div class="card">
            <div class="card-title">
                <span>📊</span> DANH SÁCH ĐỀ TÀI CÓ TRÊN HỆ THỐNG
            </div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th style="width: 140px;">Mã Đề Tài</th>
                        <th>Tên Đề Tài</th>
                        <th>Giảng Viên HD</th>
                        <th style="text-align: right; width: 160px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <!-- Dữ liệu load qua JS -->
                </tbody>
            </table>
        </div>
    </main>

    <!-- 4. JavaScript -->
    <script>
        const API_URL = '/api/de-tai';

        // 1. Load danh sách đề tài
        async function fetchDeTai() {
            try {
                const res = await fetch(API_URL);
                const result = await res.json();
                let rows = '';
                
                if (result.data && result.data.length > 0) {
                    result.data.forEach(item => {
                        rows += `
                            <tr>
                                <td>${item.id}</td>
                                <td><strong style="color: #2563eb;">${item.ma_dt}</strong></td>
                                <td><strong>${item.ten_dt}</strong></td>
                                <td>${item.giang_vien_huong_dan || '---'}</td>
                                <td style="text-align: right;">
                                    <button class="btn-edit" onclick="editDeTai(${item.id}, '${item.ma_dt}', '${item.ten_dt}', '${item.giang_vien_huong_dan || ''}')">Sửa</button>
                                    <button class="btn-delete" onclick="deleteDeTai(${item.id})">Xóa</button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    rows = `<tr><td colspan="5" style="text-align: center; color: #94a3b8; padding: 30px;">Chưa có dữ liệu đề tài nào.</td></tr>`;
                }
                document.getElementById('table-body').innerHTML = rows;
            } catch (error) {
                console.error('Lỗi tải dữ liệu:', error);
            }
        }

        // 2. Thêm mới hoặc Cập nhật
        async function saveDeTai() {
            const id = document.getElementById('de-tai-id').value;
            const data = {
                ma_dt: document.getElementById('ma_dt').value,
                ten_dt: document.getElementById('ten_dt').value,
                giang_vien_huong_dan: document.getElementById('giang_vien_hd').value 
            };

            let url = API_URL;
            let method = 'POST';

            if (id) {
                url = `${API_URL}/${id}`;
                method = 'PUT';
            }

            const res = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await res.json();
            if (res.ok) {
                alert(result.message || 'Lưu thành công!');
                resetForm();
                fetchDeTai();
            } else {
                alert('Lỗi: ' + JSON.stringify(result.errors || result.message));
            }
        }

        // 3. Đưa dữ liệu lên form để sửa
        function editDeTai(id, ma_dt, ten_dt, giang_vien_huong_dan) {
            document.getElementById('de-tai-id').value = id;
            document.getElementById('ma_dt').value = ma_dt;
            document.getElementById('ten_dt').value = ten_dt;
            document.getElementById('giang_vien_hd').value = giang_vien_huong_dan;
            document.getElementById('form-title').innerHTML = '<span>✏️</span> Cập nhật Đề Tài';
            document.getElementById('btn-cancel').style.display = 'inline-block';
            document.getElementById('btn-save').innerText = 'Cập Nhật';
        }

        // 4. Xóa đề tài
        async function deleteDeTai(id) {
            if (confirm('Bạn có chắc chắn muốn xóa đề tài này không?')) {
                const res = await fetch(`${API_URL}/${id}`, { method: 'DELETE' });
                if (res.ok) {
                    alert('Xóa thành công!');
                    fetchDeTai();
                } else {
                    alert('Xóa thất bại!');
                }
            }
        }

        // 5. Reset form
        function resetForm() {
            document.getElementById('de-tai-id').value = '';
            document.getElementById('ma_dt').value = '';
            document.getElementById('ten_dt').value = '';
            document.getElementById('giang_vien_hd').value = '';
            document.getElementById('form-title').innerHTML = '<span>➕</span> Thêm mới Đề Tài';
            document.getElementById('btn-cancel').style.display = 'none';
            document.getElementById('btn-save').innerText = 'Lưu Đề Tài';
        }

        // Khởi chạy load dữ liệu khi mở trang
        fetchDeTai();
    </script>
</body>
</html>