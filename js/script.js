
// ==========================================
// 1. MENGAMBIL ELEMEN HTML
// ==========================================

const dataSiswa = document.getElementById('dataSiswa');
const totalSiswa = document.getElementById('totalSiswa');
const jumlahData = document.getElementById('jumlahData');

const siswaForm = document.getElementById('siswaForm');
const nisInput = document.getElementById('nis');
const namaInput = document.getElementById('nama');
const kelasInput = document.getElementById('kelas');
const jurusanInput = document.getElementById('jurusan');
const alamatInput = document.getElementById('alamat');

let daftarSiswa = [];


// ==========================================
// 2. MENAMPILKAN PESAN
// ==========================================

function tampilkanPesan(pesan, berhasil = true) {
    let elemenPesan = document.getElementById('pesan');

    if (!elemenPesan) {
        elemenPesan = document.createElement('div');
        elemenPesan.id = 'pesan';
        siswaForm.before(elemenPesan);
    }

    elemenPesan.textContent = pesan;
    elemenPesan.className = berhasil
        ? 'pesan berhasil'
        : 'pesan gagal';

    setTimeout(() => {
        elemenPesan.textContent = '';
        elemenPesan.className = '';
    }, 4000);
}


// ==========================================
// 3. MENGAMBIL DAN MENAMPILKAN DATA SISWA
// ==========================================

async function loadDataSiswa() {
    try {
        const response = await fetch('proses/tampil.php');

        if (!response.ok) {
            throw new Error('Gagal menghubungi server.');
        }

        const hasil = await response.json();

        if (!hasil.status) {
            throw new Error(hasil.message);
        }

        daftarSiswa = hasil.data;

        dataSiswa.innerHTML = '';

        totalSiswa.textContent = daftarSiswa.length;

        jumlahData.textContent =
            'Menampilkan ' + daftarSiswa.length + ' data siswa';

        if (daftarSiswa.length === 0) {
            const row = document.createElement('tr');
            const cell = document.createElement('td');

            cell.colSpan = 7;
            cell.className = 'empty-state';
            cell.textContent = 'Belum ada data siswa.';

            row.appendChild(cell);
            dataSiswa.appendChild(row);

            return;
        }

        daftarSiswa.forEach((siswa, index) => {
            const row = document.createElement('tr');

            const values = [
                index + 1,
                siswa.nis,
                siswa.nama,
                siswa.kelas,
                siswa.jurusan,
                siswa.alamat || '-'
            ];

            values.forEach(value => {
                const cell = document.createElement('td');
                cell.textContent = value;
                row.appendChild(cell);
            });

            // Tombol aksi
            
const actionCell = document.createElement('td');

// Tombol Edit
            const tombolEdit = document.createElement('button');
            tombolEdit.type = 'button';
            tombolEdit.textContent = 'Edit';
            tombolEdit.className = 'btn-edit';
            tombolEdit.dataset.id = siswa.id;

            // Tombol Hapus
            const tombolHapus = document.createElement('button');
            tombolHapus.type = 'button';
            tombolHapus.textContent = 'Hapus';
            tombolHapus.className = 'btn-hapus';
            tombolHapus.dataset.id = siswa.id;

            // Masukkan kedua tombol ke kolom aksi
            actionCell.appendChild(tombolEdit);
            actionCell.appendChild(tombolHapus);

            row.appendChild(actionCell);


            dataSiswa.appendChild(row);
        });

    } catch (error) {
        console.error('Error:', error);

        dataSiswa.innerHTML = '';

        const row = document.createElement('tr');
        const cell = document.createElement('td');

        cell.colSpan = 7;
        cell.className = 'empty-state';
        cell.textContent =
            'Gagal memuat data siswa. Periksa koneksi database.';

        row.appendChild(cell);
        dataSiswa.appendChild(row);

        jumlahData.textContent = 'Gagal mengambil data';
    }
}


// ==========================================
// 4. VALIDASI FORMULIR
// ==========================================

function validasiField(input, errorId, pesan, kondisi) {
    const elemenError = document.getElementById(errorId);

    if (!elemenError) return kondisi;

    if (!kondisi) {
        elemenError.textContent = pesan;
        return false;
    }

    elemenError.textContent = '';
    return true;
}


function validasiForm() {
    const validNis = validasiField(
        nisInput,
        'errorNis',
        'NIS wajib diisi.',
        nisInput.value.trim() !== ''
    );

    const validNama = validasiField(
        namaInput,
        'errorNama',
        'Nama minimal 3 karakter.',
        namaInput.value.trim().length >= 3
    );

    const validKelas = validasiField(
        kelasInput,
        'errorKelas',
        'Silakan pilih kelas.',
        kelasInput.value !== ''
    );

    const validJurusan = validasiField(
        jurusanInput,
        'errorJurusan',
        'Silakan pilih jurusan.',
        jurusanInput.value !== ''
    );

    return validNis && validNama && validKelas && validJurusan;
}


// ==========================================
// 5. VALIDASI REAL-TIME
// ==========================================

nisInput.addEventListener('input', () => {
    validasiField(
        nisInput,
        'errorNis',
        'NIS wajib diisi.',
        nisInput.value.trim() !== ''
    );
});

namaInput.addEventListener('input', () => {
    validasiField(
        namaInput,
        'errorNama',
        'Nama minimal 3 karakter.',
        namaInput.value.trim().length >= 3
    );
});

