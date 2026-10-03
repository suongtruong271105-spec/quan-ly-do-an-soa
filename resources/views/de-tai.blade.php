<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Đề Tài</title>
    <!-- File CSS riêng của bạn -->
    <link href="detai.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Nút Quay lại (nếu cần điều hướng) -->
        <!-- <button class="btn-back" onclick="window.history.back()">← Quay lại</button> -->

        <h1>Quản lý Danh Sách Đề Tài</h1>

        <!-- Form Thêm / Sửa Đề Tài -->
        <div class="form-container">
            <h3 id="form-title">Thêm mới Đề Tài</h3>
            <input type="hidden" id="de-tai-id">
            
            <div class="input-group-wrapper">
                <input type="text" id="ma_dt" placeholder="Mã Đề Tài">
                <input type="text" id="ten_dt" placeholder="Tên Đề Tài">
                <input type="text" id="giang_vien_hd" placeholder="Giảng Viên Hướng Dẫn">
            </div>

            <div class="button-group">
                <button onclick="saveDeTai()" id="btn-save">Lưu Đề Tài</button>
                <button onclick="resetForm()" style="display:none;" id="btn-cancel">Hủy</button>
            </div>
        </div>

        <!-- Bảng hiển thị danh sách -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Mã Đề Tài</th>
                    <th>Tên Đề Tài</th>
                    <th>Giảng Viên Hướng Dẫn</th>
                    <th style="text-align: right;">Thao tác</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Dữ liệu được load bằng JavaScript -->
            </tbody>
        </table>
    </div>

    <script>
        const API_URL = '/api/de-tai';

        // 1. Load danh sách đề tài
        async function fetchDeTai() {
            try {
                const res = await fetch(API_URL);
                const result = await res.json();
                let rows = '';
                result.data.forEach(item => {
                    rows += `
                        <tr>
                            <td>${item.id}</td>
                            <td><strong>${item.ma_dt}</strong></td>
                            <td>${item.ten_dt}</td>
                            <td>${item.giang_vien_hd || ''}</td>
                            <td style="text-align: right;">
                                <button onclick="editDeTai(${item.id}, '${item.ma_dt}', '${item.ten_dt}', '${item.giang_vien_hd || ''}')">Sửa</button>
                                <button onclick="deleteDeTai(${item.id})">Xóa</button>
                            </td>
                        </tr>
                    `;
                });
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
                giang_vien_hd: document.getElementById('giang_vien_hd').value
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
                alert(result.message);
                resetForm();
                fetchDeTai();
            } else {
                alert('Lỗi: ' + JSON.stringify(result.errors || result.message));
            }
        }

        // 3. Đưa dữ liệu lên form để sửa
        function editDeTai(id, ma_dt, ten_dt, giang_vien_hd) {
            document.getElementById('de-tai-id').value = id;
            document.getElementById('ma_dt').value = ma_dt;
            document.getElementById('ten_dt').value = ten_dt;
            document.getElementById('giang_vien_hd').value = giang_vien_hd;
            document.getElementById('form-title').innerText = 'Cập nhật Đề Tài';
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
            document.getElementById('form-title').innerText = 'Thêm mới Đề Tài';
            document.getElementById('btn-cancel').style.display = 'none';
            document.getElementById('btn-save').innerText = 'Lưu Đề Tài';
        }

        // Khởi chạy load dữ liệu khi mở trang
        fetchDeTai();
    </script>
</body>
</html>