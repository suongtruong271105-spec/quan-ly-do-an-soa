const API_BASE = '/api';

// Hàm hiển thị thông báo
function showAlert(message, isError = false) {
    const alertBox = document.getElementById('alertBox');
    const alertMessage = document.getElementById('alertMessage');

    if (!alertBox || !alertMessage) return;

    alertBox.className = `alert alert-dismissible fade show mb-4 shadow-sm ${isError ? 'alert-danger' : 'alert-success'}`;

    // Dùng textContent để tránh chèn HTML không an toàn
    alertMessage.textContent = message;
    alertBox.classList.remove('d-none');

    clearTimeout(window.alertTimeout);
    window.alertTimeout = setTimeout(hideAlert, 3000);
}

function hideAlert() {
    const alertBox = document.getElementById('alertBox');
    if (alertBox) {
        alertBox.classList.add('d-none');
    }
}

// Hiển thị trạng thái đang xử lý trên nút
function setLoading(buttonId, isLoading, originalText) {
    const btn = document.getElementById(buttonId);
    if (!btn) return;

    if (isLoading) {
        btn.disabled = true;
        btn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-1" role="status"></span>
            Đang xử lý...
        `;
        btn.style.opacity = '0.75';
    } else {
        btn.disabled = false;
        btn.innerHTML = originalText;
        btn.style.opacity = '1';
    }
}

// Đọc danh sách từ phản hồi API
function getList(result) {
    if (Array.isArray(result)) return result;
    if (Array.isArray(result.data)) return result.data;
    return [];
}

// Nạp danh sách sinh viên và đề tài vào form đăng ký
async function loadDropdowns() {
    const svSelect = document.getElementById('dk_masv');
    const dtSelect = document.getElementById('dk_madt');

    // Nạp sinh viên và đề tài song song
    const [svResult, dtResult] = await Promise.allSettled([
        fetch(`${API_BASE}/sinhvien`, {
            headers: { Accept: 'application/json' }
        }),
        fetch(`${API_BASE}/de-tai`, {
            headers: { Accept: 'application/json' }
        })
    ]);

    // 1. Nạp danh sách sinh viên
    if (svSelect) {
        try {
            if (svResult.status !== 'fulfilled' || !svResult.value.ok) {
                throw new Error('Không tải được danh sách sinh viên');
            }

            const result = await svResult.value.json();
            const list = getList(result);

            svSelect.innerHTML = '';

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = '-- Chọn sinh viên --';
            svSelect.appendChild(defaultOption);

            list.forEach(sv => {
                const option = document.createElement('option');
                option.value = sv.masv;
                option.textContent = `${sv.masv} - ${sv.hoten || 'N/A'}`;
                svSelect.appendChild(option);
            });

            if (list.length === 0) {
                defaultOption.textContent = 'Chưa có sinh viên trong hệ thống';
            }
        } catch (error) {
            svSelect.innerHTML =
                '<option value="">Lỗi nạp danh sách sinh viên</option>';
            console.error(error);
        }
    }

    // 2. Nạp danh sách đề tài
    if (dtSelect) {
        try {
            if (dtResult.status !== 'fulfilled' || !dtResult.value.ok) {
                throw new Error('Không tải được danh sách đề tài');
            }

            const result = await dtResult.value.json();
            const list = getList(result);

            dtSelect.innerHTML = '';

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = '-- Chọn đề tài --';
            dtSelect.appendChild(defaultOption);

            list.forEach(dt => {
                const option = document.createElement('option');
                option.value = dt.madt;
                option.textContent = `[${dt.madt}] ${dt.tendt || 'N/A'}`;
                dtSelect.appendChild(option);
            });

            if (list.length === 0) {
                defaultOption.textContent = 'Chưa có đề tài trong hệ thống';
            }
        } catch (error) {
            dtSelect.innerHTML =
                '<option value="">Lỗi nạp danh sách đề tài</option>';
            console.error(error);
        }
    }
}

// Khởi tạo chức năng đăng ký
document.addEventListener('DOMContentLoaded', () => {
    loadDropdowns();

    const formDangKy = document.getElementById('formDangKy');

    if (!formDangKy) return;

    formDangKy.addEventListener('submit', async (e) => {
        e.preventDefault();

        const originalText =
            '<i class="bi bi-check-circle me-1"></i> Xác Nhận Đăng Ký';

        const svSelect = document.getElementById('dk_masv');
        const dtSelect = document.getElementById('dk_madt');

        const masv = svSelect ? svSelect.value : '';
        const madt = dtSelect ? dtSelect.value : '';

        if (!masv || !madt) {
            showAlert('Vui lòng chọn đầy đủ Sinh viên và Đề tài!', true);
            return;
        }

        setLoading('btnDangKy', true, originalText);

        try {
            const res = await fetch(`${API_BASE}/dang-ky-de-tai`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json'
                },
                body: JSON.stringify({ masv, madt })
            });

            const data = await res.json();

            if (!res.ok) {
                showAlert(data.message || 'Đăng ký đề tài thất bại!', true);
                return;
            }

            showAlert(data.message || 'Đăng ký đề tài thành công!');
            formDangKy.reset();

            // Tải lại danh sách sau khi đăng ký thành công
            await loadDropdowns();
        } catch (error) {
            console.error('Lỗi đăng ký đề tài:', error);
            showAlert('Không thể kết nối đến API Server!', true);
        } finally {
            setLoading('btnDangKy', false, originalText);
        }
    });
});