<?php

namespace Modules\Core\Http\Services;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\Configuration\CoreKey;
use Modules\Core\Entities\CoreKeyCounter;

class CoreKeyCounterService extends PsService
{
    protected $coreKeyCounterIdCol, $coreKeyCounterCodeCol, $coreKeyCounterCountCol;

    public function __construct()
    {
        $this->coreKeyCounterIdCol = CoreKeyCounter::id;
        $this->coreKeyCounterCodeCol = CoreKeyCounter::code;
        $this->coreKeyCounterCountCol = CoreKeyCounter::counter;
    }

    public function getCoreKeyCounter($id = null, $conds = null)
    {
        $coreKeyCounter = CoreKeyCounter::when($id, function ($query, $id) {
            $query->where($this->coreKeyCounterIdCol, $id);
        })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })
            ->first();
        return $coreKeyCounter;
    }

    public function getCoreKeyCounters($conds = null, $limit = null, $offset = null)
    {
        $coreKeyCounters = CoreKey::when($conds, function ($query, $conds) {
            $query->where($conds);
        })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->latest()->get();
        return $coreKeyCounters;
    }

    public function store($request)
    {
        DB::beginTransaction();

        try {
            $coreKeyCounter = new CoreKeyCounter();

            if (isset($request->code) && !empty($request->code))
                $coreKeyCounter->code = $request->code;

            if (isset($request->counter))
                $coreKeyCounter->counter = $request->counter;

            if (isset($request->added_user_id) && !empty($request->added_user_id))
                $coreKeyCounter->added_user_id = $request->added_user_id;
            else
                $coreKeyCounter->added_user_id = Auth::user()->id;

            $coreKeyCounter->save();

            DB::commit();
            return $coreKeyCounter;
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function update($id, $request)
    {
        DB::beginTransaction();

        try {
            $coreKeyCounter = $this->getCoreKeyCounter($id);

            if (isset($request->code) && !empty($request->code))
                $coreKeyCounter->code = $request->code;

            if (isset($request->counter) && !empty($request->counter))
                $coreKeyCounter->counter = $request->counter;

            if (isset($request->updated_user_id) && !empty($request->updated_user_id))
                $coreKeyCounter->updated_user_id = $request->updated_user_id;
            else
                $coreKeyCounter->updated_user_id = Auth::user()->id;

            $coreKeyCounter->update();

            DB::commit();
            return $coreKeyCounter;
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function saveOrUpdate($coreKeysId, $moduleName)
    {
        $coreKeysIdLastest = substr($coreKeysId, -5);
        $countRow = str_pad($coreKeysIdLastest + 1, 5, '0', STR_PAD_LEFT);
        $count = intval($countRow);

        // $clientCoreKeyCounter = CoreKeyCounter::where('code',$generatedData['module_name'])->first();
        $clientCoreKeyCounter = $this->getCoreKeyCounter(conds: [CoreKeyCounter::code => $moduleName]);

        if ($clientCoreKeyCounter) {
            $clientCoreKeyCounter->counter = $count;
            $clientCoreKeyCounter->update();
        } else {
            $clientCoreKeyCounter = new CoreKeyCounter();
            $clientCoreKeyCounter->code = $moduleName;
            $clientCoreKeyCounter->counter = $count;
            $clientCoreKeyCounter->added_user_id = Auth::user()->id;
            $clientCoreKeyCounter->save();
        }
    }

    public function decisionForCustomFieldDataType($uiTypeId)
    {
        $stringUiTypes = ['uit00001', 'uit00002', 'uit00003', 'uit00004', 'uit00006'];
        $imageUiTypes = ['uit00009'];
        $multiSelectUiTypes = ['uit00008'];
        $dateUiTypes = ['uit00005', 'uit00010', 'uit00011'];
        $integerUiTypes = ['uit00007'];

        if(in_array($uiTypeId, $stringUiTypes)) {
            return 'String';
        } elseif (in_array($uiTypeId, $imageUiTypes)) {
            return 'Image';
        } elseif ( in_array($uiTypeId, $multiSelectUiTypes)) {
            return 'MultiSelect';
        } elseif (in_array($uiTypeId, $dateUiTypes)) {
            return 'Date';
        } elseif (in_array($uiTypeId, $integerUiTypes)) {
            return "Integer";
        }
    }

    public function corekeyGenerateClient($code, $counter)
    {
        if(!$code || !$counter) {
            throw new \InvalidArgumentException("Code and counter are required.");
        }

        $countRow = str_pad($counter, 5, '0', STR_PAD_LEFT);
        $core_keys_id = $code . $countRow;

        $dataArr = [
            'core_keys_id' => $core_keys_id,
            'module_name' => $code,
            'base_module_name' => 'ps-' . $code,
            'name_key' => "{$core_keys_id}_name",
            'placeholder_key' => "{$core_keys_id}_placeholder"
        ];
        return $dataArr;
    }
}
