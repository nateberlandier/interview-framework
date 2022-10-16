<?php

declare(strict_types = 1);

namespace Example\Model;

use Mini\Model\Model;
use Mini\Controller\Exception\BadInputException;

/**
 * Example data.
 */
class ExampleModel extends Model
{
    //array that will be used in object to hold model data
    public $fields = [
        "id" => "",
        "created" => "",
        "code" => "",
        "description" => ""
    ];
    
    //get field value of model array if it has a value
    public function getField(string $field): string {
        if (!array_key_exists($field, $this->fields)) {
            throw new BadInputException($field." does not exist");
        }
        
        if ($this->fields[$field] == "") {
            throw new BadInputException($field." is not set in model object");
        }
        
        return $this->fields[$field];
    }
    
    //set field in model array if the key exists
    public function setField(string $field, string $value): void {
        if (array_key_exists($field, $this->fields)) {
            $this->fields[$field] = $value;
        } else {
            throw new BadInputException($field." field does not exist, cannot set in model object");
        }
    }
    
    /**
     * Get example data by ID.
     *
     * @param int $id example id
     *  
     * @return array example data
     */
    public function get(int $id): array
    {
        $sql = '
            SELECT
                example_id AS "id",
                created,
                code,
                description
            FROM
                ' . getenv('DB_SCHEMA') . '.master_example
            WHERE
                example_id = ?';

        return $this->db->select([
            'title'  => 'Get example data',
            'sql'    => $sql,
            'inputs' => [$id]
        ]);
    }

    /**
     * Create an example.
     *
     * Saves returned id from db into model object
     */
    public function create(): void
    {
        $sql = '
            INSERT INTO
                ' . getenv('DB_SCHEMA') . '.master_example
            (
                created,
                code,
                description
            )
            VALUES
            (?,?,?)';

        $id = $this->db->statement([
            'title'  => 'Create example',
            'sql'    => $sql,
            'inputs' => [
                $this->fields["created"],
                $this->fields["code"],
                $this->fields["description"]
            ]
        ]);

        $this->db->validateAffected();

        $this->fields["id"] = $id;
    }
}
