<?php
include 'database.php';

$query = "SELECT nota, COUNT(*) as total FROM avaliacao GROUP BY nota ORDER BY nota DESC";
$result = $conexao->query($query);

$data = [];
$data[] = ['Nota', 'Quantidade'];

while ($row = $result->fetch_assoc()) {
    $data[] = [(string)$row['nota'] . ' Estrelas', (int)$row['total']];
}

echo json_encode($data);

// Fechar conexão
$conexao->close();
?>