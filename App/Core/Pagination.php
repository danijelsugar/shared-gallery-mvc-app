<?php

namespace Gallery\Core;

class Pagination
{
    private $model;
    private int $pagesLimit;

    public function __construct($model, int $pagesLimit)
    {
        $this->model = $model;
        $this->pagesLimit = $pagesLimit;
    }

    public function getPageNumber()
    {
        $pageNumber = $this->model->numberOfRecords();

        if ($pageNumber < 1) {
            return 1;
        }

        return ceil($pageNumber / $this->pagesLimit);
    }

    public function getOffset($page)
    {
        if ($page < 1) {
            $page = 1;
        }

        $offset = ($page * $this->pagesLimit - $this->pagesLimit);

        return $offset;
    }
}