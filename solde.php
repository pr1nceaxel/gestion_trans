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

// Initialiser le solde
$solde = 0;

// Récupérer les transactions avec filtrage
$sql = "SELECT date, description, montant_gain, montant_dep FROM finance WHERE 1=1";

//  filtrage par date si une plage de dates est sélectionnée
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    $sql .= " AND date BETWEEN '{$start_date}' AND '{$end_date}'";
}

//  filtrage par type de transaction si un type est sélectionné
if (isset($_GET['transaction_type'])) {
    $transaction_type = $_GET['transaction_type'];
    if ($transaction_type == 'gain') {
        $sql .= " AND montant_gain > 0";
    } elseif ($transaction_type == 'depense') {
        $sql .= " AND montant_dep > 0";
    }
}

$sql .= " ORDER BY date ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualisation des Transactions</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

    <div class="max-w-4xl mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h2 class="text-center font-bold text-xl mb-4">Filtrage des Transactions</h2>
        <form method="GET" action="">
            <label for="start_date">Date début :</label>
            <input type="date" id="start_date" name="start_date" required>

            <label for="end_date">Date fin :</label>
            <input type="date" id="end_date" name="end_date" required>

            <label for="transaction_type_gain" class="mx-4" >Gains :</label>
            <input type="radio" id="transaction_type_gain" name="transaction_type" value="gain" >
            
            <label for="transaction_type_depense" class="mx-4">Dépenses :</label>
            <input type="radio" id="transaction_type_depense" name="transaction_type" value="depense" >

            <button type="submit" value="Filtrer" class="bg-blue-500 text-white mx-4 px-4 font-bold uppercase hover:bg-green-700 rounded" >Filtrer</button>

        </form>
    </div>


    <div class="max-w-4xl mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h2 class="text-center font-bold text-xl mb-4">Historique des Transactions</h2>        
        <a href="solde.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded justify-end">Réinitialiser les filtres</a>

        <table class="min-w-full bg-white border-gray-500 border-b ">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">Date</th>
                    <th class="w-2/5 text-left py-3 px-4 uppercase font-semibold text-sm">Description</th>
                    <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">Montant des gains</th>
                    <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">Montant des dépenses</th>
                    <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">Solde</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                <?php
                if ($result->num_rows > 0) {
                    //  transaction
                    while($row = $result->fetch_assoc()) {
                        $date = $row['date'];
                        $description = $row['description'];
                        $montant_gain = $row['montant_gain'];
                        $montant_dep = $row['montant_dep'];

                        // Calcule solde
                        $solde += $montant_gain - $montant_dep;
                        echo "<tr>";
                        echo "<td class='w-1/5 text-left py-3 px-4'>{$date}</td>";
                        echo "<td class='w-2/5 text-left py-3 px-4'>{$description}</td>";
                        echo "<td class='w-1/5 text-left py-3 px-4'>" . number_format($montant_gain, 0) . " FCFA</td>";
                        echo "<td class='w-1/5 text-left py-3 px-4'>" . number_format($montant_dep, 0) . " FCFA</td>";
                        echo "<td class='w-1/5 text-left py-3 px-4'>" . number_format($solde, 0) . " FCFA</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center py-3 px-4'>Aucune transaction trouvée</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="flex items-center justify-between ">
            <a href="trans.html" class="bg-blue-400 hover:bg-blue-500 text-white font-bold py-2 px-4 mt-4 rounded focus:outline-none focus:shadow-outline">
                NOTE TRANSACTION
            </a> 

            <a href="#" class="bg-gray-500 font-bold uppercase py-2 mt-4 px-4" >Solde total: <?php echo number_format($solde, 0);  ?> </a>
            
            <a
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 mt-4 rounded focus:outline-none focus:shadow-outline"
                href="index.php">
                Retour
            </a>
            

        </div>
    </div>

    <?php
    $conn->close();
    ?>
</body>
</html>
