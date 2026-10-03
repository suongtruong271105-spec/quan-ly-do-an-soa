<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quản lý sinh viên</title>
    
    <!-- Kết nối file CSS -->
    <link rel="stylesheet" href="../css/sinhvien.css">
</head>

<body>

    <div class="container">
<button class="btn-back" onclick="window.location.href='/dashboard'">
            &#8592; Quay lại
        </button>
        <h1>QUẢN LÝ SINH VIÊN</h1>

        <div class="form-container">

            <h3>Thông tin sinh viên</h3>

            <input
                type="text"
                id="ma_sv"
                placeholder="Mã sinh viên"
            >

            <input
                type="text"
                id="ho_ten"
                placeholder="Họ tên"
            >

            <input
                type="text"
                id="lop"
                placeholder="Lớp"
            >

            <button onclick="luuSinhVien()">
                Lưu
            </button>

            <button onclick="huySua()">
                Hủy
            </button>

        </div>


        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Mã SV</th>
                    <th>Họ tên</th>
                    <th>Lớp</th>
                    <th>Thao tác</th>
                </tr>
            </thead>

            <tbody id="danhSachSinhVien">
                <!-- JavaScript sẽ đưa dữ liệu vào đây -->
            </tbody>

        </table>

    </div>


    <!-- Kết nối file JavaScript -->
    <script src="/js/sinhvien.js"></script>

</body>

</html>