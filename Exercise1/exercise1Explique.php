//Exercice 1 – Gestion d’une imprimante

<?php

//-----La classe et les attributs------
class Imprimante
//class Imprimante : crée le modèle qui décrit une imprimante

{
    private string $nom;
    private int $nbFeuilles;
    private bool $allumee;
    //private : interdit de modifier directement l’attribut depuis l’extérieur de l’objet
    //string : texte
    //int : nombre entier
    //bool : valeur true ou false.

    //-----Le constructeur------
    //Le constructeur s’exécute automatiquement quand on crée une imprimante
    public function __construct(string $nom, int $nbFeuilles)
    // __construct() : initialise l’objet


    {
        if ($nbFeuilles < 0 || $nbFeuilles > 250) {
            throw new Exception("Le stock initial doit être entre 0 et 250.");
        }
        //|| : signifie OU
        //throw new Exception(...) : déclenche une erreur si le stock est invalide

        $this->nom = $nom;
        //$this->nom : l’attribut nom de l’objet actuel
        $this->nbFeuilles = $nbFeuilles;
        $this->allumee = false;
        //false : l’imprimante est éteinte au départ
    }


    //-----Les accesseurs (getters)------

    //public : la méthode peut être appelée depuis l’extérieur

    public function getNom(): string
    {
        return $this->nom;
    }
    //return : renvoie la valeur
    //string : elle retourne un texte.

    public function getNbFeuilles(): int
    {
        return $this->nbFeuilles;
    }
    //int : elle retourne un entier

    public function isAllumee(): bool
    {
        return $this->allumee;
    }
    //bool : elle retourne true ou false

    public function allumer(): void
    {
        $this->allumee = true;
    }
    //void : elle ne retourne rien

    public function eteindre(): void
    {
        $this->allumee = false;
    }


    //-----Les méthodes métier------
    public function ajouterPapier(int $n): void
    {
        
        if ($n <= 0) {
            throw new Exception("La quantité ajoutée doit être positive.");
        }

        if ($n > 100) {
            throw new Exception("Un ajout ne peut pas dépasser 100 feuilles.");
        }

        if ($this->nbFeuilles + $n > 250) {
            throw new Exception("Le stock ne peut pas dépasser 250 feuilles.");
        }

        $this->nbFeuilles += $n;
    }
    //Les conditions vérifient les règles avant de modifier le stock. 
    //L’instruction += ajoute la quantité à la valeur actuelle.

    public function imprimer(int $n): void
    {
        if ($n <= 0) {
            throw new Exception("Le nombre de pages doit être positif.");
        }

        if (!$this->allumee) {
            throw new Exception("L'imprimante est éteinte.");
        }

        if ($this->nbFeuilles < $n) {
            throw new Exception("Il n'y a pas assez de feuilles.");
        }

        $this->nbFeuilles -= $n;
    }
    //Vérifier que le nombre de pages est positif, que l’imprimante est allumée et qu’il y a assez de feuilles. 
    // Ce n’est qu’après ces vérifications que le stock diminue.

}

// Tests de l'imprimante

$imprimante = new Imprimante("Salle 204", 40);

echo "<h2>Tests de l'imprimante</h2>";

echo "Imprimante : " . $imprimante->getNom() . "<br>";
echo "Stock initial : " . $imprimante->getNbFeuilles() . " feuilles<br>";

// Test 1 : imprimer quand l'imprimante est éteinte
try {
    $imprimante->imprimer(10);
} catch (Exception $e) {
    echo "Test 1 : impression refusée : "
        . $e->getMessage() . "<br>";
}
//try : exécute une opération qui peut échouer
//catch : récupère l’exception si elle est déclenchée
//$e->getMessage() : affiche le message d’erreur
//Cela permet de tester les erreurs attendues sans arrêter tout le programme


// Test 2 : ajouter 80 feuilles
try {
    $imprimante->ajouterPapier(80);
    echo "Test 2 : ajout réussi. Stock : "
        . $imprimante->getNbFeuilles() . "<br>";
} catch (Exception $e) {
    echo $e->getMessage() . "<br>";
}

// Allumer l'imprimante
$imprimante->allumer();
echo "Imprimante allumée : "
    . ($imprimante->isAllumee() ? "Oui" : "Non") . "<br>";

// Test 3 : imprimer 30 pages
try {
    $imprimante->imprimer(30);
    echo "Test 3 : impression réussie. Stock : "
        . $imprimante->getNbFeuilles() . "<br>";
} catch (Exception $e) {
    echo $e->getMessage() . "<br>";
}

// Test 4 : tenter d'imprimer 100 pages
try {
    $imprimante->imprimer(100);
} catch (Exception $e) {
    echo "Test 4 : impression refusée : "
        . $e->getMessage() . "<br>";
    echo "Stock après le refus : "
        . $imprimante->getNbFeuilles() . "<br>";
}

// Test 5 : ajouter 120 feuilles
try {
    $imprimante->ajouterPapier(120);
} catch (Exception $e) {
    echo "Test 5 : ajout refusé : "
        . $e->getMessage() . "<br>";
}

// Test 6a : ajouter zéro feuille
try {
    $imprimante->ajouterPapier(0);
} catch (Exception $e) {
    echo "Test 6a : ajout refusé : "
        . $e->getMessage() . "<br>";
}

// Test 6b : ajouter une quantité négative
try {
    $imprimante->ajouterPapier(-5);
} catch (Exception $e) {
    echo "Test 6b : ajout refusé : "
        . $e->getMessage() . "<br>";
}

echo "Stock final : " . $imprimante->getNbFeuilles() . " feuilles";
?>

