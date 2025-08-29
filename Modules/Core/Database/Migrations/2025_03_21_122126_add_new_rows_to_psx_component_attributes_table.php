<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Entities\Project;
use Modules\Theme\Entities\ComponentAttribute;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Artisan::call('optimize:clear');
            $project = Project::first();
            $baseData = [
                'theme_id' => 1,
                'attributes' => '{"is_show":"1"}',
                'added_date' => now(),
                'added_user_id' => 0
            ];
            if ($project->base_project_id == 11) {
                $components = $this->getMPCcomponents();
            }
            if ($project->base_project_id == 26) {
                $components = $this->getMOCcomponents();
            }
            if ($project->base_project_id == 27) {
                $components = $this->getRECcomponents();
            }
            if ($project->base_project_id == 28) {
                $components = $this->getCGCcomponents();
            }

            $componentIds = array_column($components, 'component_id');

            $existingComponents = DB::table(ComponentAttribute::tableName)
                ->whereIn('component_id', $componentIds)
                ->get()
                ->pluck('component_id')
                ->toArray();

            $missingComponents = array_diff($componentIds, $existingComponents);

            $insertRecords = [];
            foreach ($components as $component) {
                if (in_array($component['component_id'], $missingComponents)) {
                    $insertRecords[] = [
                        ...$component,
                        ...$baseData
                    ];
                }
            }

            DB::table(ComponentAttribute::tableName)->insert($insertRecords);
        } catch (Throwable $e) {
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {}

    /**
     * MPC
     */
    private function getMPCcomponents()
    {
        return [
            [
                'component_id' => 'ps-cweb00001',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00001',
            ],
            [
                'component_id' => 'ps-cweb00002',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00001',
            ],
            [
                'component_id' => 'ps-cweb00003',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00001',
            ],
            [
                'component_id' => 'ps-cweb00004',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00001',
            ],
            [
                'component_id' => 'ps-cweb00005',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00001',
            ],
            [
                'component_id' => 'ps-cweb00006',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00001',
            ],
            [
                'component_id' => 'ps-cweb00007',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00001',
            ],
            [
                'component_id' => 'ps-cweb00008',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00001',
            ],
            [
                'component_id' => 'ps-cweb00009',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00001',
            ],
            [
                'component_id' => 'ps-cweb00010',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00001',
            ],
            [
                'component_id' => 'ps-cweb00011',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00001',
            ],
            [
                'component_id' => 'ps-cweb00012',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00001',
            ],
            [
                'component_id' => 'ps-cweb00013',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00001',
            ],
            [
                'component_id' => 'ps-cmb00001',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00001',
            ],
            [
                'component_id' => 'ps-cmb00002',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00001',
            ],
            [
                'component_id' => 'ps-cmb00003',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00001',
            ],
            [
                'component_id' => 'ps-cmb00004',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00001',
            ],
            [
                'component_id' => 'ps-cmb00005',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00001',
            ],
            [
                'component_id' => 'ps-cmb00006',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00001',
            ],
            [
                'component_id' => 'ps-cmb00007',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00001',
            ],
            [
                'component_id' => 'ps-cmb00008',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00001',
            ],
            [
                'component_id' => 'ps-cmb00009',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00001',
            ],
            [
                'component_id' => 'ps-cmb00010',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00001',
            ],
            [
                'component_id' => 'ps-cmb00011',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00001',
            ],
            [
                'component_id' => 'ps-cmb00012',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00001',
            ]
        ];
    }

    /**
     * MOC
     */
    private function getMOCcomponents()
    {
        return [
            [
                'component_id' => 'ps-cweb00024',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00003',
            ],
            [
                'component_id' => 'ps-cweb00025',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00003',
            ],
            [
                'component_id' => 'ps-cweb00026',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00003',
            ],
            [
                'component_id' => 'ps-cweb00027',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00003',
            ],
            [
                'component_id' => 'ps-cweb00028',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00003',
            ],
            [
                'component_id' => 'ps-cweb00029',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00003',
            ],
            [
                'component_id' => 'ps-cweb00030',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00003',
            ],
            [
                'component_id' => 'ps-cweb00031',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00003',
            ],
            [
                'component_id' => 'ps-cweb00032',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00003',
            ],
            [
                'component_id' => 'ps-cweb00033',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00003',
            ],
            [
                'component_id' => 'ps-cweb00034',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00003',
            ],
            [
                'component_id' => 'ps-cweb00035',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00003',
            ],
            [
                'component_id' => 'ps-cweb00036',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00003',
            ],
            [
                'component_id' => 'ps-cmb00013',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00002',
            ],
            [
                'component_id' => 'ps-cmb00014',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00002',
            ],
            [
                'component_id' => 'ps-cmb00015',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00002',
            ],
            [
                'component_id' => 'ps-cmb00016',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00002',
            ],
            [
                'component_id' => 'ps-cmb00017',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00002',
            ],
            [
                'component_id' => 'ps-cmb00018',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00002',
            ],
            [
                'component_id' => 'ps-cmb00019',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00002',
            ],
            [
                'component_id' => 'ps-cmb00020',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00002',
            ],
            [
                'component_id' => 'ps-cmb00021',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00002',
            ],
            [
                'component_id' => 'ps-cmb00022',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00002',
            ],
            [
                'component_id' => 'ps-cmb00023',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00002',
            ]
        ];
    }

    /**
     * REC
     */
    private function getRECcomponents()
    {
        return [
            [
                'component_id' => 'ps-cweb00037',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00004',
            ],
            [
                'component_id' => 'ps-cweb00038',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00004',
            ],
            [
                'component_id' => 'ps-cweb00039',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00004',
            ],
            [
                'component_id' => 'ps-cweb00040',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00004',
            ],
            [
                'component_id' => 'ps-cweb00041',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00004',
            ],
            [
                'component_id' => 'ps-cweb00042',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00004',
            ],
            [
                'component_id' => 'ps-cweb00043',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00004',
            ],
            [
                'component_id' => 'ps-cweb00044',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00004',
            ],
            [
                'component_id' => 'ps-cweb00045',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00004',
            ],
            [
                'component_id' => 'ps-cweb00046',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00004',
            ],
            [
                'component_id' => 'ps-cweb00047',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00004',
            ],
            [
                'component_id' => 'ps-cweb00048',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00004',
            ],
            [
                'component_id' => 'ps-cweb00049',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00004',
            ],
            [
                'component_id' => 'ps-cmb00024',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00003',
            ],
            [
                'component_id' => 'ps-cmb00025',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00003',
            ],
            [
                'component_id' => 'ps-cmb00026',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00003',
            ],
            [
                'component_id' => 'ps-cmb00027',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00003',
            ],
            [
                'component_id' => 'ps-cmb00028',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00003',
            ],
            [
                'component_id' => 'ps-cmb00029',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00003',
            ],
            [
                'component_id' => 'ps-cmb00030',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00003',
            ],
            [
                'component_id' => 'ps-cmb00031',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00003',
            ],
            [
                'component_id' => 'ps-cmb00032',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00003',
            ],
            [
                'component_id' => 'ps-cmb00033',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00003',
            ],
            [
                'component_id' => 'ps-cmb00034',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00003',
            ],
            [
                'component_id' => 'ps-cmb00035',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00003',
            ]
        ];
    }

     /**
     * CGC
     */
    private function getCGCcomponents()
    {
        return [
            [
                'component_id' => 'ps-cweb00014',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00002',
            ],
            [
                'component_id' => 'ps-cweb00015',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00002',
            ],
            [
                'component_id' => 'ps-cweb00016',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00002',
            ],
            [
                'component_id' => 'ps-cweb00017',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00002',
            ],
            [
                'component_id' => 'ps-cweb00018',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00002',
            ],
            [
                'component_id' => 'ps-cweb00019',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00002',
            ],
            [
                'component_id' => 'ps-cweb00020',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00002',
            ],
            [
                'component_id' => 'ps-cweb00021',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00002',
            ],
            [
                'component_id' => 'ps-cweb00022',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00002',
            ],
            [
                'component_id' => 'ps-cweb00023',
                'platform_id' => 1,
                'screen_id' => 'ps-sweb00002',
            ],
            [
                'component_id' => 'ps-cmb00036',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00004',
            ],
            [
                'component_id' => 'ps-cmb00037',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00004',
            ],
            [
                'component_id' => 'ps-cmb00038',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00004',
            ],
            [
                'component_id' => 'ps-cmb00039',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00004',
            ],
            [
                'component_id' => 'ps-cmb00040',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00004',
            ],
            [
                'component_id' => 'ps-cmb00041',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00004',
            ],
            [
                'component_id' => 'ps-cmb00042',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00004',
            ],
            [
                'component_id' => 'ps-cmb00043',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00004',
            ],
            [
                'component_id' => 'ps-cmb00044',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00004',
            ],
            [
                'component_id' => 'ps-cmb00045',
                'platform_id' => 2,
                'screen_id' => 'ps-smb00004',
            ]
        ];
    }
};
