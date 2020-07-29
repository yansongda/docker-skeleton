<?php

declare(strict_types=1);

namespace App\Service\Api;

use App\Constants\ErrorCode;
use App\Exception\ApiException;
use App\Model\Entity\AbstractEntity;
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Model;

/**
 * @author yansongda <me@yansongda.cn>
 */
abstract class AbstractService
{
    /**
     * repository.
     *
     * @var \App\Repository\AbstractRepository
     */
    protected $repository;

    /**
     * all.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @return \App\Model\Entity\AbstractEntity[]|Collection
     */
    public function all(array $condition, $columns = ['*']): Collection
    {
        return $this->repository->findAll(...func_get_args());
    }

    /**
     * 获取所有数据带上关系.
     *
     * @author yansongda <me@yansongda.cn>
     */
    public function allWithRelations(array $conditions, array $relations = [], array $columns = ['*']): Collection
    {
        return $this->repository->findAllWithRelations(...func_get_args());
    }

    /**
     * count.
     *
     * @author yansongda <me@yansongda.cn>
     */
    public function count(array $condition): int
    {
        return $this->repository->count($condition);
    }

    /**
     * findOne.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @throws \App\Exception\ApiException
     */
    public function findOne(array $condition, array $columns = ['*'], ?string $throw = null): ?AbstractEntity
    {
        $entity = $this->repository->findOne($condition, $columns);

        if (is_null($entity) && !is_null($throw)) {
            throw new ApiException(ErrorCode::DATA_NOT_FOUND, $throw);
        }

        return $entity;
    }

    /**
     * Find soft deleted item.
     *
     * @author Eagle Luo <eagle.luo@foxmail.com>
     */
    public function findOneWithTrashed(array $conditions, array $columns = ['*']): ?Model
    {
        return $this->repository->findOneWithTrashed(...func_get_args());
    }

    /**
     * 批量赋值新增.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param array $data 二维数组，批量创建
     *
     * @return \App\Model\Entity\AbstractEntity[]|Collection
     */
    public function create(int $vccId, array $data): Collection
    {
        $results = [];

        foreach ($data as $item) {
            $results[] = $this->repository->store(
                array_merge(['vcc_id' => $vccId], $item)
            );
        }

        return new Collection($results);
    }

    /**
     * 单一新增
     * Create a single record.
     *
     * @author Eagle Luo <eagle.luo@foxmail.com>
     */
    public function createOne(int $vccId, array $data): Model
    {
        $data['vcc_id'] = $vccId;

        return $this->repository->store($data);
    }

    /**
     * Update a single record.
     *
     * @author Eagle Luo <eagle.luo@foxmail.com>
     *
     * @throws \App\Exception\ApiException
     *
     * @return bool|Model
     */
    public function updateOne(array $conditions, array $data, bool $getUpdated = false)
    {
        $model = $this->findOne($conditions, ['*'], '');

        $res = $this->repository->update($model, $data);

        if ($res && $getUpdated) {
            return $model->fresh();
        }

        return $res;
    }

    /**
     * 批量赋值更新.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @throws \App\Exception\ApiException
     */
    public function update(array $condition, array $data): bool
    {
        $models = $this->all($condition);

        if (0 === $models->count()) {
            throw new ApiException(ErrorCode::DATA_NOT_FOUND);
        }

        foreach ($models as $model) {
            $this->repository->update($model, $data);
        }

        return true;
    }

    /**
     * 更新或新增.
     *
     * @author yansongda <me@yansongda.cn>
     */
    public function updateOrCreate(array $condition, array $data): Model
    {
        return $this->repository->updateOrCreate(...func_get_args());
    }

    /**
     * 查询或新增.
     *
     * @author yansongda <me@yansongda.cn>
     */
    public function firstOrCreate(array $condition, array $data): AbstractEntity
    {
        return $this->repository->firstOrCreate($condition, $data);
    }

    /**
     * delete.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @throws \Exception
     */
    public function delete(array $condition): void
    {
        if (!key_exists('vcc_id', $condition)) {
            return;
        }

        if (isset($condition['ids']) && is_array($condition['ids']) && count($condition['ids']) > 0) {
            $this->repository->deleteByIds($condition['vcc_id'], $condition['ids']);

            return;
        }

        $this->repository->delete($condition);
    }

    /**
     * paginate.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param string|int|null $perPage
     */
    public function paginate(array $condition, $perPage = null, ?array $sorts = null, array $columns = ['*']): array
    {
        $condition = (new \Yansongda\Supports\Collection($condition))
                        ->except(['per_page', 'current_page', 'field', 'order'])
                        ->toArray();

        $data = $this->repository->paginate(
            $condition,
            is_null($perPage) ? null : intval($perPage),
            $sorts,
            $columns
        );

        return [
            'currentPage' => $data->currentPage(),
            'totalPage' => intval(ceil($data->total() / $data->perPage())),
            'perPage' => $data->perPage(),
            'total' => $data->total(),
            'empty' => $data->isEmpty(),
            'data' => $data->items(),
        ];
    }
}
