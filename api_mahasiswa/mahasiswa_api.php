<?php
require_once "config.php";

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        if (!empty($_GET["nim"])) {
            $nim = $_GET["nim"];
            get_mahasiswa_by_nim($nim);
        } else {
            get_all_mahasiswa();
        }
        break;
    case 'POST':
        add_newscore_mahasiswa();
        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $nim = $data["nim"];
        $kode_mk = $data["kode_mk"];
        update_mhs($nim, $kode_mk);
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        $nim = $data["nim"];
        $kode_mk = $data["kode_mk"];
        delete_score_mahasiswa($nim, $kode_mk); 
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function get_all_mahasiswa()
{
    global $mysqli;
    $query = "SELECT m.nim, m.nama, m.alamat, m.tanggal_lahir, p.kode_mk, mk.nama_mk, mk.sks, p.nilai 
            FROM mahasiswa m
            INNER JOIN perkuliahan p ON m.nim = p.nim
            INNER JOIN matakuliah mk ON p.kode_mk = mk.kode_mk";
    $data = array();
    $result = $mysqli->query($query);
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    $response = array(
        'status' => 1,
        'message' => 'Get List Mahasiswa Successfully.',
        'data' => $data
    );
    header('Content-Type: application/json');
    echo json_encode($response);
}

function get_mahasiswa_by_nim($nim = '')
{
    global $mysqli;
    $query = "SELECT m.nim, m.nama, m.alamat, m.tanggal_lahir, p.kode_mk, mk.nama_mk, mk.sks, p.nilai 
            FROM mahasiswa m
            INNER JOIN perkuliahan p ON m.nim = p.nim
            INNER JOIN matakuliah mk ON p.kode_mk = mk.kode_mk";
    if (!empty($nim)) {
        $query .= " WHERE m.nim = '" . $nim . "'";
    } else {
        // Jika nim tidak disediakan, kembalikan pesan kesalahan
        $response = array(
            'status' => 0,
            'message' => 'Parameter nim is required.'
        );
        http_response_code(400); // Bad Request
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
    }
    $data = array();
    $result = $mysqli->query($query);
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        $response = array(
            'status' => 1,
            'message' => 'Get Mahasiswa Successfully.',
            'data' => $data
        );
    } else {
        // Jika tidak ada hasil untuk nim yang diberikan, kembalikan pesan
        $response = array(
            'status' => 0,
            'message' => 'Mahasiswa with nim ' . $nim . ' not found.'
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}


function add_newscore_mahasiswa()
{
    global $mysqli;
    $content_type = $_SERVER["CONTENT_TYPE"];

    $nim = "";
    $kode_mk = "";
    $nilai = "";

    if ($content_type === "application/json") {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!empty($data["nim"]) && !empty($data["kode_mk"]) && !empty($data["nilai"])) {
            $nim = $data["nim"];
            $kode_mk = $data["kode_mk"];
            $nilai = $data["nilai"];
        } else {
            $response = array(
                'status' => 0,
                'message' => 'Parameters "nim", "kode_mk", and "nilai" are required.'
            );
            header('Content-Type: application/json');
            echo json_encode($response);
            return;
        }
    } else {
        if (!empty($_POST["nim"]) && !empty($_POST["kode_mk"]) && !empty($_POST["nilai"])) {
            $nim = $_POST["nim"];
            $kode_mk = $_POST["kode_mk"];
            $nilai = $_POST["nilai"];
        } else {
            $response = array(
                'status' => 0,
                'message' => 'Parameters "nim", "kode_mk", and "nilai" are required.'
            );
            header('Content-Type: application/json');
            echo json_encode($response);
            return;
        }
    }

    $result = mysqli_query($mysqli, "INSERT INTO perkuliahan SET nim = '$nim', kode_mk = '$kode_mk', nilai = '$nilai'");

    if ($result) {
        $response = array(
            'status' => 1,
            'message' => 'Mahasiswa Added Successfully.'
        );
    } else {
        $response = array(
            'status' => 0,
            'message' => 'Mahasiswa Addition Failed.'
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

function update_mhs($nim, $kode_mk)
{
    global $mysqli;
    $data = json_decode(file_get_contents('php://input'), true);
    if (!empty($data["nilai"])) {
        $nilai = $data["nilai"];
    } else {
        $response = array(
            'status' => 0,
            'message' => 'Parameter "nilai" is required.'
        );
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
    }

    $result = mysqli_query($mysqli, "UPDATE perkuliahan SET nilai = '$nilai' WHERE nim='$nim' AND kode_mk='$kode_mk'");

    if ($result) {
        $response = array(
            'status' => 1,
            'message' => 'Nilai Updated Successfully for nim ' . $nim . ' and kode_mk ' . $kode_mk . '.'
        );
    } else {
        $response = array(
            'status' => 0,
            'message' => 'Nilai Updation Failed.'
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

function delete_score_mahasiswa($nim, $kode_mk)
{
    global $mysqli;
    
    if (empty($nim) || empty($kode_mk)) {
        $response = array(
            'status' => 0,
            'message' => 'Parameters "nim" and "kode_mk" are required.'
        );
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
    }

    $result = mysqli_query($mysqli, "DELETE FROM perkuliahan WHERE nim='$nim' AND kode_mk='$kode_mk'");

    if ($result) {
        $response = array(
            'status' => 1,
            'message' => 'Nilai Deleted Successfully for nim ' . $nim . ' and kode_mk ' . $kode_mk . '.'
        );
    } else {
        $response = array(
            'status' => 0,
            'message' => 'Nilai Deletion Failed.'
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
