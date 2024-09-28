<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['date'], $_POST['amount'], $_POST['description'], $_POST['type'])) {
        $date = htmlspecialchars($_POST['date']);
        $montant = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $description = htmlspecialchars($_POST['description']);
        $type = htmlspecialchars($_POST['type']); 

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "compte";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("La connexion a échoué : " . $conn->connect_error);
        }

        if ($type == 'gain') {
            $montant_gain = $montant;
            $montant_dep = 0;
        } else {
            $montant_gain = 0;
            $montant_dep = $montant;
        }

        $stmt = $conn->prepare("INSERT INTO finance (date, montant_gain, montant_dep, description, solde) VALUES (?, ?, ?, ?, 0)");
        
        if ($stmt === false) {
            die("Erreur de préparation de la requête SQL : " . $conn->error);
        }

        $stmt->bind_param("sdds", $date, $montant_gain, $montant_dep, $description);

        if ($stmt->execute() === TRUE) {
            // Redirige le formulaire avec un paramètre de succès
            header("Location: trans.html?success=1");
            exit();
        } else {
            echo "Erreur : " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Erreur : Toutes les données du formulaire ne sont pas présentes.";
    }
}

