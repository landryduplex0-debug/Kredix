<?php
/**
 * Classe Model de base
 */
class Model
{
    protected Database $db;
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Trouver par ID
     */
    public function find(int $id): ?object
    {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?",
            [$id]
        );
    }

    /**
     * Trouver tous les enregistrements
     */
    public function all(string $orderBy = 'id DESC'): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} ORDER BY {$orderBy}"
        );
    }

    /**
     * Trouver par condition
     */
    public function where(string $column, $value): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE {$column} = ? ORDER BY id DESC",
            [$value]
        );
    }

    /**
     * Trouver le premier par condition
     */
    public function findBy(string $column, $value): ?object
    {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1",
            [$value]
        );
    }

    /**
     * Compter les enregistrements
     */
    public function count(string $condition = '1=1', array $params = []): int
    {
        $result = $this->db->fetch(
            "SELECT COUNT(*) as total FROM {$this->table} WHERE {$condition}",
            $params
        );
        return $result ? (int)$result->total : 0;
    }

    /**
     * Créer un enregistrement
     */
    public function create(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        return $this->db->insert(
            "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})",
            array_values($data)
        );
    }

    /**
     * Mettre à jour un enregistrement
     */
    public function update(int $id, array $data): int
    {
        $setClause = implode(' = ?, ', array_keys($data)) . ' = ?';
        $values = array_values($data);
        $values[] = $id;
        
        return $this->db->execute(
            "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = ?",
            $values
        );
    }

    /**
     * Supprimer un enregistrement
     */
    public function delete(int $id): int
    {
        return $this->db->execute(
            "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?",
            [$id]
        );
    }

    /**
     * Paginer les résultats
     */
    public function paginate(int $page = 1, int $perPage = 15, string $condition = '1=1', array $params = []): array
    {
        $offset = ($page - 1) * $perPage;
        $total = $this->count($condition, $params);
        $totalPages = ceil($total / $perPage);
        
        $items = $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE {$condition} ORDER BY id DESC LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        return [
            'items'       => $items,
            'total'       => $total,
            'page'        => $page,
            'per_page'    => $perPage,
            'total_pages' => $totalPages,
            'has_prev'    => $page > 1,
            'has_next'    => $page < $totalPages
        ];
    }
}
