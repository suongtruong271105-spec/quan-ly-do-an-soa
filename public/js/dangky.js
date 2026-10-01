const API_BASE = '/api';

const sleep = (ms) => new Promise(resolve => setTimeout(resolve, ms));

function displayLog(data, isError = false) {
    const logElement = document.getElementById('outputLog');
    if (!logElement) return;
    logElement.className = `console-output ${isError ? 'text-error-custom' : 'text-success-custom'}`;
    logElement.textContent = JSON.stringify(data, null, 2);
}

function clearLog() {
    const logElement = document.getElementById('outputLog');
    if (logElement) {
        logElement.textContent = 'Kết quả gọi API sẽ hiển thị ở đây...';
    }
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

document.addEventListener('DOMContentLoaded', () => {
    // 1. Thao tác Đăng Ký Đề Tài (POST /api/dang-ky-de-tai)
    const formDangKy = document.getElementById('formDangKy');
    if (formDangKy) {
        formDangKy.addEventListener('submit', async (e) => {
            e.preventDefault();
            const originalText = `<i class="bi bi-check-circle me-1"></i> Xác Nhận Đăng Ký`;
            setLoading('btnDangKy', true, originalText);

            const payload = {
                sinhvien_id: parseInt(document.getElementById('dk_sinhvien_id').value),
                detai_id: parseInt(document.getElementById('dk_detai_id').value)
            };

            try {
                await sleep(400);

                const res = await fetch(`${API_BASE}/dang-ky-de-tai`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                displayLog(data, !res.ok);
                if (res.ok) e.target.reset();
            } catch (err) {
                displayLog({ error: 'Lỗi kết nối API Server!' }, true);
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
            setLoading('btnNhapDiem', true, originalText);

            const payload = {
                dang_ky_id: parseInt(document.getElementById('nd_dang_ky_id').value),
                diem: parseFloat(document.getElementById('nd_diem').value)
            };

            try {
                await sleep(400);

                const res = await fetch(`${API_BASE}/dang-ky-de-tai/nhap-diem`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                displayLog(data, !res.ok);
                if (res.ok) e.target.reset();
            } catch (err) {
                displayLog({ error: 'Lỗi kết nối API Server!' }, true);
            } finally {
                setLoading('btnNhapDiem', false, originalText);
            }
        });
    }
});