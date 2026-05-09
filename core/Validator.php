<?php
/**
 * Classe Validator - Validation des données
 */
class Validator
{
    private array $errors = [];
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Valider un champ requis
     */
    public function required(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        if (!isset($this->data[$field]) || trim($this->data[$field]) === '') {
            $this->errors[$field] = "Le champ {$label} est obligatoire.";
        }
        return $this;
    }

    /**
     * Valider un email
     */
    public function email(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            if (!filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
                $this->errors[$field] = "L'adresse email n'est pas valide.";
            }
        }
        return $this;
    }

    /**
     * Valider une longueur minimale
     */
    public function minLength(string $field, int $min, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && strlen(trim($this->data[$field])) < $min) {
            $this->errors[$field] = "Le champ {$label} doit contenir au moins {$min} caractères.";
        }
        return $this;
    }

    /**
     * Valider une longueur maximale
     */
    public function maxLength(string $field, int $max, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && strlen(trim($this->data[$field])) > $max) {
            $this->errors[$field] = "Le champ {$label} ne doit pas dépasser {$max} caractères.";
        }
        return $this;
    }

    /**
     * Valider que deux champs correspondent
     */
    public function matches(string $field1, string $field2, string $label = ''): self
    {
        $label = $label ?: $field2;
        if (isset($this->data[$field1]) && isset($this->data[$field2])) {
            if ($this->data[$field1] !== $this->data[$field2]) {
                $this->errors[$field2] = "Les champs ne correspondent pas.";
            }
        }
        return $this;
    }

    /**
     * Valider un numéro de téléphone camerounais
     */
    public function phone(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            $phone = preg_replace('/[^0-9+]/', '', $this->data[$field]);
            if (!preg_match('/^(\+237|237)?[6-9][0-9]{8}$/', $phone)) {
                $this->errors[$field] = "Le numéro de téléphone n'est pas valide.";
            }
        }
        return $this;
    }

    /**
     * Valider un nombre
     */
    public function numeric(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field] = "Le champ {$label} doit être un nombre.";
        }
        return $this;
    }

    /**
     * Valider un montant positif
     */
    public function positiveAmount(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field])) {
            if (!is_numeric($this->data[$field]) || (float)$this->data[$field] <= 0) {
                $this->errors[$field] = "Le montant doit être supérieur à 0.";
            }
        }
        return $this;
    }

    /**
     * Vérifier si la validation a échoué
     */
    public function fails(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Obtenir les erreurs
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Obtenir la première erreur
     */
    public function firstError(): string
    {
        return !empty($this->errors) ? reset($this->errors) : '';
    }
}
