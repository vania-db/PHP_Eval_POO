//Exercice 1 – Gestion d’une imprimante

<?php

class Imprimante
{
    private string $nom;
    private int $nbFeuilles;
    private bool $allumee;

    public function __construct(string $nom, int $nbFeuilles)
    {
        if ($nbFeuilles < 0 || $nbFeuilles > 250) {
            throw new Exception("Le stock initial doit être entre 0 et 250.");
        }

        $this->nom = $nom;
        $this->nbFeuilles = $nbFeuilles;
        $this->allumee = false;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getNbFeuilles(): int
    {
        return $this->nbFeuilles;
    }

    public function isAllumee(): bool
    {
        return $this->allumee;
    }

    public function allumer(): void
    {
        $this->allumee = true;
    }

    public function eteindre(): void
    {
        $this->allumee = false;
    }

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
```