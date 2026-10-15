//Exercice 2 – Prêt d’un ordinateur portable (40 min – 3 points)

<?php

//-----Classe mère-----
//La classe mère contient les informations communes aux équipements : numéro d’inventaire et marque.
//Le constructeur initialise ces deux informations. 
//La méthode description() retourne une phrase contenant le numéro et la marque.
class Equipement
{
    private string $numeroInventaire;
    private string $marque;

    public function __construct(string $numeroInventaire, string $marque)
    {
        $this->numeroInventaire = $numeroInventaire;
        $this->marque = $marque;
    }

    public function getNumeroInventaire(): string
    {
        return $this->numeroInventaire;
    }

    public function getMarque(): string
    {
        return $this->marque;
    }

    public function description(): string
    {
        return "Inventaire : " . $this->numeroInventaire
            . " - Marque : " . $this->marque;
    }
}

//-----L’interface Empruntable-----
//Une interface définit les méthodes qu’une classe doit obligatoirement posséder si elle l’implémente
interface Empruntable
{
    public function emprunter(string $nom): void;

    public function restituer(): void;

    public function estDisponible(): bool;
}
//emprunter() enregistre un emprunt.
//restituer() enregistre le retour du matériel.
//estDisponible() renvoie true ou false.
//L’interface définit le contrat, mais elle ne contient pas ici les règles métier


// Classe fille
//-----L’héritage avec extends-----

class OrdinateurPortable extends Equipement implements Empruntable

//extends Equipement signifie que OrdinateurPortable hérite de la classe mère.
//implements Empruntable signifie que la classe doit implémenter les trois méthodes de l’interface.
{
    private int $ramGo;
    private ?string $emprunteur;

    public function __construct(
        string $numeroInventaire,
        string $marque,
        int $ramGo
    ) {
        parent::__construct($numeroInventaire, $marque);

        if ($ramGo <= 0) {
            throw new Exception("La RAM doit être supérieure à zéro.");
        }

        $this->ramGo = $ramGo;
        $this->emprunteur = null;
    }

    public function getRamGo(): int
    {
        return $this->ramGo;
    }

    public function getEmprunteur(): ?string
    {
        return $this->emprunteur;
    }

    public function description(): string
    {
        return parent::description()
            . " - RAM : " . $this->ramGo . " Go";
    }

    public function emprunter(string $nom): void
    {
        if (trim($nom) === "") {
            throw new Exception("Le nom de l'emprunteur ne peut pas être vide.");
        }

        if (!$this->estDisponible()) {
            throw new Exception("Ce portable est déjà emprunté.");
        }

        $this->emprunteur = trim($nom);
    }

    public function restituer(): void
    {
        if ($this->estDisponible()) {
            throw new Exception("Ce portable n'est pas emprunté.");
        }

        $this->emprunteur = null;
    }

    public function estDisponible(): bool
    {
        return $this->emprunteur === null;
    }
}

// Fonction indépendante des classes
function afficherDisponibilite(Empruntable $materiel): void
{
    if ($materiel->estDisponible()) {
        echo "Disponible<br>";
    } else {
        echo "Emprunté<br>";
    }
}

// Tests
echo "<h2>Tests de l'ordinateur portable</h2>";

$portable = new OrdinateurPortable("LENOVO-024", "Lenovo", 16);

echo "<strong>1. Description :</strong><br>";
echo $portable->description() . "<br>";

echo "Disponibilité initiale : ";
afficherDisponibilite($portable);

// Test 2 : emprunter le portable
try {
    $portable->emprunter("Camille");
    echo "2. Emprunt effectué au nom de Camille.<br>";
    echo "Disponibilité : ";
    afficherDisponibilite($portable);
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "<br>";
}

// Test 3 : second emprunt interdit
try {
    $portable->emprunter("Alex");
} catch (Exception $e) {
    echo "3. Second emprunt refusé : "
        . $e->getMessage() . "<br>";
}

// Test 4 : restitution
try {
    $portable->restituer();
    echo "4. Restitution effectuée.<br>";
    echo "Disponibilité : ";
    afficherDisponibilite($portable);
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "<br>";
}

// Test 5 : nouvelle restitution interdite
try {
    $portable->restituer();
} catch (Exception $e) {
    echo "5. Nouvelle restitution refusée : "
        . $e->getMessage() . "<br>";
}
?>