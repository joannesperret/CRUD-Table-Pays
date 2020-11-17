<?php

/*
  VillesDAOProcedural.php
 */
/*
  LE DAO de la table [villes] de la BD [cours]
 * PAS DE CONNEXION DANS UN DAO
 * PAS DE GESTION DE TRANSACTION DANS UN DAO
 * PAS D'AFFICHAGE DANS UN DAO
 */

/**
 *
 * @param PDO $pdo
 * @return array
 */
function selectAll(PDO $pdo): array {
    /*
     * Renvoie un tableau ordinal de tableaux associatifs
     * POUR LES COMMENTS :
     * SELECT COLUMN_COMMENT 
     * FROM information_schema.`COLUMNS` 
     * WHERE TABLE_SCHEMA = 'cours' AND TABLE_NAME = 'villes';
     */
    $list = array();
    try {
        $cursor = $pdo->query("SELECT * FROM villes ORDER BY nom_ville");
        // Renvoie un tableau ordinal de tableaux associatifs
        $list = $cursor->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $message = array("message" => $e->getMessage());
        $list[] = $message;
        //$list["message"] = $e->getMessage();
    }
    return $list;
}

/**
 *
 * @param PDO $pdo
 * @param string $id
 * @return array
 */
function selectOnePays(PDO $pdo, string $nom_pays): array {
    /*
     * Renvoie un tableau associatif
     */
    try {
        $sql = "SELECT * FROM pays WHERE nom_pays = ?";
        $cursor = $pdo->prepare($sql);
        $cursor->bindValue(1, $nom_pays);
        $cursor->execute();
        // Renvoie un tableau associatif
        $line = $cursor->fetch(PDO::FETCH_ASSOC);
        if ($line === FALSE) {
            $line["nom_pays"] = "Enregistement inexistant !";
            $line["id_pays"] = "";
        }
        $cursor->closeCursor();
    } catch (PDOException $e) {
        //$line["Error"] = $e->getMessage();
        $line["Error"] = "Une erreur s'est produite, veuillez contacter votre administrateur";
        //$line = null;
    }
    return $line;
}

/**
 *
 * @param PDO $pdo
 * @param array $tAttributesValues
 * @return int
 */
function insert(PDO $pdo, array $tbtnSaisi): int {
    $affected = 0;
    try {
        $sql = "INSERT INTO pays(id_pays,nom_pays) VALUES(?,?)";
        $statement = $pdo->prepare($sql);

        $statement->bindValue(1, $tbtnSaisi[0]);
        $statement->bindValue(2, $tbtnSaisi[1]);
        
        $statement->execute();
        $affected = $statement->rowcount();
    } catch (PDOException $e) {
        $affected = -1;
    }
    return $affected;
}

/**
 *
 * @param PDO $pdo
 * @param string $id
 * @return int
 */
function delete(PDO $pdo, string $Pays): int {
    $affected = 0;
    try {
        $sql = "DELETE FROM pays WHERE nom_pays = ?";
        
        $statement = $pdo->prepare($sql);
        $statement->bindValue(1, $Pays);
        $statement->execute();

        $affected = $statement->rowcount();
    } catch (PDOException $e) {
        $affected = -1;
    }
    return $affected;
}

/**
 *
 * @param PDO $pdo
 * @param array $tAttributesValues
 * @return int
 */
function update(PDO $pdo, array $tbtnSaisi): int {
    $affected = 0;
    try {
        $sql = "UPDATE pays SET nom_pays = ?, id_pays = ? WHERE id_pays = ?";
        $statement = $pdo->prepare($sql);

        $statement->bindValue(1, $tbtnSaisi[0]);
        $statement->bindValue(2, $tbtnSaisi[1]);
        $statement->bindValue(3, $tbtnSaisi[2]);

        $statement->execute();
        $affected = $statement->rowcount();
    } catch (PDOException $e) {
        $affected = -1;
    }
    return $affected;
}
