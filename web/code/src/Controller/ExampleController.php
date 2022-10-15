<?php

declare(strict_types = 1);

namespace Example\Controller;

use Example\Model\ExampleModel;
use Example\View\ExampleView;
use Mini\Controller\Controller;
use Mini\Controller\Exception\BadInputException;
use Mini\Http\Request;

/**
 * Example entrypoint logic.
 */
class ExampleController extends Controller
{
    /**
     * Example view model.
     * 
     * @var Example\Model\ExampleModel|null
     */
    protected $model = null;

    /**
     * Example view builder.
     * 
     * @var Example\View\ExampleView|null
     */
    protected $view = null;

    /**
     * Setup.
     * 
     * @param ExampleModel $model example data
     * @param ExampleView  $view  example view builder
     */
    public function __construct(ExampleModel $model, ExampleView $view)
    {
        $this->model = $model;
        $this->view  = $view;
    }

    /**
     * Create an example and display its data.
     * 
     * @param Request $request http request
     * 
     * @return string view template
     */
    public function createExample(Request $request): string
    {
        $this->model->fields['created'] = now();
        $this->model->fields['code'] = $request->request->get('code');
        $this->model->fields['description'] = $request->request->get('description');
        
        if (! $this->model->fields['code']){
            throw new BadInputException('Example code missing');
        }

        if (! $this->model->fields['description']) {
            throw new BadInputException('Example description missing');
        }

        $this->model->create();
        return $this->view->get(
            $this->model
        );
    }
}
