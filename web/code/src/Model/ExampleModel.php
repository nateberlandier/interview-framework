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
    public $fields = [
        "id" => "",
        "created" => "",
        "code" => "",
        "description" => ""
    ];
    
    public function getField(string $field): string {
        if (isset($this->fields[$field])) {
            return $this->fields[$field];
        }
        throw new BadInputException($field." does not exist in model object");
    }
    
    public function setField(string $field, string $value): string {
        if (array_key_exists($field, $this->fields)) {
            $this->fields[$field] = $value;
        }
        throw new BadInputException($field." field does not exist, cannot set in model");
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
     * @param string $created     example created on
     * @param string $code        example code
     * @param string $description example description
     *  
     * @return int example id
     */
    public function create()
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
