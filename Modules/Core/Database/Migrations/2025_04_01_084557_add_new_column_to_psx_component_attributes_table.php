<?php

use App\Http\Contracts\Utilities\ChunkUpdateServiceInterface;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('psx_component_attributes', function (Blueprint $table) {
            $table->string('name')->after('id');
        });

        $project = Project::first();
        if ($project->base_project_id == 11) {
            $records = $this->getMPCrecords();
        }
        if ($project->base_project_id == 26) {
            $records = $this->getMOCrecords();
        }
        if ($project->base_project_id == 27) {
            $records = $this->getRECrecords();
        }
        if ($project->base_project_id == 28) {
            $records = $this->getCGCrecords();
        }

        $chunkUpdateService = app(ChunkUpdateServiceInterface::class);

        $chunkUpdateService->updateRecordsInChunks($records, ComponentAttribute::tableName, ComponentAttribute::componentId);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_component_attributes', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * MPC
     */
    private function getMPCrecords()
    {
        return [
            [
                'name' => 'Search And Popular Category Component',
                'component_id' => 'ps-cweb00001'
            ],
            [
                'name' => 'Category Horizontal List Component',
                'component_id' => 'ps-cweb00002'
            ],
            [
                'name' => 'How Its Work Component',
                'component_id' => 'ps-cweb00003'
            ],
            [
                'name' => 'Vendor Horizontal List Component',
                'component_id' => 'ps-cweb00004'
            ],
            [
                'name' => 'Feature Item Horizontal List Component',
                'component_id' => 'ps-cweb00005'
            ],
            [
                'name' => 'Recent Item Horizontal List Component',
                'component_id' => 'ps-cweb00006'
            ],
            [
                'name' => 'Popular Item Horizontal List Component',
                'component_id' => 'ps-cweb00007'
            ],
            [
                'name' => 'Vendor Card Component',
                'component_id' => 'ps-cweb00008'
            ],
            [
                'name' => 'Discount Item Horizontal List Component',
                'component_id' => 'ps-cweb00009'
            ],
            [
                'name' => 'Package Horizontal List Component',
                'component_id' => 'ps-cweb00010'
            ],
            [
                'name' => 'Top Rated Seller Horizontal List Component',
                'component_id' => 'ps-cweb00011'
            ],
            [
                'name' => 'Blog Horizontal List Component',
                'component_id' => 'ps-cweb00012'
            ],
            [
                'name' => 'PSX Classified App Component',
                'component_id' => 'ps-cweb00013'
            ],
            [
                'component_id' => 'ps-cmb00001',
                'name' => 'Search Header Component'
            ],
            [
                'component_id' => 'ps-cmb00002',
                'name' => 'Blog Product Slider Component'
            ],
            [
                'component_id' => 'ps-cmb00003',
                'name' => 'Category Horizontal List Component'
            ],
            [
                'component_id' => 'ps-cmb00004',
                'name' => 'Feature Item Horizontal List Component'
            ],
            [
                'component_id' => 'ps-cmb00005',
                'name' => 'Vendor Horizontal List Component'
            ],
            [
                'component_id' => 'ps-cmb00006',
                'name' => 'Recent Item Horizontal List Component'
            ],
            [
                'component_id' => 'ps-cmb00007',
                'name' => 'Vendor Card Component'
            ],
            [
                'component_id' => 'ps-cmb00008',
                'name' => 'Top Seller Horizontal List Component'
            ],
            [
                'component_id' => 'ps-cmb00009',
                'name' => 'Nearest Item Horizontal List Component'
            ],
            [
                'component_id' => 'ps-cmb00010',
                'name' => 'Discount Item Horizontal List Component'
            ],
            [
                'component_id' => 'ps-cmb00011',
                'name' => 'Popular Item Horizontal List Component',
            ],
            [
                'component_id' => 'ps-cmb00012',
                'name' => 'Followers Item Horizontal List Component'
            ]
        ];
    }

    /**
     * MOC
     */
    private function getMOCrecords()
    {
        return [
            [
                'name' => 'Search And Popular Category Component',
                'component_id' => 'ps-cweb00024'
            ],
            [
                'name' => 'Category Horizontal List Component',
                'component_id' => 'ps-cweb00025'
            ],
            [
                'name' => 'How Its Work Component',
                'component_id' => 'ps-cweb00026'
            ],
            [
                'name' => 'Vendor Horizontal List Component',
                'component_id' => 'ps-cweb00027'
            ],
            [
                'name' => 'Feature Item Horizontal List Component',
                'component_id' => 'ps-cweb00028'
            ],
            [
                'name' => 'Recent Item Horizontal List Component',
                'component_id' => 'ps-cweb00029'
            ],
            [
                'name' => 'Popular Item Horizontal List Component',
                'component_id' => 'ps-cweb00030'
            ],
            [
                'name' => 'Vendor Card Component',
                'component_id' => 'ps-cweb00031'
            ],
            [
                'name' => 'Discount Item Horizontal List Component',
                'component_id' => 'ps-cweb00032'
            ],
            [
                'name' => 'Package Horizontal List Component',
                'component_id' => 'ps-cweb00033'
            ],
            [
                'name' => 'Top Seller Horizontal List Component',
                'component_id' => 'ps-cweb00034'
            ],
            [
                'name' => 'Blog Horizontal List Component',
                'component_id' => 'ps-cweb00035'
            ],
            [
                'name' => 'Mobile Show Case Component',
                'component_id' => 'ps-cweb00036'
            ],
            [
                'name' => 'Header Card Component',
                'component_id' => 'ps-cmb00013'
            ],
            [
                'name' => 'Feature Items Component',
                'component_id' => 'ps-cmb00014'
            ],
            [
                'name' => 'Blog Product Slider Component',
                'component_id' => 'ps-cmb00015'
            ],
            [
                'name' => 'Category Horizontal List Component',
                'component_id' => 'ps-cmb00016'
            ],
            [
                'name' => 'Recent Items List Component',
                'component_id' => 'ps-cmb00017'
            ],
            [
                'name' => 'Dealer Application Card Component',
                'component_id' => 'ps-cmb00018'
            ],
            [
                'name' => 'Top Rated Sellers List Component',
                'component_id' => 'ps-cmb00019'
            ],
            [
                'name' => 'Popular Items List Component',
                'component_id' => 'ps-cmb00020'
            ],
            [
                'name' => 'Latest Dealer List Component',
                'component_id' => 'ps-cmb00021'
            ],
            [
                'name' => 'Items Near You List Component',
                'component_id' => 'ps-cmb00022'
            ],
            [
                'name' => 'Discount Items Horizontal List Component',
                'component_id' => 'ps-cmb00023'
            ]
        ];
    }

    /**
     * REC
     */
    private function getRECrecords()
    {
        return [
            [
                'name' => 'Search And Popular Category Component',
                'component_id' => 'ps-cweb00037'
            ],
            [
                'name' => 'Category Horizontal List Component',
                'component_id' => 'ps-cweb00038'
            ],
            [
                'name' => 'How Its Work Component',
                'component_id' => 'ps-cweb00039'
            ],
            [
                'name' => 'Vendor Horizontal List Component',
                'component_id' => 'ps-cweb00040'
            ],
            [
                'name' => 'Feature Item Horizontal List Component',
                'component_id' => 'ps-cweb00041'
            ],
            [
                'name' => 'Recent Item Horizontal List Component',
                'component_id' => 'ps-cweb00042'
            ],
            [
                'name' => 'Popular Item Horizontal List Component',
                'component_id' => 'ps-cweb00043'
            ],
            [
                'name' => 'Vendor Card Component',
                'component_id' => 'ps-cweb00044'
            ],
            [
                'name' => 'Discount Item Horizontal List Component',
                'component_id' => 'ps-cweb00045'
            ],
            [
                'name' => 'Package Horizontal List Component',
                'component_id' => 'ps-cweb00046'
            ],
            [
                'name' => 'Top Seller Horizontal List Component',
                'component_id' => 'ps-cweb00047'
            ],
            [
                'name' => 'Blog Horizontal List Component',
                'component_id' => 'ps-cweb00048'
            ],
            [
                'name' => 'Mobile Show Case Component',
                'component_id' => 'ps-cweb00049'
            ],
            [
                'name' => 'Search Header Component',
                'component_id' => 'ps-cmb00024'
            ],
            [
                'name' => 'Header Card Component',
                'component_id' => 'ps-cmb00025'
            ],
            [
                'name' => 'Ideal Stay Horizontal List Component',
                'component_id' => 'ps-cmb00026'
            ],
            [
                'name' => 'Category Horizontal List Component',
                'component_id' => 'ps-cmb00027'
            ],
            [
                'name' => 'Featured Items List Component',
                'component_id' => 'ps-cmb00028'
            ],
            [
                'name' => 'Latest Agent Horizontal List Component',
                'component_id' => 'ps-cmb00029'
            ],
            [
                'name' => 'Popular Items Horizontal List Component',
                'component_id' => 'ps-cmb00030'
            ],
            [
                'name' => 'Recent Items Horizontal List Component',
                'component_id' => 'ps-cmb00031'
            ],
            [
                'name' => 'Agent Card Component',
                'component_id' => 'ps-cmb00032'
            ],
            [
                'name' => 'Top Rated Seller Horizontal List Component',
                'component_id' => 'ps-cmb00033'
            ],
            [
                'name' => 'Discount Items Horizontal List Component',
                'component_id' => 'ps-cmb00034'
            ],
            [
                'name' => 'Blog Slider Component',
                'component_id' => 'ps-cmb00035'
            ]
        ];
    }

    /**
     * CGC
    */
    private function getCGCrecords()
    {
        return [
            [
                'name' => 'Search And Popular Category Component',
                'component_id' => 'ps-cweb00014'
            ],
            [
                'name' => 'Category Horizontal List Component',
                'component_id' => 'ps-cweb00015'
            ],
            [
                'name' => 'Feature Item Horizontal List Component',
                'component_id' => 'ps-cweb00016'
            ],
            [
                'name' => 'Recent Item Horizontal List Component',
                'component_id' => 'ps-cweb00017'
            ],
            [
                'name' => 'Popular Item Horizontal List Component',
                'component_id' => 'ps-cweb00018'
            ],
            [
                'name' => 'Discount Item Horizontal List Component',
                'component_id' => 'ps-cweb00019'
            ],
            [
                'name' => 'Package Horizontal List Component',
                'component_id' => 'ps-cweb00020'
            ],
            [
                'name' => 'Top Seller Horizontal List Component',
                'component_id' => 'ps-cweb00021'
            ],
            [
                'name' => 'Blog Horizontal List Component',
                'component_id' => 'ps-cweb00022'
            ],
            [
                'name' => 'Mobile Show Case Component',
                'component_id' => 'ps-cweb00023'
            ],
            [
                'name' => 'Search Header Component',
                'component_id' => 'ps-cmb00036'
            ],
            [
                'name' => 'Category Horizontal List Component',
                'component_id' => 'ps-cmb00037'
            ],
            [
                'name' => 'Featured Listings Component',
                'component_id' => 'ps-cmb00038'
            ],
            [
                'name' => 'Blog Slider Component',
                'component_id' => 'ps-cmb00039'
            ],
            [
                'name' => 'Trending Spots Listing Component',
                'component_id' => 'ps-cmb00040'
            ],
            [
                'name' => 'City Savvy Selections Listing Component',
                'component_id' => 'ps-cmb00041'
            ],
            [
                'name' => 'Fresh Discoveries Listing Component',
                'component_id' => 'ps-cmb00042'
            ],
            [
                'name' => 'Top Rated Sellers Horizontal List Component',
                'component_id' => 'ps-cmb00043'
            ],
            [
                'name' => 'Nearby Gems Listing Component',
                'component_id' => 'ps-cmb00044'
            ],
            [
                'name' => 'Promote Business Card Component',
                'component_id' => 'ps-cmb00045'
            ]
        ];
    }
};
