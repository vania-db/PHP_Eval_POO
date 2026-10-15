//Exercice 3 – Catalogue MySQL et ORM manuel 

<?php

// Connexion à la base de données
$pdo = new PDO(
    "mysql:host=localhost;dbname=parc_informatique;charset=utf8mb4",
    "root",
    ""
);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Classe métier
class ModeleMateriel
{
    private ?int $id = null;
    private string $reference = "";
    private string $designation = "";
    private string $categorie = "";

    public function __construct(array $donnees = [])
    {
        if (!empty($donnees)) {
            $this->hydrate($donnees);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference): void
    {
        $this->reference = $reference;
    }

    public function getDesignation(): string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): void
    {
        $this->designation = $designation;
    }

    public function getCategorie(): string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): void
    {
        $this->categorie = $categorie;
    }

    public function hydrate(array $donnees): void
    {
        if (isset($donnees["id"])) {
            $this->setId((int) $donnees["id"]);
        }

        if (isset($donnees["reference"])) {
            $this->setReference($donnees["reference"]);
        }

        if (isset($donnees["designation"])) {
            $this->setDesignation($donnees["designation"]);
        }

        if (isset($donnees["categorie"])) {
            $this->setCategorie($donnees["categorie"]);
        }
    }
}

// Gestionnaire de la base de données
class ModeleMaterielManager
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function ajouter(ModeleMateriel $modele): void
    {
        $sql = "INSERT INTO modele_materiel
                (reference, designation, categorie)
                VALUES (:reference, :designation, :categorie)";

        $requete = $this->pdo->prepare($sql);

        $requete->execute([
            "reference" => $modele->getReference(),
            "designation" => $modele->getDesignation(),
            "categorie" => $modele->getCategorie()
        ]);

        $modele->setId((int) $this->pdo->lastInsertId());
    }

    public function trouverParReference(
        string $reference
    ): ?ModeleMateriel {
        $sql = "SELECT id, reference, designation, categorie
                FROM modele_materiel
                WHERE reference = :reference";

        $requete = $this->pdo->prepare($sql);
        $requete->execute(["reference" => $reference]);

        $donnees = $requete->fetch(PDO::FETCH_ASSOC);

        if ($donnees === false) {
            return null;
        }

        return new ModeleMateriel($donnees);
    }
}

// Tests
$manager = new ModeleMaterielManager($pdo);

echo "<h2>Tests du catalogue</h2>";

// Création des deux modèles
$modele1 = new ModeleMateriel([
    "reference" => "PC-LAB-01",
    "designation" => "Ordinateur laboratoire",
    "categorie" => "Informatique"
]);

$modele2 = new ModeleMateriel([
    "reference" => "VP-AMPHI-01",
    "designation" => "Vidéoprojecteur",
    "categorie" => "Audiovisuel"
]);

// Insertion du premier modèle
try {
    $manager->ajouter($modele1);
    echo "Modèle 1 ajouté. ID : "
        . $modele1->getId() . "<br>";
} catch (PDOException $e) {
    echo "Insertion du modèle 1 impossible : "
        . $e->getMessage() . "<br>";
}

// Insertion du deuxième modèle
try {
    $manager->ajouter($modele2);
    echo "Modèle 2 ajouté. ID : "
        . $modele2->getId() . "<br>";
} catch (PDOException $e) {
    echo "Insertion du modèle 2 impossible : "
        . $e->getMessage() . "<br>";
}

// Recherche d'une référence existante
try {
    $resultat = $manager->trouverParReference("PC-LAB-01");

    if ($resultat !== null) {
        echo "<h3>Modèle trouvé</h3>";
        echo "ID : " . $resultat->getId() . "<br>";
        echo "Référence : "
            . $resultat->getReference() . "<br>";
        echo "Désignation : "
            . $resultat->getDesignation() . "<br>";
        echo "Catégorie : "
            . $resultat->getCategorie() . "<br>";
    } else {
        echo "Aucun modèle trouvé.<br>";
    }
} catch (PDOException $e) {
    echo "Erreur de recherche : " . $e->getMessage() . "<br>";
}

// Recherche d'une référence inexistante
$resultat = $manager->trouverParReference("INEXISTANT");

if ($resultat === null) {
    echo "Recherche INEXISTANT : aucun modèle trouvé, comme prévu.";
} else {
    echo "Un modèle a été trouvé.";
}
?>
