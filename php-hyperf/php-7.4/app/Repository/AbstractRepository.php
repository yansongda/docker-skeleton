<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Entity\AbstractEntity;
use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Model;
use Yansongda\Supports\Str;

abstract class AbstractRepository
{
    /**
     * entity.
     *
     * @var \App\Model\Entity\AbstractEntity
     */
    protected $entity;

    /**
     * Bootstrap.
     *
     * @author yansongda <me@yansongda.cn>
     */
    public function __construct(string $entity)
    {
        if (class_exists($entity)) {
            $this->entity = new $entity();
        }
    }

    /**
     * findOneBy.
     *
     * @author yansongda <me@yansongda.cn>
     */
    public function findOne(array $conditions, array $columns = ['*']): ?AbstractEntity
    {
        /** @var AbstractEntity $data */
        $data = $this->entity->newQuery()->where($conditions)->first($columns);

        return $data;
    }

    /**
     * Find soft deleted item.
     *
     * @author Eagle Luo <eagle.luo@foxmail.com>
     */
    public function findOneWithTrashed(array $conditions, array $columns = ['*']): ?Model
    {
        return $this->entity->withTrashed()->where($conditions)->first($columns);
    }

    /**
     * findBy.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param array $columns
     */
    public function find(array $conditions, ?int $offset = null, ?int $limit = null, $columns = ['*']): Collection
    {
        $query = $this->entity->newQuery()->where($conditions);

        if (!is_null($limit)) {
            $query->limit($limit);
        }

        if (!is_null($offset)) {
            $query->offset($offset);
        }

        return $query->get($columns);
    }

    /**
     * Find data by model fields.
     *
     * @author Eagle Luo <eagle.luo@foxmail.com>
     */
    public function findBy(array $conditions = [], array $orderBys = [], array $columns = ['*']): Collection
    {
        $query = $this->entity->newQuery();

        foreach ($conditions as $field => $condition) {
            if (is_array($condition)) {
                $query->whereIn($field, $condition);
            } else {
                $query->where($field, $condition);
            }
        }

        $defaultOrder = true;
        foreach ($orderBys as $field => $order) {
            $query->orderBy($field, $order);
            $defaultOrder = false;
        }

        if ($defaultOrder) {
            $query->orderBy('id', 'asc');
        }

        return $query->get($columns);
    }

    /**
     * 查找所有数据.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param string[] $columns
     */
    public function findAll(array $conditions, $columns = ['*']): Collection
    {
        return $query = $this->entity->newQuery()->where($conditions)->get($columns);
    }

    /**
     * 带上表关系查找所有数据.
     *
     * !!!When using this feature, you should always include the id column and any relevant foreign key columns in the
     * list of columns you wish to retrieve.
     *
     * @author Eagle Luo <eagle.luo@foxmail.com>
     *
     * @param array $relations 关系 eg: ['template' => ['id', 'name']]
     */
    public function findAllWithRelations(array $conditions, array $relations = [], array $columns = ['*'], ?Builder $builder = null): Collection
    {
        $query = $builder ?? $this->entity->newQuery()->where($conditions);

        foreach ($relations as $relationName => $fields) {
            if (is_int($relationName)) {
                $query->with($fields);
            } else {
                $query->with($relationName.':'.implode(',', (array) $fields));
            }
        }

        return $query->get($columns);
    }

    /**
     * count.
     *
     * @author yansongda <me@yansongda.cn>
     */
    public function count(array $conditions): int
    {
        return $this->entity->newQuery()->where($conditions)->count();
    }

    /**
     * paginate.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param array $sorts 排序；使用多维数组进行多个字段排序. 举例： [['id', 'desc'], ['created_at', 'asc']]
     */
    public function paginate(array $conditions, ?int $perPage = null, ?array $sorts = null, array $columns = ['*'], ?Builder $builder = null): LengthAwarePaginatorInterface
    {
        $query = $builder ?? $this->entity->newQuery()->where($conditions);

        if (!is_null($sorts)) {
            foreach ($sorts as $key => $value) {
                if (is_array($value)) {
                    $query->orderBy(
                        Str::snake(reset($value)),
                        Str::startsWith(end($value), 'asc') ? 'asc' : 'desc'
                    );
                }
            }
        }

        return $query->paginate($perPage ?? $this->entity->getPerPage(), $columns, 'currentPage');
    }

    /**
     * 批量赋值新建.
     *
     * @author yansongda <me@yansongda.cn>
     */
    public function store(array $data): Model
    {
        return $this->entity->newQuery()->create($data);
    }

    /**
     * 批量赋值更新.
     *
     * @author yansongda <me@yansongda.cn>
     */
    public function update(Model $entity, array $data): bool
    {
        foreach ($data as $key => $value) {
            if ($entity->isFillable($key)) {
                $entity->{$key} = $value;
            }
        }

        return $entity->save();
    }

    /**
     * 更新或创建.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param array $condition  需要查询更新的条件
     * @param array $attributes 需要更新或者新增的内容
     */
    public function updateOrCreate(array $condition, array $attributes): Model
    {
        return $this->entity->newQuery()->updateOrCreate($condition, $attributes);
    }

    /**
     * 查找一条数据，如果不存在就创建.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param array $condition  需要查询更新的条件
     * @param array $attributes 需要新增的内容
     *
     * @return AbstractEntity
     */
    public function firstOrCreate(array $condition, array $attributes): Model
    {
        return $this->entity->newQuery()->firstOrCreate($condition, $attributes);
    }

    /**
     * delete.
     *
     * @author yansongda <me@yansongda.cn>
     */
    public function delete(array $condition): void
    {
        $models = $this->find($condition, null, null);
        foreach ($models as $model) {
            $model->delete();
        }
    }

    /**
     * 根据 id 批量删除.
     *
     * @author yansongda <me@yansongda.cn>
     */
    public function deleteByIds(int $vccId, array $id): void
    {
        $data = $this->entity->newQuery()->where('vcc_id', $vccId)
            ->whereIn('id', $id)
            ->get();

        foreach ($data as $model) {
            $model->delete();
        }
    }

    /**
     * setEntity.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @return void
     */
    protected function setEntity(AbstractEntity $entity)
    {
        $this->entity = $entity;
    }
}
