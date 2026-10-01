const API_BASE = '/api';

const sleep = (ms) => new Promise(resolve => setTimeout(resolve, ms));

// Hàm hiển thị thông báo Alert trên UI
function showAlert(message, isError = false) {
    const alertBox = document.getElementById('alertBox');
    const alertMessage = document.getElementById('alertMessage');
    if (!alertBox || !alertMessage) return;

    // Đổi màu sắc Alert tùy thuộc vào thành công hay thất bại
    alertBox.className = `alert alert-dismissible fade show mb-4 shadow-sm ${isError ? 'alert-danger' : 'alert-success'}`;
    alertMessage.innerHTML = `<i class="bi ${isError ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill'} me-2"></i> ${message}`;

    // Hiện thông báo
    alertBox.classList.remove('d-none');

    // Tự động ẩn thông báo sau 4 giây
    clearTimeout(window.alertTimeout);
    window.alertTimeout = setTimeout(() => {
        hideAlert();
    }, 3000);
}

function hideAlert() {
    const alertBox = document.getElementById('alertBox');
    if (alertBox) alertBox.classList.add('d-none');
}

function setLoading(buttonId, isLoading, originalText) {
    const btn = document.getElementById(buttonId);
    if (!btn) return;
    if (isLoading) {
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status"></span> Đang xử lý...`;
        btn.style.opacity = '0.75';
    } else {
        btn.disabled = false;
        btn.innerHTML = originalText;
        btn.style.opacity = '1';
    }
}

