<?php

namespace Modules\Core\Http\Services\Information;

use App\Config\Cache\BlogCache;
use App\Http\Contracts\Blog\BlogServiceInterface;
use App\Http\Contracts\Configuration\MobileSettingServiceInterface;
use App\Http\Contracts\Image\ImageServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Information\Blog;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Location\LocationCity;
use Modules\Core\Http\Facades\PsCache;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class BlogService extends PsService implements BlogServiceInterface
{
    protected $blogApiRelation;

    public function __construct(
        protected ImageServiceInterface $imageService,
        protected CoreFieldFilterSettingService $coreFieldFilterSettingService,
        protected MobileSettingServiceInterface $mobileSettingService
    ) {
        $this->blogApiRelation = ['city', 'cover'];
    }

    public function save($blogData, $blogImage)
    {

        DB::beginTransaction();

        try {

            $blog = $this->saveBlog($blogData);

            $imgData = $this->prepareSaveImageData($blog->id);

            $this->imageService->save($blogImage, $imgData);

            PsCache::clear(BlogCache::BASE);

            DB::commit();
        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function update($id, $blogData, $blogImageId, $blogImage)
    {

        DB::beginTransaction();

        try {
            $blog = $this->updateBlog($id, $blogData);

            $imgData = $this->prepareSaveImageData($blog->id);

            $this->imageService->update($blogImageId, $blogImage, $imgData);

            PsCache::clear(BlogCache::BASE);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $this->imageService->deleteAll($id, Constants::blogCoverImgType);

            $name = $this->deleteBlog($id);

            PsCache::clear(BlogCache::BASE);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $name]),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function get($id, $relation = null)
    {
        $param = [$id, $relation];
        return PsCache::remember(
            [BlogCache::BASE],
            BlogCache::GET_ALL_EXPIRY,
            $param,
            function () use ($id, $relation) {
                return Blog::where(Blog::id, $id)
                    ->when($relation, function ($q, $relation) {
                        $q->with($relation);
                    })->first();
            }
        );
    }

    public function getAll($relation = null, $status = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null)
    {
        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }

        $param = [$relation, $status, $limit, $offset, $noPagination, $pagPerPage, $conds, $sort];

        return PsCache::remember(
            [BlogCache::BASE],
            BlogCache::GET_ALL_EXPIRY,
            $param,
            function () use ($relation, $status, $limit, $offset, $noPagination, $pagPerPage, $conds, $sort) {
                $blogs = Blog::select(Blog::tableName . '.*')
                    ->when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($sort) {
                        if ($sort == Blog::locationCityId . '@@name') {
                            $q->join(LocationCity::tableName, LocationCity::tableName . '.' . LocationCity::id, '=', Blog::locationCityId);
                            $q->select(LocationCity::tableName . '.' . LocationCity::name . ' as city_name', Blog::tableName . '.*');
                        }
                    })
                    ->when($relation, function ($q, $relation) {
                        $q->with($relation);
                    })
                    ->when($status, function ($q, $status) {
                        $q->where(Blog::status, $status);
                    })
                    ->when($limit, function ($query, $limit) {
                        $query->limit($limit);
                    })
                    ->when($offset, function ($query, $offset) {
                        $query->offset($offset);
                    })
                    ->when($conds, function ($query, $conds) {
                        $query = $this->searching($query, $conds);
                    })
                    ->when(empty($sort), function ($query) {
                        $query->orderBy(Blog::tableName . '.added_date', 'desc')->orderBy(Blog::tableName . '.' . Blog::status, 'desc')->orderBy(Blog::tableName . '.' . Blog::name, 'asc');
                    });
                if ($pagPerPage) {
                    return $blogs->paginate($pagPerPage)->onEachSide(1)->withQueryString();
                } elseif ($noPagination) {
                    return $blogs->get();
                }
            }
        );
    }

    public function setStatus($id, $status)
    {
        try {
            $status = $this->prepareUpdateStausData($status);

            PsCache::clear(BlogCache::BASE);

            return $this->updateBlog($id, $status);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparations
    //-------------------------------------------------------------------
    private function prepareSaveImageData($id)
    {
        return [
            'img_parent_id' => $id,
            'img_type' => Constants::blogCoverImgType,
        ];
    }

    private function prepareUpdateStausData($status)
    {
        return ['status' => $status];
    }

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------
    private function saveBlog($blogData)
    {
        $blog = new Blog();
        $blog->fill($blogData);
        $blog->added_user_id = Auth::user()->id;
        $blog->save();

        return $blog;
    }

    private function updateBlog($id, $blogData)
    {
        $blog = $this->get($id);
        $blog->updated_user_id = Auth::user()->id;
        $blog->update($blogData);

        return $blog;
    }

    private function deleteBlog($id)
    {
        $blog = $this->get($id);
        $name = $blog->name;
        $blog->delete();

        return $name;
    }

    private function searching($query, $conds)
    {
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(Blog::tableName . '.' . Blog::name, 'like', '%' . $search . '%');
            });
        }

        if (isset($conds[Blog::locationCityId]) && $conds[Blog::locationCityId]) {
            $city_filter = $conds[Blog::locationCityId];
            $query->whereHas('city', function ($q) use ($city_filter) {
                $q->where(Blog::tableName . '.' . Blog::locationCityId, $city_filter);
            });
        }

        if (isset($conds['added_user_id']) && $conds['added_user_id']) {
            $query->where(Blog::tableName . '.' . Blog::addedUserId, $conds['added_user_id']);
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

            if ($conds['order_by'] == 'id') {
                $query->orderBy(Blog::tableName . '.' . Blog::id, $conds['order_type']);
            } else if ($conds['order_by'] == Blog::locationCityId . '@@name') {
                $query->orderBy('city_name', $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        }

        return $query;
    }
}
