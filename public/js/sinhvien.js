const API_URL = "/api/sinhvien";

// null nghĩa là hiện tại đang thêm mới
// có giá trị nghĩa là hiện tại đang sửa (chứa chuỗi masv)
let editingMaSV = null;

// ================================
// GET: Lấy danh sách sinh viên
// ================================
async function taiDanhSach() {
    const response = await fetch(API_URL);
    const sinhViens = await response.json();
    const tbody = document.getElementById("danhSachSinhVien");
    
    tbody.innerHTML = "";

    sinhViens.forEach(sv => {
        // Chú ý: Bắt buộc dùng dấu nháy đơn bao quanh ${sv.masv} vì nó là chuỗi
        tbody.innerHTML += `
            <tr>
                <td>${sv.masv}</td>
                <td>${sv.hoten}</td>
                <td>${sv.lop}</td>
                <td>
                    <button onclick="suaSinhVien('${sv.masv}')">
                        Sửa
                    </button>
                    <button onclick="xoaSinhVien('${sv.masv}')">
                        Xóa
                    </button>
                </td>
            </tr>
        `;
    });
}

// ================================
// POST / PUT: Lưu thông tin
// ================================
async function luuSinhVien() {
    const masv = document.getElementById("masv").value;
    const hoten = document.getElementById("hoten").value;
    const lop = document.getElementById("lop").value;

    // Kiểm tra dữ liệu
    if (!masv || !hoten || !lop) {
        alert("Vui lòng nhập đầy đủ thông tin");
        return;
    }

    const data = {
        masv: masv,
        hoten: hoten,
        lop: lop
    };

    // THÊM SINH VIÊN
    if (editingMaSV === null) {
        await fetch(API_URL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify(data)
        });
    } 
    // SỬA SINH VIÊN
    else {
        await fetch(`${API_URL}/${editingMaSV}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify(data)
        });
    }

    huySua();
    await taiDanhSach();
}

// ================================
// GET: Lấy một sinh viên để sửa
// ================================
async function suaSinhVien(masv) {
    const response = await fetch(`${API_URL}/${masv}`);
    const sv = await response.json();

    document.getElementById("masv").value = sv.masv;
    document.getElementById("hoten").value = sv.hoten;
    document.getElementById("lop").value = sv.lop;

    // Lưu lại mã SV đang sửa
    editingMaSV = masv;

    // KHÓA ô Mã sinh viên lại vì đây là khóa chính, không được phép sửa
    document.getElementById("masv").readOnly = true;
    document.getElementById("masv").style.backgroundColor = "#e9ecef";
}

// ================================
// DELETE: Xóa sinh viên
// ================================
async function xoaSinhVien(masv) {
    const dongY = confirm(`Bạn có chắc muốn xóa sinh viên mã ${masv} này?`);
    if (!dongY) {
        return;
    }

    await fetch(`${API_URL}/${masv}`, {
        method: "DELETE",
        headers: {
            "Accept": "application/json"
        }
    });

    await taiDanhSach();
}

// ================================
// Hủy sửa và làm trống form
// ================================
function huySua() {
    editingMaSV = null;
    document.getElementById("masv").value = "";
    document.getElementById("hoten").value = "";
    document.getElementById("lop").value = "";

    // MỞ KHÓA lại ô Mã sinh viên để có thể thêm mới
    document.getElementById("masv").readOnly = false;
    document.getElementById("masv").style.backgroundColor = "";
}

// ================================
// Khi mở trang → tải danh sách
// ================================
taiDanhSach();