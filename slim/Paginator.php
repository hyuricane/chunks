<?php
/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 20/07/16
 * Time: 14:04
 */

namespace App\Utils;


use Illuminate\Database\Eloquent\Builder;
use Slim\Slim;

class Paginator
{
    var $count = 0;
    var $items = array();
    var $next = null;
    var $prev = null;
    var $currentPage = 1;
    var $totalPage;

    var $perPage = 15;

    var $builder = null;


    static function make (Builder $builder, $perpage = 15){
        return new self($builder, $perpage);
    }

    /**
     * Paginator constructor.
     */
    private function __construct(Builder $builder, $perpage = 15)
    {
        $this->app = Slim::getInstance();

        $params = $this->app->request->params();

        $this->perPage = $perpage;
        $this->count = $builder->count();
        $this->totalPage = ceil($this->count / $this->perPage);

        $this->currentPage = intval($this->app->request->params("page", 1));
        $this->items = $builder->take($this->perPage)->skip($this->perPage * ($this->currentPage - 1))->get()->toArray();

        $baseUrl = $this->app->request->getUrl();

        if ($this->totalPage - $this->currentPage > 0){
            $nextparams = $params;
            $nextparams["page"] = $this->currentPage + 1;
            $nextparamsflat = array();
            foreach ($nextparams as $key=>$value){
                $nextparamsflat[] = "$key=$value";
            }

            $this->next = $baseUrl . "?" . join("?", $nextparamsflat);
        }
        if ($this->currentPage > 1){
            $prevparams = $params;
            $prevparams["page"] = $this->currentPage - 1;
            $prevparamsflat = array();
            foreach ($prevparams as $key=>$value){
                $prevparamsflat[] = "$key=$value";
            }

            $this->prev = $baseUrl . "?" . join("?", $prevparamsflat);
        }

    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }



    /**
     * @return null
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * @return null
     */
    public function getPrev()
    {
        return $this->prev;
    }

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @return float
     */
    public function getTotalPage()
    {
        return $this->totalPage;
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

}