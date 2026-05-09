<?php
/**
 * Classe Database - Connexion PDO Singleton
 */
class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
            ];
            
            // Si on utilise Aiven (qui requiert SSL)
            if (DB_PORT == '19032' || getenv('DB_SSL') == '1') {
                $options[PDO::MYSQL_ATTR_SSL_CA] = true; // Demander SSL
                $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false; // Désactiver la vérification stricte du certificat s'il n'est pas fourni localement
            }

            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            if (APP_DEBUG) {
                die("Erreur de connexion : " . $e->getMessage());
            }
            die("Erreur de connexion à la base de données. Veuillez réessayer.");
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    /**
     * Exécuter une requête préparée
     */
    public function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Récupérer plusieurs lignes
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Récupérer une seule ligne
     */
    public function fetch(string $sql, array $params = []): ?object
    {
        $result = $this->query($sql, $params)->fetch();
        return $result ?: null;
    }

    /**
     * Insérer et retourner l'ID
     */
    public function insert(string $sql, array $params = []): int
    {
        $this->query($sql, $params);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Compter les lignes affectées
     */
    public function execute(string $sql, array $params = []): int
    {
        return $this->query($sql, $params)->rowCount();
    }

    // Empêcher le clonage
    private function __clone() {}
}
