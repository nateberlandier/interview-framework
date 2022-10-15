<?php

declare(strict_types = 1);

namespace Example\View;

use Example\Model\ExampleModel;
use Mini\Controller\Exception\BadInputException;

/**
 * Example view builder.
 */
class ExampleView
{
    /**
     * Example data.
     * 
     * @var Example\Model\ExampleModel|null
     */
    protected $model = null;

    /**
     * Setup.
     * 
     * @param ExampleModel $model example data
     */
    public function __construct(ExampleModel $model)
    {
        $this->model = $model;
    }

    /**
     * Get the example view to display its data.
     * 
     * @param ExampleModel $model
     * 
     * @return string view template
     *
     * @throws BadInputException if model does not exist in db
     */
    public function get(ExampleModel $model): string
    {
        $this->model = $model;
        
        //hitting the db is required at least once, use requirement as a content check
        $compare = $this->model->get($this->model->fields['id']);
        
        //if our model as a parameter does not match entry in db then it does not exist
        if (!$this->model->fields == $compare) {
            throw new BadInputException('Unknown example ID');
        }

        return view('app/example/detail', $this->model->fields);
    }
}
