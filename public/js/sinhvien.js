const API_URL = "/api/sinhvien";

// null nghĩa là hiện tại đang thêm mới
// có ID nghĩa là hiện tại đang sửa
let editingId = null;


// ================================
// GET: Lấy danh sách sinh viên
// ================================

async function taiDanhSach() {

    const response = await fetch(API_URL);

    const sinhViens = await response.json();

    const tbody = document.getElementById("danhSachSinhVien");

    tbody.innerHTML = "";

    sinhViens.forEach(sv => {

        tbody.innerHTML += `
            <tr>
                <td>${sv.id}</td>
                <td>${sv.ma_sv}</td>
                <td>${sv.ho_ten}</td>
                <td>${sv.lop}</td>

                <td>
                    <button onclick="suaSinhVien(${sv.id})">
                        Sửa
                    </button>

                    <button onclick="xoaSinhVien(${sv.id})">
                        Xóa
                    </button>
                </td>
            </tr>
        `;
    });
}


// ================================
// POST / PUT
// ================================

async function luuSinhVien() {

    const ma_sv = document.getElementById("ma_sv").value;
    const ho_ten = document.getElementById("ho_ten").value;
    const lop = document.getElementById("lop").value;


    // Kiểm tra dữ liệu
    if (!ma_sv || !ho_ten || !lop) {

        alert("Vui lòng nhập đầy đủ thông tin");

        return;
    }


    const data = {
        ma_sv: ma_sv,
        ho_ten: ho_ten,
        lop: lop
    };


    // THÊM SINH VIÊN
    if (editingId === null) {

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

        await fetch(`${API_URL}/${editingId}`, {

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

async function suaSinhVien(id) {

    const response = await fetch(`${API_URL}/${id}`);

    const sv = await response.json();


    document.getElementById("ma_sv").value = sv.ma_sv;

    document.getElementById("ho_ten").value = sv.ho_ten;

    document.getElementById("lop").value = sv.lop;


    editingId = id;
}


// ================================
// DELETE: Xóa sinh viên
// ================================

async function xoaSinhVien(id) {

    const dongY = confirm("Bạn có chắc muốn xóa sinh viên này?");

    if (!dongY) {
        return;
    }


    await fetch(`${API_URL}/${id}`, {

        method: "DELETE",

        headers: {
            "Accept": "application/json"
        }

    });


    await taiDanhSach();
}


// ================================
// Hủy sửa và xóa dữ liệu form
// ================================

function huySua() {

    editingId = null;

    document.getElementById("ma_sv").value = "";

    document.getElementById("ho_ten").value = "";

    document.getElementById("lop").value = "";
}


// ================================
// Khi mở trang → tải danh sách
// ================================

taiDanhSach();