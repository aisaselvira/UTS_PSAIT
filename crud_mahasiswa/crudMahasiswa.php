<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahasiswa</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4 judul">Sistem Informasi Akademik</h1>

        <button id="btnTambahNilai" class="btn btn-success mb-3 float-right" onclick="toggleFormTambahNilai()">Tambah Nilai</button>

        <div id="formTambahNilai" class="mb-4 d-none">
            <h2>Masukkan Nilai Baru</h2>
            <form id="formTambahNilaiInner">
                <div class="form-group">
                    <label for="nim">NIM:</label>
                    <input type="text" class="form-control" id="nim" name="nim">
                </div>
                <div class="form-group">
                    <label for="kodeMK">Kode MK:</label>
                    <input type="text" class="form-control" id="kodeMK" name="kodeMK">
                </div>
                <div class="form-group">
                    <label for="nilai">Nilai:</label>
                    <input type="text" class="form-control" id="nilai" name="nilai">
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
        

        <div id="actionMessage" class="action-success"></div>

        <div>
            <br><br>
            <h3 class="judul">Nilai Mahasiswa</h3>
            <table class="table table-custom">
                <thead>
                    <tr class="tabel_mhs">
                        <th scope="col">NIM</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Alamat</th>
                        <th scope="col">Tanggal Lahir</th>
                        <th scope="col">Kode MK</th>
                        <th scope="col">Nama MK</th>
                        <th scope="col">SKS</th>
                        <th scope="col">Nilai</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody id="nilaiMahasiswaBody">
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>