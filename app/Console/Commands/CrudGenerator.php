<?php

namespace App\Console\Commands;

use App\Console\StubFile;
use Illuminate\Console\Command;
use File;
use Illuminate\Support\Str;

class CrudGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:generator
        {name : Class (singular) for example User} {path?}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create CRUD operations';

    /**
     * @var array
     */
    protected $columns;

    protected $modelName;
    protected $modelNameFirstLower;
    protected $modelNameSingularLowerCase;
    protected $modelNamePluralLowerCase;
    protected $modelNamePlural;
    protected $modelNamePluralLowerCaseDashed;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function getStub($type)
    {
        return file_get_contents(resource_path("stubs/$type.stub"));
    }

    protected function controller($name, $path='')
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNameRegular}}',
                '{{modelNameFirstLower}}',
                '{{modelNamePlural}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralLowerCaseDashed}}',
                '{{path}}'
            ],
            [
                $name,
                $this->modelNameRegular,
                $this->modelNameFirstLower,
                $this->modelNamePlural,
                $this->modelNamePluralLowerCase,
                $this->modelNameSingularLowerCase,
                $this->modelNamePluralLowerCaseDashed,
                strtolower($path),
            ],
            $this->getStub('Controller')
        );
        $pluralName =Str::plural($name);
        file_put_contents(app_path("/Http/Controllers/{$name}Controller.php"), $controllerTemplate);
    }

    protected function transformModalName($name)
    {
        $this->modelName = $name;
        $this->modelNameSingularLowerCase = strtolower($name);
        $this->modelNameFirstLower = lcfirst($name);
        $this->modelNamePluralLowerCase = strtolower(Str::plural($name));
        $this->modelNamePlural = Str::plural($name);
        $nameAsArray = preg_split('/(?=[A-Z])/', Str::plural($name), -1, PREG_SPLIT_NO_EMPTY);
        $nameRegularAsArray = preg_split('/(?=[A-Z])/', $name, -1, PREG_SPLIT_NO_EMPTY);
        if(count($nameAsArray) > 1){
            $this->modelNamePluralLowerCaseDashed = strtolower(implode('-', $nameAsArray));
        } else {
            $this->modelNamePluralLowerCaseDashed = strtolower(Str::plural($name));
        }

        if(count($nameRegularAsArray) > 1){
            $this->modelNameRegular = implode(' ', $nameAsArray);
        } else {
            $this->modelNameRegular = $name;
        }
    }

    protected function repository($name, $stubFile='')
    {
        $repositoryTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNameFirstLower}}',
                '{{modelNamePlural}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
            ],
            [
                $this->modelName,
                $this->modelNameFirstLower,
                $this->modelNamePlural,
                $this->modelNamePluralLowerCase,
                $this->modelNameSingularLowerCase,
            ],
            $this->getStub('Repository')
        );

        file_put_contents(app_path("Repository/{$name}Repository.php"), $repositoryTemplate);
    }

    protected function service($name, $stubFile='')
    {
        $serviceTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNameFirstLower}}',
                '{{modelNamePlural}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
            ],
            [
                $this->modelName,
                $this->modelNameFirstLower,
                $this->modelNamePlural,
                $this->modelNamePluralLowerCase,
                $this->modelNameSingularLowerCase,
            ],
            $this->getStub('Service')
        );

        file_put_contents(app_path("Services/{$name}{$stubFile}Service.php"), $serviceTemplate);
    }

    protected function request($name, $type)
    {
        $requestTemplate = str_replace(
            [
                '{{modelName}}', '[]'
            ],
            [
                $name, $this->validationFields($name)
            ],
            $this->getStub($type.'Request'),
        );

        if(!file_exists($path = app_path('/Http/Requests')))
            mkdir($path, 0777, true);

        file_put_contents(app_path("/Http/Requests/{$name}{$type}Request.php"), $requestTemplate);
    }

    protected function setColumns($name)
    {
        $model = $this->laravel->make("App\\Models\\".$name);
        $table = $model->getConnection()->getTablePrefix() . $model->getTable();
        $schema = $model->getConnection()->getDoctrineSchemaManager($table);
        $databasePlatform = $schema->getDatabasePlatform();
        $databasePlatform->registerDoctrineTypeMapping('enum', 'string');


        if (strpos($table, '.')) {
            list($database, $table) = explode('.', $table);
        } else {
            $database = null;
        }

        $columnDefinitions = $schema->listTableColumns($table, $database);
        $columns = [];

        foreach ($columnDefinitions as $column) {
            $columns[] = [
                'name' => $column->getName(),
                'type' => $column->getType()->getName()
            ];
        }

        $filterColumns = [
            'id'        ,
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'deleted_at',
            'deleted_by',
            'company_id',
        ];

        $this->columns = array_filter($columns, function(array $column) use ($filterColumns) {
            return !in_array($column['name'], $filterColumns);
        });

    }

    public function validationFields(){
        $validationFields = '[';

        foreach ($this->columns as $column){
            $columnName = $column['name'];
            $columnType = $column['type'];
            $validationFields = $validationFields.
                "
                '".$columnName."' => 'required|".$columnType."',
                ";
        }

        return $validationFields.']';
    }

    public function tableFields(){
        $tableFields = '';

        foreach ($this->columns as $column){
            $field = $column['name'];
            $tableFields = $tableFields.
                '{ "data": "'.$field.'"},
                ';

        }

        return $tableFields;
    }

    public function tableHeader(){
        $tableHeader = '';

        foreach ($this->columns as $column){
            $field = str_replace('_', ' ', ucfirst($column['name']));
            $tableHeader = $tableHeader.
                "<th>{{ __('{$field}' ) }}</th>
                ";
        }

        return $tableHeader;
    }

    public function formFields($name, $file){
        $formFields = '';
        $value = '';
        foreach ($this->columns as $column){
            if($file == 'edit'){
                $value = "{{ $".strtolower($name)."->".$column['name']." }}";
            }
            $formFields = $formFields.
                '<x-{{type}}
                                      name="{{fieldName}}"
                                      label="{{ __({{fieldLabel}}) }}"
                                      type="{{filedType}}"
                                      size="3"
                                      {{options}}
                                      value="{{value}}"
                                     />';

            $formFields = str_replace('{{value}}', $value, $formFields);
            if (strpos($column['name'], '_id') !== false) {
                $options = str_replace('_id', '', $column['name']);
                $formFields = str_replace('{{options}}', ':options="'.$options.'"', $formFields);
                $formFields = str_replace('{{filedType}}', 'select', $formFields);
                $formFields = str_replace('{{type}}', 'select', $formFields);
            } else{
                $formFields = str_replace('{{filedType}}', 'text', $formFields);
                $formFields = str_replace('{{type}}', 'input', $formFields);
                $formFields = str_replace('{{options}}', '', $formFields);
            }
            $formFields = str_replace('{{fieldName}}', $column['name'], $formFields);
            $formFields = str_replace('{{fieldName}}', $column['name'], $formFields);
            $formFields = str_replace('{{fieldLabel}}', "'".str_replace('_', ' ', ucfirst($column['name']))."'", $formFields);
        }

        return $formFields;
    }


    protected function makeView($name, $file, $path=null)
    {
        $template = str_replace(
            [
                '{{modelName}}',
                '{{modelNameFirstLower}}',
                '{{modelNameRegular}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralLowerCaseDashed}}',
                '{{formFields}}',
                '{{tableFields}}',
                '{{tableHeader}}',
                '{{path}}',
            ],
            [
                $this->modelName,
                $this->modelNameFirstLower,
                $this->modelNameRegular,
                $this->modelNamePluralLowerCase,
                $this->modelNameSingularLowerCase,
                $this->modelNamePluralLowerCaseDashed,
                $this->formFields($name, $file),
                $this->tableFields(),
                $this->tableHeader(),
                $path
            ],
            $this->getStub('views/'.$file)
        );
        if($path){
            if(!is_dir(resource_path('views/'.$path))){
                File::makeDirectory(resource_path('views/'.$path));
            }

            if(!is_dir(resource_path('views/'.$path.'/'.$this->modelNamePluralLowerCaseDashed))){
                File::makeDirectory(resource_path('views/'.$path.'/'.$this->modelNamePluralLowerCaseDashed));
            }

            file_put_contents(resource_path('views/'.$path.'/'.$this->modelNamePluralLowerCaseDashed.'/'.$file.'.blade.php'), $template);
        } else {
            if(!is_dir(resource_path('views/'.$this->modelNamePluralLowerCaseDashed))){
                File::makeDirectory(resource_path('views/'.$this->modelNamePluralLowerCaseDashed));
            }
            file_put_contents(resource_path('views/'.strtolower($this->modelNamePluralLowerCaseDashed.'/'.$file.'.blade.php')), $template);
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $path = '';

        if($this->argument('path')){
            $path = $this->argument('path');
        }
        $this->transformModalName($name);
        $this->setColumns($name);
        $this->repository($name);
        $this->service($name);

        $this->request($name, 'Create');
        $this->request($name, 'Update');

        $pluralName = Str::plural($name);
        $controller = "App\Http\Controllers\{$pluralName}Controller";
        if($path != ''){
            $this->controller($name, $path.'.');
        }else {
            $this->controller($name);
        }

    }
}