// Nạp data Sinh viên, Đề tài và Đăng ký vào các thẻ Select
async function loadDropdowns() {
    let registeredSvIds = [];

    // 0. Lấy danh sách các SV ĐÃ ĐĂNG KÝ từ báo cáo/dashboard
    try {
        const resBC = await fetch(`${API_BASE}/dashboard/bao-cao`);
        const resultBC = await resBC.json();
        const listBC = resultBC.data || (Array.isArray(resultBC) ? resultBC : []);
        // Gom các sinhvien_id đã có trong bảng đăng ký
        registeredSvIds = listBC.map(item => item.sinhvien_id || item.sv_id || item.id_sinhvien);
    } catch (e) {
        console.error('Không thể lấy danh sách báo cáo để lọc SV', e);
    }

    // 1. Tải danh sách Sinh viên (CHƯA ĐĂNG KÝ)
    try {
        const res = await fetch(`${API_BASE}/sinhvien`);
        const result = await res.json();
        const svSelect = document.getElementById('dk_sinhvien_id');
        let list = Array.isArray(result) ? result : (result.data || []);

        // LỌC: Chỉ giữ lại các Sinh viên CHƯA ĐĂNG KÝ đề tài nào
        list = list.filter(sv => !registeredSvIds.includes(sv.id));

        if (list.length > 0) {
            svSelect.innerHTML = '<option value="">-- Chọn sinh viên --</option>' +
                list.map(sv => {
                    const id = sv.id;
                    const code = sv.ma_sv || id;
                    const name = sv.ho_ten || sv.ten_sinh_vien || 'N/A';
                    return `<option value="${id}">${code} - ${name}</option>`;
                }).join('');
        } else {
            svSelect.innerHTML = '<option value="">Tất cả sinh viên đã đăng ký đề tài!</option>';
        }
    } catch (e) {
        document.getElementById('dk_sinhvien_id').innerHTML = '<option value="">Lỗi nạp danh sách sinh viên</option>';
    }

    // 2. Tải danh sách Đề tài
    try {
        const res = await fetch(`${API_BASE}/de-tai`);
        const result = await res.json();
        const dtSelect = document.getElementById('dk_detai_id');
        const list = Array.isArray(result) ? result : (result.data || []);

        if (list.length > 0) {
            dtSelect.innerHTML = '<option value="">-- Chọn đề tài --</option>' +
                list.map(dt => {
                    const id = dt.id;
                    const code = dt.ma_dt ? `[${dt.ma_dt}] ` : '';
                    const title = dt.ten_dt || dt.ten_de_tai || 'N/A';
                    return `<option value="${id}">${code}${title}</option>`;
                }).join('');
        } else {
            dtSelect.innerHTML = '<option value="">Chưa có đề tài trong hệ thống</option>';
        }
    } catch (e) {
        document.getElementById('dk_detai_id').innerHTML = '<option value="">Lỗi nạp danh sách đề tài</option>';
    }

    // 3. Tải danh sách lượt Đăng ký CHƯA CÓ ĐIỂM (Phục vụ Chấm điểm)
    try {
        const res = await fetch(`${API_BASE}/dashboard/bao-cao`);
        const result = await res.json();
        const dkSelect = document.getElementById('nd_dang_ky_id');
        let list = result.data || (Array.isArray(result) ? result : []);

        // LỌC: Chỉ giữ lại các lượt đăng ký CHƯA CÓ ĐIỂM
        list = list.filter(dk => dk.diem === null || dk.diem === undefined || dk.diem === '');

        if (list.length > 0) {
            dkSelect.innerHTML = '<option value="">-- Chọn SV & Đề tài cần chấm điểm --</option>' +
                list.map(dk => {
                    const id = dk.dang_ky_id || dk.dangky_id || dk.id;
                    const svName = dk.ho_ten || dk.ten_sinh_vien || dk.ma_sv || 'SV';
                    const dtName = dk.ten_dt || dk.ten_de_tai || dk.ma_dt || 'Đề tài';
                    return `<option value="${id}">${svName} - ${dtName}</option>`;
                }).join('');
        } else {
            dkSelect.innerHTML = '<option value="">Tất cả sinh viên đã được chấm điểm!</option>';
        }
    } catch (e) {
        document.getElementById('nd_dang_ky_id').innerHTML = '<option value="">Lỗi nạp danh sách lượt đăng ký</option>';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadDropdowns();

    // 1. Thao tác Đăng Ký Đề Tài (POST /api/dang-ky-de-tai)
    const formDangKy = document.getElementById('formDangKy');
    if (formDangKy) {
        formDangKy.addEventListener('submit', async (e) => {
            e.preventDefault();
            const originalText = `<i class="bi bi-check-circle me-1"></i> Xác Nhận Đăng Ký`;

            const svId = parseInt(document.getElementById('dk_sinhvien_id').value);
            const dtId = parseInt(document.getElementById('dk_detai_id').value);

            if (!svId || !dtId) {
                showAlert('Vui lòng chọn đầy đủ Sinh viên và Đề tài!', true);
                return;
            }

            setLoading('btnDangKy', true, originalText);

            try {
                await sleep(300);

                const res = await fetch(`${API_BASE}/dang-ky-de-tai`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ sinhvien_id: svId, detai_id: dtId })
                });
                const data = await res.json();

                if (res.ok) {
                    showAlert(data.message || 'Đăng ký đề tài thành công!', false);
                    e.target.reset();
                    loadDropdowns();
                } else {
                    showAlert(data.message || 'Đăng ký đề tài thất bại!', true);
                }
            } catch (err) {
                showAlert('Lỗi kết nối API Server!', true);
            } finally {
                setLoading('btnDangKy', false, originalText);
            }
        });
    }

    // 2. Thao tác Nhập Điểm (PUT /api/dang-ky-de-tai/nhap-diem)
    const formNhapDiem = document.getElementById('formNhapDiem');
    if (formNhapDiem) {
        formNhapDiem.addEventListener('submit', async (e) => {
            e.preventDefault();
            const originalText = `<i class="bi bi-star me-1"></i> Cập Nhật Điểm`;

            const dkIdRaw = document.getElementById('nd_dang_ky_id').value;
            const dkId = parseInt(dkIdRaw);
            const diemVal = parseFloat(document.getElementById('nd_diem').value);

            if (!dkIdRaw || isNaN(dkId)) {
                showAlert('Vui lòng chọn lượt Đăng ký hợp lệ!', true);
                return;
            }

            setLoading('btnNhapDiem', true, originalText);

            try {
                await sleep(300);

                const res = await fetch(`${API_BASE}/dang-ky-de-tai/nhap-diem`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ dang_ky_id: dkId, diem: diemVal })
                });
                const data = await res.json();

                if (res.ok) {
                    showAlert(data.message || 'Cập nhật điểm thành công!', false);
                    e.target.reset();
                    loadDropdowns();
                } else {
                    showAlert(data.message || 'Cập nhật điểm thất bại!', true);
                }
            } catch (err) {
                showAlert('Lỗi kết nối API Server!', true);
            } finally {
                setLoading('btnNhapDiem', false, originalText);
            }
        });
    }
});