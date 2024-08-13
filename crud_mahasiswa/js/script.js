document.addEventListener('DOMContentLoaded', () => {
    tampilkanSemuaNilai();
});

document.getElementById('formTambahNilaiInner').addEventListener('submit', (event) => {
    event.preventDefault();
    tambahNilai();
});

function toggleFormTambahNilai() {
    const formTambahNilai = document.getElementById('formTambahNilai');
    if (formTambahNilai.classList.contains('d-none')) {
        formTambahNilai.classList.remove('d-none');
    } else {
        formTambahNilai.classList.add('d-none');
    }
}

function showActionMessage(message, isSuccess) {
    const actionMessage = document.getElementById('actionMessage');
    actionMessage.textContent = message;
    actionMessage.classList.remove('d-none');
    actionMessage.classList.remove(isSuccess ? 'action-error' : 'action-success');
    actionMessage.classList.add(isSuccess ? 'action-success' : 'action-error');
    setTimeout(() => {
        actionMessage.classList.add('d-none');
    }, 3000); 
}

function tampilkanSemuaNilai() {
    fetch('http://localhost/UTS_PSAIT/api_mahasiswa/mahasiswa_api.php')
        .then(response => response.json())
        .then(data => {
            const nilaiMahasiswaBody = document.getElementById('nilaiMahasiswaBody');
            nilaiMahasiswaBody.innerHTML = ''; 

            data.data.forEach(item => {
                const row = `<tr>
                    <td>${item.nim}</td>
                    <td>${item.nama}</td>
                    <td>${item.alamat}</td>
                    <td>${item.tanggal_lahir}</td>
                    <td>${item.kode_mk}</td>
                    <td>${item.nama_mk}</td>
                    <td>${item.sks}</td>
                    <td>${item.nilai}</td>
                    <td>
                        <button class="btn btn-update btn-sm" onclick="updateNilai('${item.nim}', '${item.kode_mk}')">Update</button>
                        <button class="btn btn-delete btn-sm ml-1" onclick="confirmDelete('${item.nim}', '${item.kode_mk}')">Hapus</button>
                    </td>
                </tr>`;
                nilaiMahasiswaBody.innerHTML += row;
            });
        })
        .catch(error => console.error('Error:', error));
}

function tambahNilai() {
    const nim = document.getElementById('nim').value;
    const kode_mk = document.getElementById('kodeMK').value;
    const nilai = document.getElementById('nilai').value;

    fetch('http://localhost/UTS_PSAIT/api_mahasiswa/mahasiswa_api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ nim: nim, kode_mk: kode_mk, nilai: nilai })
    })
    .then(response => response.json())
    .then(data => {
        tampilkanSemuaNilai(); 
        showActionMessage(data.message, true);
        document.getElementById('formTambahNilaiInner').reset(); 
    })
    .catch(error => {
        console.error('Error:', error);
        showActionMessage('Terjadi kesalahan. Silakan coba lagi.', false);
    });
}

function hapusNilai(nim, kode_mk) {
    fetch('http://localhost/UTS_PSAIT/api_mahasiswa/mahasiswa_api.php', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ nim: nim, kode_mk: kode_mk })
    })
    .then(response => response.json())
    .then(data => {
        tampilkanSemuaNilai(); 
        showActionMessage(data.message, true);
    })
    .catch(error => {
        console.error('Error:', error);
        showActionMessage('Terjadi kesalahan. Silakan coba lagi.', false);
    });
}

function confirmDelete(nim, kode_mk) {
    if (confirm('Apakah Anda yakin ingin menghapus nilai ini?')) {
        hapusNilai(nim, kode_mk);
    }
}

function updateNilai(nim, kode_mk) {
    const nilaiBaru = prompt('Masukkan nilai baru:');
    if (nilaiBaru !== null) {
        fetch('http://localhost/UTS_PSAIT/api_mahasiswa/mahasiswa_api.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ nim: nim, kode_mk: kode_mk, nilai: nilaiBaru })
        })
        .then(response => response.json())
        .then(data => {
            tampilkanSemuaNilai();
            showActionMessage(data.message, true);
        })
        .catch(error => {
            console.error('Error:', error);
            showActionMessage('Terjadi kesalahan. Silakan coba lagi.', false);
        });
    }
}