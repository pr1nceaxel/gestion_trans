<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "compte";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Récupérer les transactions
$sql = "SELECT date, description, montant_gain, montant_dep FROM finance ORDER BY date ASC";
$result = $conn->query($sql);

// Initialiser le solde
$solde = 0;

if ($result->num_rows > 0) {
  //  transaction
  while($row = $result->fetch_assoc()) {
      $date = $row['date'];
      $description = $row['description'];
      $montant_gain = $row['montant_gain'];
      $montant_dep = $row['montant_dep'];

      // Calculer  solde
      $solde += $montant_gain - $montant_dep;
    }}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Comptes</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body>
    <p class="text-center bg-blue-700">BIENVENU</p>

    <div class="flex justify-center bg-gray-500 max-w-lg mx-auto shadow-md rounded mt-8 px-5 p-8 mx-5">
        <a href="trans.html" class="bg-blue-400 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded mr-2 focus:outline-none focus:shadow-outline">
          NOTE TRANSACTION
        </a>

        <a href="solde.php" class="bg-green-400 hover:bg-green-500 text-white font-bold py-2 px-4 rounded mr-2 focus:outline-none focus:shadow-outline">
          HISTORIQUE TRANSACTION
        </a>
        <div class="block bg-white px-2 py-2 font-bold">Solde total: <?php echo number_format($solde, 0);  ?> FCFA </div>
    </div>
      
</body>
