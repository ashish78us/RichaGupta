<?php

namespace app\Controllers;

use stdClass;
use app\Models\Model;

abstract class Controller
{
    protected int $id;
    protected mixed $data;
    protected Model $model;    
    protected static mixed $static_model = null;

    /**
     * Lors de l'instanciation de la classe, lie le Model associé en tant que propriété statique du Controller
     *
     * @param int|null $id
     */
    public function __construct(int $id = null)
    {
        //var_dump("Inside __construct of controller");
        $class = get_called_class();
        $data = explode('\\', $class);
        $class = '\app\Models\\' . end($data);
        //var_dump("model=".$class);
        $this->model = new $class();
        self::$static_model = $this->model;
        if (!empty($id)) {
            $this->id = $id;
            $this->data = $this->model->get($id);
        }
    }

    /**
     * Récupère, sous forme d'objet, le contenu du record en DB sur base de son id
     *
     * @param int $id
     * @return mixed
     */
    public function get(int $id): mixed
    {
        //var_dump($this);
        return $this->model->getByField('id', $id);
    }

    /**
     * Injecte les propriétés d'un objet comme propriétés de l'objet instancié du Controller
     *
     * @param stdClass $object
     * @return void
     */
    public function import(stdClass $object): void
    {
        foreach (get_object_vars($object) as $key => $value) {
            $this->$key = $value;
        }
    }

}
