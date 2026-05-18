<?php
require 'koneksi.php';

// SQL to create meja table
$sql_create_table = "
CREATE TABLE `meja` (
  `id_meja` int(11) NOT NULL AUTO_INCREMENT,
  `nomor_meja` int(11) NOT NULL,
  `status` enum('kosong','diisi') NOT NULL DEFAULT 'kosong',
  PRIMARY KEY (`id_meja`),
  UNIQUE KEY `nomor_meja` (`nomor_meja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";

if ($conn->query($sql_create_table) === TRUE) {
    echo "Table 'meja' created successfully.<br>";

    // SQL to populate meja table
    $sql_populate_table = "INSERT INTO `meja` (`nomor_meja`) VALUES ";
    for ($i = 1; $i <= 40; $i++) {
        $sql_populate_table .= "($i),";
    }
    // Remove the last comma
    $sql_populate_table = rtrim($sql_populate_table, ',');

    if ($conn->query($sql_populate_table) === TRUE) {
        echo "Table 'meja' populated with 40 tables successfully.<br>";
    } else {
        echo "Error populating table 'meja': " . $conn->error . "<br>";
    }
} else {
    echo "Error creating table 'meja': " . $conn->error . "<br>";
}

$conn->close();
?>
<p>Setup complete. You can now delete this file.</p>