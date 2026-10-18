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
            <button class="btn-back" onclick="window.location.href='/dashboard'">← Quay lại</button>
        </div>
    </header>

    <!-- 3. Nội dung chính -->
    <main class="main-container">
        <!-- Form Thêm / Sửa -->
        <div class="card">
            <div class="card-title" id="form-title">
                <span>➕</span> Thêm mới Đề Tài
            </div>
            
            <!-- Đổi id ẩn thành editing_madt -->
            <input type="hidden" id="editing_madt">
            
            <div class="input-group-wrapper">
                <!-- Đồng bộ id cho khớp với Controller: madt, tendt, giangvien -->
                <input type="text" id="madt" placeholder="Mã Đề Tài (VD: DT01)">
                <input type="text" id="tendt" placeholder="Tên Đề Tài đồ án...">
                <input type="text" id="giangvien" placeholder="Giảng Viên Hướng Dẫn">
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
                        <!-- Đã xóa cột ID -->
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
                        // Chú ý: Dùng dấu nháy đơn '${item.madt}' cho tham số kiểu chuỗi
                        rows += `
                            <tr>
                                <td><strong style="color: #2563eb;">${item.madt}</strong></td>
                                <td><strong>${item.tendt}</strong></td>
                                <td>${item.giangvien || '---'}</td>
                                <td style="text-align: right;">
                                    <button class="btn-edit" onclick="editDeTai('${item.madt}', '${item.tendt}', '${item.giangvien || ''}')">Sửa</button>
                                    <button class="btn-delete" onclick="deleteDeTai('${item.madt}')">Xóa</button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    rows = `<tr><td colspan="4" style="text-align: center; color: #94a3b8; padding: 30px;">Chưa có dữ liệu đề tài nào.</td></tr>`;
                }
                document.getElementById('table-body').innerHTML = rows;
            } catch (error) {
                console.error('Lỗi tải dữ liệu:', error);
            }
        }

        // 2. Thêm mới hoặc Cập nhật
        async function saveDeTai() {
            const editingMaDT = document.getElementById('editing_madt').value;
            const data = {
                madt: document.getElementById('madt').value,
                tendt: document.getElementById('tendt').value,
                giangvien: document.getElementById('giangvien').value 
            };

            if (!data.madt || !data.tendt) {
                alert("Mã đề tài và tên đề tài không được để trống!");
                return;
            }

            let url = API_URL;
            let method = 'POST';

            if (editingMaDT) {
                url = `${API_URL}/${editingMaDT}`;
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
        function editDeTai(madt, tendt, giangvien) {
            document.getElementById('editing_madt').value = madt;
            
            document.getElementById('madt').value = madt;
            document.getElementById('tendt').value = tendt;
            document.getElementById('giangvien').value = giangvien;
            
            document.getElementById('form-title').innerHTML = '<span>✏️</span> Cập nhật Đề Tài';
            document.getElementById('btn-cancel').style.display = 'inline-block';
            document.getElementById('btn-save').innerText = 'Cập Nhật';

            // Khóa ô Mã đề tài vì nó là khóa chính, không được phép sửa đổi
            document.getElementById('madt').readOnly = true;
            document.getElementById('madt').style.backgroundColor = '#e9ecef';
        }

        // 4. Xóa đề tài
        async function deleteDeTai(madt) {
            if (confirm(`Bạn có chắc chắn muốn xóa đề tài ${madt} này không?`)) {
                const res = await fetch(`${API_URL}/${madt}`, { method: 'DELETE' });
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
            document.getElementById('editing_madt').value = '';
            
            document.getElementById('madt').value = '';
            document.getElementById('tendt').value = '';
            document.getElementById('giangvien').value = '';
            
            document.getElementById('form-title').innerHTML = '<span>➕</span> Thêm mới Đề Tài';
            document.getElementById('btn-cancel').style.display = 'none';
            document.getElementById('btn-save').innerText = 'Lưu Đề Tài';

            // Mở khóa lại ô Mã đề tài cho chức năng thêm mới
            document.getElementById('madt').readOnly = false;
            document.getElementById('madt').style.backgroundColor = '';
        }

        // Khởi chạy load dữ liệu khi mở trang
        fetchDeTai();
    </script>
</body>
</html>