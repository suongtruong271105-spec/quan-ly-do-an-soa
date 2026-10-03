                    <div>
                        <label for="giang_vien_huong_dan">Giảng viên hướng dẫn</label>
                        <input id="giang_vien_huong_dan" name="giang_vien_huong_dan" maxlength="100" required>
                    </div>
                </div>
                <div class="actions">
                    <button type="submit" class="btn-primary" id="submit-btn">Thêm mới</button>
                    <button type="button" class="btn-ghost" id="reset-btn">Hủy</button>
                </div>
            </form>
        </section>
        <section class="card">
            <h2>Danh sách đề tài</h2>
            <table>
                <thead>
                    <tr>
                        <th>Mã</th>
                        <th>Tên đề tài</th>
                        <th>Giảng viên</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="de-tai-table"></tbody>
            </table>
        </section>
    </div>
    <script>
        const API_URL = '/api/de-tai';
        const form = document.getElementById('de-tai-form');
        const tableBody = document.getElementById('de-tai-table');
        const alertBox = document.getElementById('alert');
        const formTitle = document.getElementById('form-title');
        const submitBtn = document.getElementById('submit-btn');
        function escapeHtml(value) {
            return String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }
        function showAlert(message, type) {
            alertBox.textContent = message;
            alertBox.className = `alert show ${type}`;
        }
        function clearForm() {
            form.reset();
            document.getElementById('id').value = '';
            formTitle.textContent = 'Thêm đề tài';
            submitBtn.textContent = 'Thêm mới';
        }
        async function request(url, options = {}) {
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    ...(options.headers || {}),
                },
                ...options,
            });
            const payload = await response.json().catch(() => ({}));
            if (!response.ok) {
                const errors = payload.errors
                    ? Object.values(payload.errors).flat().join(' ')
                    : (payload.message || 'Có lỗi xảy ra.');
                throw new Error(errors);
            }
            return payload;
        }
        async function loadDeTai() {
            const { data } = await request(API_URL);
            if (!data.length) {
                tableBody.innerHTML = '<tr><td colspan="4" class="empty">Chưa có đề tài nào.</td></tr>';
                return;
            }
            tableBody.innerHTML = data.map((item) => `
                <tr>
                    <td>${escapeHtml(item.ma_dt)}</td>
                    <td>${escapeHtml(item.ten_dt)}</td>
                    <td>${escapeHtml(item.giang_vien_huong_dan)}</td>
                    <td class="ops">
                        <button class="btn-ghost btn-small" data-edit="${item.id}">Sửa</button>
                        <button class="btn-danger btn-small" data-delete="${item.id}">Xóa</button>
                    </td>
                </tr>
            `).join('');
        }
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const id = document.getElementById('id').value;
            const body = JSON.stringify({
                ma_dt: document.getElementById('ma_dt').value.trim(),
                ten_dt: document.getElementById('ten_dt').value.trim(),
                giang_vien_huong_dan: document.getElementById('giang_vien_huong_dan').value.trim(),
            });
            try {
                const result = id
                    ? await request(`${API_URL}/${id}`, { method: 'PUT', body })
                    : await request(API_URL, { method: 'POST', body });
                showAlert(result.message, 'success');
                clearForm();
                await loadDeTai();
            } catch (error) {
                showAlert(error.message, 'error');
            }
        });
        document.getElementById('reset-btn').addEventListener('click', clearForm);
        tableBody.addEventListener('click', async (event) => {
            const editId = event.target.dataset.edit;
            const deleteId = event.target.dataset.delete;
            if (editId) {
                try {
                    const { data } = await request(`${API_URL}/${editId}`);
                    document.getElementById('id').value = data.id;
                    document.getElementById('ma_dt').value = data.ma_dt;
                    document.getElementById('ten_dt').value = data.ten_dt;
                    document.getElementById('giang_vien_huong_dan').value = data.giang_vien_huong_dan;
                    formTitle.textContent = 'Sửa đề tài';
                    submitBtn.textContent = 'Cập nhật';
                } catch (error) {
                    showAlert(error.message, 'error');
                }
            }
            if (deleteId) {
                if (!confirm('Bạn chắc chắn muốn xóa đề tài này?')) {
                    return;
                }
                try {
                    const result = await request(`${API_URL}/${deleteId}`, { method: 'DELETE' });
                    showAlert(result.message, 'success');
                    clearForm();
                    await loadDeTai();
                } catch (error) {
                    showAlert(error.message, 'error');
                }
            }
        });
        loadDeTai().catch((error) => showAlert(error.message, 'error'));
    </script>
</body>
</html>