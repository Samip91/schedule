<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{

    /**
     * The repository model
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Get searchable fields array
     *
     * @return array
     */
    abstract public function getFieldsSearchable();

    /**
     * The query builder
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;



    /**
     * Array of related models to eager load
     *
     * @var array
     */
    protected $with = [];

    /**
     * Array of one or more search key and value
     *
     * @var array
     */
    protected array $search = [];

    /**
     * Array of one or more where clause parameters
     *
     * @var array
     */
    protected $wheres = [];


    /**
     * Array of one or more where in clause parameters
     *
     * @var array
     */
    protected $whereIns = [];


    /**
     * Array of one or more ORDER BY column/value pairs
     *
     * @var array
     */
    protected $orderBys = [];


    /**
     * Array of scope methods to call on the model
     *
     * @var array
     */
    protected $scopes = [];


    /**
     * Alias for the query limit
     *
     * @var int
     */
    protected $limit;

    /**
     * Get all the model records in the database
     *
     * @return Collection
     */
    public function all(array $search = [], $columns = ['*']): Collection
    {

        return $this->allQuery($search)->get($columns);
    }

    public function paginate(array $search = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->allQuery($search)->paginate($perPage);
    }

    /**
     * Get all the model records in the database
     *
     * @return Builder
     */
    public function allQuery(array $search = []): Builder
    {
        $this->setSearch($search);

        $this->newQuery()->eagerLoad()->setClauses($this->search)->setScopes();

        $query = $this->query;

        return $query;
    }

    public function setSearch(array $search)
    {
        $this->search = $search;
        return $this;
    }

    /**
     * Create a new model record in the database
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data): Model
    {
        $this->unsetClauses();

        return $this->model->create($data);
    }

    /**
     * Update model record for given id
     *
     * @param array $input
     * @param int $id
     *
     * @return bool
     */
    public function updateById(array $input, int $id): bool
    {

        $model = $this->getById($id);

        $model->fill($input);

        return $model->save();
    }

    public function updateWhereIn(string $column, array $values, array $input, array $scopes = []): int
    {
        $query = $this->model->whereIn($column, $values);

        foreach ($scopes as $method) {
            $query->$method();
        }

        return $query->update($input);
    }

    public function deleteWhereIn(string $column, array $values,  array $scopes = []): int
    {
        $query = $this->model->whereIn($column, $values);

        foreach ($scopes as $method) {
            $query->$method();
        }

        return $query->forceDelete();
    }
    /**
     * delete record for given model
     *
     * @param model $model
     *
     * @return bool
     */
    public function deleteModel(Model $model): bool
    {
        return $model->delete();
    }
    /**
     * Update model record for given model
     *
     * @param array $input
     * @param model $model
     *
     * @return bool
     */
    public function updateModel(Model $model, array $input): bool
    {

        $model->fill($input);

        return $model->save();
    }

    public function upsertData(array $data, array $uniqueBy, array $update = []): int
    {
        return $this->model->upsert($data, $uniqueBy, $update);
    }

    /**
     * Set the query limit
     *
     * @param int $limit
     *
     * @return $this
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }


    /**
     * Create a new instance of the model's query builder
     *
     * @return $this
     */
    protected function newQuery(): self
    {
        $this->query = $this->model->newQuery();

        return $this;
    }

    /**
     * Get the specified model record from the database
     *
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getById($id)
    {
        $this->unsetClauses();

        $this->newQuery()->eagerLoad();

        return $this->query->findOrFail($id);
    }
    /**
     * Add relationships to the query builder to eager load
     *
     * @return $this
     */
    protected function eagerLoad(): self
    {
        foreach ($this->with as $relation) {
            $this->query->with($relation);
        }

        return $this;
    }


    /**
     * Set an ORDER BY clause
     *
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function orderBy($column, $direction = 'asc'): self
    {
        $this->orderBys[] = compact('column', 'direction');

        return $this;
    }


    /**
     * Set query scopes
     *
     * @return $this
     */
    protected function setScopes(): self
    {
        foreach ($this->scopes as $method) {
            $this->query->$method();
        }

        return $this;
    }

    public function whereIn($column, $values): self
    {
        $values = is_array($values) ? $values : [$values];

        $this->whereIns[] = compact('column', 'values');

        return $this;
    }

    /**
     * Set clauses on the query builder
     *
     * @return $this
     */
    protected function setClauses(array $search = []): self
    {
        if (count($search)) {
            $this->setSearchClause($search);
        }

        return $this;
    }


    public function setSearchClause(array $search): self
    {
        $columns = \array_keys($this->getFieldsSearchable());
        foreach ($search as $key => $value) {

            if (in_array($key, $columns))
                if ($this->getFieldsSearchable()[$key] != 'like') {
                    $this->query->where($key, $this->getFieldsSearchable()[$key], $value);
                } else {
                    $value = $value;
                    $this->query->where($key, 'like', "%$value%");
                }
        }

        return $this;
    }
    /**
     * Reset the query clause parameter arrays
     *
     * @return $this
     */
    protected function unsetClauses(): self
    {
        $this->wheres   = [];
        $this->whereIns = [];
        $this->scopes   = [];
        $this->limit     = null;

        return $this;
    }
}