kelasInput.addEventListener('change', () => {
    validasiField(
        kelasInput,
        'errorKelas',
        'Silakan pilih kelas.',
        kelasInput.value !== ''
    );
});

jurusanInput.addEventListener('change', () => {
    validasiField(
        jurusanInput,
        'errorJurusan',
        'Silakan pilih jurusan.',
        jurusanInput.value !== ''
    );
});


// ==========================================
// 6. EVENT SUBMIT TAMBAH DAN EDIT
// ==========================================

siswaForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    if (!validasiForm()) {
        tampilkanPesan(
            'Periksa kembali data yang wajib diisi.',
            false
        );
        return;
    }

    const formData = new FormData(siswaForm);

    const idSiswa = document.getElementById('id').value;

    // Memilih proses tambah atau edit
    const url = idSiswa
        ? 'proses/edit.php'
        : 'proses/tambah.php';

    const tombolSubmit = document.getElementById('btnSubmit');
    const teksTombolAwal = tombolSubmit.textContent;

    tombolSubmit.disabled = true;
    tombolSubmit.textContent = 'Menyimpan...';

    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });

        const hasil = await response.json();

        if (!response.ok || !hasil.status) {
            throw new Error(
                hasil.message || 'Gagal menyimpan data.'
            );
        }

        tampilkanPesan(hasil.message, true);

        // Mengembalikan formulir ke kondisi awal
        siswaForm.reset();

        document.getElementById('id').value = '';

        document.getElementById('formTitle').textContent =
            'Tambah Data Siswa';

        tombolSubmit.textContent = 'Tambah Siswa';

        // Menghapus pesan validasi
        document.querySelectorAll('.error-message').forEach(elemen => {
            elemen.textContent = '';
        });

        // Memperbarui tabel tanpa reload halaman
        await loadDataSiswa();

    } catch (error) {
        console.error('Error:', error);
        tampilkanPesan(error.message, false);

    } finally {
        tombolSubmit.disabled = false;

        if (document.getElementById('id').value === '') {
            tombolSubmit.textContent = 'Tambah Siswa';
        } else {
            tombolSubmit.textContent = teksTombolAwal;
        }
    }
});


// ==========================================
// 7. EVENT DELEGATION TOMBOL EDIT
// ==========================================


// ==========================================
// EVENT DELEGATION TOMBOL EDIT DAN HAPUS
// ==========================================

dataSiswa.addEventListener('click', async function(event) {

    // Cek apakah tombol Edit diklik
    const tombolEdit = event.target.closest('.btn-edit');

    if (tombolEdit) {
        const idSiswa = tombolEdit.dataset.id;

        const siswa = daftarSiswa.find(
            item => String(item.id) === String(idSiswa)
        );

        if (!siswa) {
            tampilkanPesan('Data siswa tidak ditemukan.', false);
            return;
        }

        // Masukkan data ke formulir
        document.getElementById('id').value = siswa.id;
        nisInput.value = siswa.nis;
        namaInput.value = siswa.nama;
        kelasInput.value = siswa.kelas;
        jurusanInput.value = siswa.jurusan;
        alamatInput.value = siswa.alamat || '';

        // Ubah formulir ke mode Edit
        document.getElementById('formTitle').textContent =
            'Edit Data Siswa';

        document.getElementById('btnSubmit').textContent =
            'Simpan Perubahan';

        document.querySelectorAll('.error-message').forEach(elemen => {
            elemen.textContent = '';
        });

        siswaForm.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });

        return;
    }

    // Cek apakah tombol Hapus diklik
    const tombolHapus = event.target.closest('.btn-hapus');

    if (tombolHapus) {
        const idSiswa = tombolHapus.dataset.id;

        const siswa = daftarSiswa.find(
            item => String(item.id) === String(idSiswa)
        );

        if (!siswa) {
            tampilkanPesan('Data siswa tidak ditemukan.', false);
            return;
        }

        // Konfirmasi sebelum menghapus
        const konfirmasi = confirm(
            `Apakah kamu yakin ingin menghapus data siswa ${siswa.nama}?`
        );

        if (!konfirmasi) return;

        tombolHapus.disabled = true;
        tombolHapus.textContent = 'Menghapus...';

        try {
            const formData = new URLSearchParams();
            formData.append('id', idSiswa);

            const response = await fetch('proses/hapus.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: formData.toString()
            });

            const hasil = await response.json();

            if (!response.ok || !hasil.status) {
                throw new Error(
                    hasil.message || 'Gagal menghapus data.'
                );
            }

            // Jika data yang sedang diedit dihapus,
            // kembalikan formulir ke mode Tambah
            const inputId = document.getElementById('id');

            if (inputId.value === String(idSiswa)) {
                siswaForm.reset();
                inputId.value = '';

                document.getElementById('formTitle').textContent =
                    'Tambah Data Siswa';

                document.getElementById('btnSubmit').textContent =
                    'Tambah Siswa';

                document.querySelectorAll('.error-message').forEach(elemen => {
                    elemen.textContent = '';
                });
            }

            tampilkanPesan(hasil.message, true);

            // Perbarui tabel tanpa reload halaman
            await loadDataSiswa();

        } catch (error) {
            console.error('Error:', error);
            tampilkanPesan(error.message, false);

            tombolHapus.disabled = false;
            tombolHapus.textContent = 'Hapus';
        }
    }
});


// ==========================================
// 8. MEMUAT DATA SAAT HALAMAN DIBUKA
// ==========================================

document.addEventListener('DOMContentLoaded', loadDataSiswa);
