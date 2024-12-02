// Contoh fungsi untuk insert data ke tabel Lokasi
function insertLokasi() {
    var namaLokasi = document.getElementById('nama_lokasi').value;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'server.php', true);
    xhr.setRequestHeader('Content-Type', 'application /x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            alert(response.message);
        } else {
            alert('Error: ' + xhr.statusText);
        }
    };

    xhr.send('action=insertLokasi&nama_lokasi=' + namaLokasi);
}

// Contoh fungsi untuk get data dari tabel Lokasi
function getLokasi() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'server.php?action=getLokasi', true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            console.log(response);
        } else {
            alert('Error: ' + xhr.statusText);
        }
    };

    xhr.send();
}