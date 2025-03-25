<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chart</title>
</head>
<body>
    <div class="container pt-5">
        <h1>Membuat Grafik dengan PHP dan MySQLI</h1>
        <div class="chart-container" style="position: relative; height: 40vh; width: 80vw">
            <canvas id="myChart"></canvas>
        </div>
        <button id="downloadPdf">Download PDF</button>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

<script>
    <?php
    include 'koneksi.php';
    $query = "SELECT jurusan, COUNT(*) AS jml_mahasiswa FROM mahasiswa GROUP BY jurusan;";
    $result = mysqli_query($conn, $query);

    $jurusan = []; // Inisialisasi array
    $jumlah = [];

    while ($data = mysqli_fetch_array($result)) {
        $jurusan[] = $data['jurusan'];
        $jumlah[] = $data['jml_mahasiswa'];
    }
    ?>

    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, { // Perbaikan inisialisasi
        type: 'bar',
        data: {
            labels: <?php echo json_encode($jurusan); ?>,
            datasets: [{
                label: 'Jumlah Mahasiswa',
                data: <?php echo json_encode($jumlah); ?>,
                backgroundColor: [
                    'rgba(255, 99, 71, 1)',
                    'rgba(9, 31, 242, 0.8)',
                    'rgba(255, 128, 6, 0.8)'
                ],
                borderColor: [
                    'rgba(255, 99, 71, 1)',
                    'rgba(9, 31, 242, 0.8)',
                    'rgba(255, 128, 6, 0.8)'
                ],
                borderWidth: 1
            }]
        },
        options: { // Perbaikan tanda titik koma
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    document.getElementById('downloadPdf').addEventListener('click', function() {
        const { jsPDF } = window.jspdf; // Perbaikan destructuring jsPDF
        const pdf = new jsPDF();
        const canvas = document.getElementById('myChart');
        const imgData = canvas.toDataURL('image/png'); // Perbaikan fungsi toDataURL
        pdf.addImage(imgData, 'PNG', 10, 10, 180, 100);
        pdf.save('chart.pdf');
    });
</script>

</html>
