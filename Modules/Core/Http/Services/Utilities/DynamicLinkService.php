<?php

namespace Modules\Core\Http\Services\Utilities;

use App\Config\Cache\DynamicLinkCache;
use App\Config\Cache\ItemCache;
use App\Http\Contracts\Utilities\DynamicLinkServiceInterface;
use App\Http\Services\PsService;
use Modules\Core\Entities\DynamicLink;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Facades\PsCache;
use App\Http\Contracts\Configuration\SettingServiceInterface;
use App\Config\ps_constant;
use App\Http\Contracts\Utilities\ChunkUpdateServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DynamicLinkService extends PsService implements DynamicLinkServiceInterface
{
    public function __construct(protected SettingServiceInterface $settingService, protected ChunkUpdateServiceInterface $chunkUpdateService) {}

    public function get($shortCode = null)
    {
        $param = [$shortCode];
        return PsCache::remember([DynamicLinkCache::GET_KEY, $shortCode], DynamicLinkCache::GET_EXPIRY, $param, function () use ($shortCode) {
            return DynamicLink::when($shortCode, function ($q, $shortCode) {
                $q->where(DynamicLink::shortCode, $shortCode);
            })->first();
        });
    }
    public function getAll($shortCode = null, $type = null, $conds = null)
    {
        $param = [$shortCode, $type, $conds];
        return PsCache::remember([DynamicLinkCache::GET_ALL_KEY], DynamicLinkCache::GET_ALL_EXPIRY, $param, function () use ($shortCode, $type, $conds) {
            return DynamicLink::when($shortCode, function ($q, $shortCode) {
                $q->where(DynamicLink::shortCode, $shortCode);
            })->when($type, function ($q, $type) {
                $q->where(DynamicLink::type, $type);
            })->when($conds, function ($q, $conds) {
                $this->searching($q, $conds);
            })->get();
        });
    }

    public function getDeepLinkServiceProvider()
    {
        $dynamicLinkConfig = $this->settingService->get(env: ps_constant::DYNAMIC_LINK_CONFIG);
        $dynamicLinkSetting = json_decode($dynamicLinkConfig->setting, true);
        return $dynamicLinkSetting['default_dynamic_link']['id'];
    }

    public function getDynamicLinkRedirectData($shortCode)
    {
        // Dynamic Link Configs from psx backend settings + psx settings table
        $dynamicLinkSetting = json_decode($this->settingService->get(env: ps_constant::DYNAMIC_LINK_CONFIG)['setting'], true);

        // Default Web URL
        $webUrl = route('dashboard');

        $dynamicLink = $this->get($shortCode);

        if (!$dynamicLink) {
            return redirect()->to($webUrl);
        }

        $appName = $dynamicLinkSetting['scheme_name'];
        $appRedirect = "$appName://open";

        if ($dynamicLink->parameters) {
            $parameters = json_decode($dynamicLink->parameters, true);
            $appRedirect .= "?" . http_build_query($parameters);
        }

        if (ps_constant::DYNAMIC_LINKS[$dynamicLink->type]) {
            $webUrl = route(ps_constant::DYNAMIC_LINKS[$dynamicLink->type], $parameters);
        }

        return [
            'appRedirect' => $appRedirect,
            'iosPackageId' => $dynamicLinkSetting['apple_id'],
            'appPackageId' => $dynamicLinkSetting['android_package'],
            'webRedirect' => $webUrl
        ];
    }

    public function generateDynamicLinks($collections, $queryColumnMap, $type)
    {
        try {
            if (!$collections instanceof \Illuminate\Support\Collection) {
                $collections = collect(is_array($collections) ? $collections : [$collections]);
            }
            $existingDynamicLinks = $this->getAll(type: $type)->pluck(DynamicLink::shortCode, DynamicLink::shortCode)->toArray();

            $bulkCreateDynamicLinks = [];
            $bulkUpdateDynamicLinks = [];

            // Loop through model and make create/update data for dynamic link and update data for model
            foreach ($collections as $model) {
                $shortCode = $this->generateShortCode($model, $type);
                $parametersJson = $this->generateParametersJson($model, $queryColumnMap);

                if (isset($existingDynamicLinks[$shortCode])) {
                    $bulkUpdateDynamicLinks[] = $this->prepareBulkUpdateData($shortCode, $parametersJson);
                } else {
                    $bulkCreateDynamicLinks[] = $this->prepareBulkCreateData($shortCode, $parametersJson, $type);
                }
            }

            // Perform bulk insert and update on dynamic link
            $createdLinks = collect($this->bulkCreateTable($bulkCreateDynamicLinks) ?? []);
            $updatedLinks = collect($this->bulkUpdateTable($bulkUpdateDynamicLinks) ?? []);

            $dynamicLinks = $createdLinks->merge($updatedLinks);
            PsCache::clear(DynamicLinkCache::GET_ALL_KEY);

            return $dynamicLinks;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    /**
     * Generate the shortCode for a given model and type.
     *
     * @param $model
     * @param string $type
     * @return string
     */
    private function generateShortCode($model, $type)
    {
        return substr($type, 0, 2) . '-' . $this->encodeBase62($model->id);
    }

    private function encodeBase62($num)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base = strlen($characters);
        $encoded = '';

        while ($num > 0) {
            $encoded = $characters[$num % $base] . $encoded;
            $num = floor($num / $base);
        }

        return $encoded ?: '0'; // If ID is 0, return '0'
    }

    /**
     * Generate the parameters JSON for a given model and query column map.
     *
     * @param $model
     * @param array $queryColumnMap
     * @return string
     */
    private function generateParametersJson($model, $queryColumnMap)
    {
        $parameters = [];
        foreach ($queryColumnMap as $queryParam => $column) {
            $parameters[$queryParam] = (string) $model[$column];
        }

        return json_encode($parameters, JSON_UNESCAPED_SLASHES);
    }

    /**
     * Prepare the bulk update data for a dynamic link.
     *
     * @param string $shortCode
     * @param string $parametersJson
     * @return array
     */
    private function prepareBulkUpdateData($shortCode, $parametersJson)
    {
        return [
            DynamicLink::shortCode => $shortCode,
            DynamicLink::parameters => $parametersJson,
            DynamicLink::updatedUserId => Auth::id() ?? '0'
        ];
    }

    /**
     * Prepare the bulk create data for a dynamic link.
     *
     * @param string $shortCode
     * @param string $parametersJson
     * @param string $type
     * @return array
     */
    private function prepareBulkCreateData($shortCode, $parametersJson, $type)
    {
        return [
            DynamicLink::shortCode => $shortCode,
            DynamicLink::type => $type,
            DynamicLink::parameters => $parametersJson,
            DynamicLink::addedUserId => Auth::id() ?? '0'
        ];
    }

    /**
     * Bulk insert dynamic links into the database.
     *
     * @param array $createData
     */
    private function bulkCreateTable(array $createData)
    {
        if (empty($createData)) return;
        DB::beginTransaction();
        try {
            // Bulk insert
            DB::table(DynamicLink::tableName)->insert($createData);
            DB::commit();
            $dynamicLinks = $this->getAll(conds: ['short_codes' => array_column($createData, DynamicLink::shortCode)]);

            return $dynamicLinks;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function bulkUpdateTable(array $updateData)
    {
        if (empty($updateData)) return;
        DB::beginTransaction();
        try {
            $this->chunkUpdateService->updateRecordsInChunks($updateData, DynamicLink::tableName, DynamicLink::shortCode);

            DB::commit();

            $dynamicLinks = $this->getAll(conds: ['short_codes' => array_column($updateData, DynamicLink::shortCode)]);

            return $dynamicLinks;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function searching(Builder $query, $conds)
    {
        $query->when(isset($conds['short_codes']), function ($q) use ($conds) {
            $q->whereIn(DynamicLink::shortCode, $conds['short_codes']);
        });
        return $query;
    }
}
