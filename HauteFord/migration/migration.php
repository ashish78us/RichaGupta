<?php
/**
 * Gestionnaire de migration SQL
 */
// nécessaire car ce script utilise une connexion à la DB
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../app/Helpers/DB.php';
require_once __DIR__ . '/../app/Models/Model.php';
require_once __DIR__ . '/../app/Models/Migration.php';

// On récupère les paramètres de la commande
$args = $_SERVER['argv'];
// Si le nombre de paramètres ne correspond pas ou si le paramètre est de type "aide", on affiche l'aide de la commande
$help = ['--help', '-help', '-h', '-?', 'h', 'help', '?'];
if ($_SERVER['argc'] != 2 || in_array($args[1], $help)) {
    echo "Cette commande permet de migrer les fichiers SQL présents dans le dossier migration\n
    Cette commande comporte un paramètre, pouvant contenir les valeurs suivantes :
    --help|-help|-h|-?|h|help|? pour afficher cette aide
    --all pour migrer tous les fichiers SQL présents dans le dossier migration, du plus ancien au plus récent
    --update pour migrer les fichiers SQL présents dans le dossier migration et plus récents que la date de la dernière migration, du plus ancien au plus récent
    <filename> le nom du fichier SQL à exécuter (le paramètre ne contient pas l'extension du fichier).\n 
    Ce fichier doit être présent dans le dossier migration\n
    Exemples de commande :\n
    php migration.php user\n
    php migration.php --all\n
    php migration.php --update\n
    Cette commande exécutera le code SQL présent dans le(s) fichier(s) SQL";
    die;
}

// Options de migration
if ($args[1] == '--all') {
    // Toutes les migrations (dans le cas d'une nouvelle installation)
    migrationAll();
} elseif ($args[1] == '--update') {
    // migrations uniquement depuis la dernière date de migration
    $dbh = \app\Helpers\DB::connect();
    if ($dbh->query("SHOW TABLES LIKE 'migration'")) {
        // Si la table de migration existe mais qu'aucune date de migration n'a été définie, la migration reprend à partir du 8/11/2022 (hotfix inutile sur une nouvelle installation)
        if (getLastMigrationDate() == '') {
            $dbh->query("INSERT INTO migration (id, lasttime) VALUES (1, 1667892791) ON DUPLICATE KEY UPDATE lasttime = 1667892791");
        }
        migrationAll(true);
    } else {
        migration('migration');
        migrationAll(true);
    }
} else {
    // Migration d'un fichier unique : construction du path du fichier à migrer
    $file = $args[1] . '.sql';
    migration($file);
}

/**
 * Migration de tous les fichier SQL du répertoire migration, depuis le plus ancien au plus récent
 *
 * @param bool $fromLastTime    ne migre que les fichiers SQL plus récents que la dernière date de migration
 * @return void
 */
function migrationAll(bool $fromLastTime = false): void {
    $files = [];
    $path = getcwd();
    $filelist = scandir($path);
    foreach ($filelist as $tmpfile) {
        if (pathinfo($tmpfile, PATHINFO_EXTENSION) == 'sql') {
            $filetime = filemtime($path . '/' . $tmpfile);
            if (!$fromLastTime || ($fromLastTime && $filetime > getLastMigrationDate())) {
                $files[$tmpfile] = filemtime($path . '/' . $tmpfile);
            }
        }
    }
    asort($files);
    $files = array_keys($files);
    foreach ($files as $file) {
        migration($file);
    }
}


/**
 * @param string $file
 * @return void
 */
function migration(string $file): void {
    $file_path = getcwd() . '/' . $file;
    // On vérifie si le fichier existe
    if (file_exists($file_path)) {
        echo "Le fichier $file va être migré\n";
        // On exécute la fonction de migration. Le contenu du fichier SQL est directement lu et passé en paramètre de la fonction
        migrate(file_get_contents($file_path));
        echo "Le fichier $file a été migré avec succès\n";
    } else {
        echo "Ce fichier de migration n'existe pas!";
    }
}

/**
 * @param string $migration
 * @return void
 */
function migrate(string $migration):void {
    // connexion à la DB et exécution du code SQL contenu dans le fichier SQL de migration
    $dbh = \app\Helpers\DB::connect();
    $dbh->query($migration);
    // Si la table migration existe, on met à jour la date de la dernière migration
    if ($dbh->query("SHOW TABLES LIKE 'migration'")) {
        $dbh->query("INSERT INTO migration (id, lasttime) VALUES (1, UNIX_TIMESTAMP(NOW())) ON DUPLICATE KEY UPDATE lasttime = UNIX_TIMESTAMP(NOW())");
    }
}

/**
 * @return mixed
 */
function getLastMigrationDate(): mixed
{
    $dbh = \app\Helpers\DB::connect();
    $result = $dbh->prepare("SELECT lasttime FROM migration WHERE id = 1");
    $result->execute();
    return $result->fetchObject()->lasttime;
}
