<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Project;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try{
            $project = Project::first();
            if($project->base_project_id == 28){
                $oldAndNewTableIdArr = [
                    [
                        "oldTableId" => "335",
                        "newTableId" => "267",
                    ],
                    [
                        "oldTableId" => "336",
                        "newTableId" => "268",
                    ],
                    [
                        "oldTableId" => "337",
                        "newTableId" => "269",
                    ],
                    [
                        "oldTableId" => "338",
                        "newTableId" => "270",
                    ],
                    [
                        "oldTableId" => "339",
                        "newTableId" => "271",
                    ],
                    [
                        "oldTableId" => "340",
                        "newTableId" => "272",
                    ],
                    [
                        "oldTableId" => "341",
                        "newTableId" => "273",
                    ],
                    [
                        "oldTableId" => "342",
                        "newTableId" => "274",
                    ],
                    [
                        "oldTableId" => "343",
                        "newTableId" => "275",
                    ],
                    [
                        "oldTableId" => "344",
                        "newTableId" => "276",
                    ],
                    [
                        "oldTableId" => "345",
                        "newTableId" => "277",
                    ],
                    [
                        "oldTableId" => "346",
                        "newTableId" => "278",
                    ],
                    [
                        "oldTableId" => "347",
                        "newTableId" => "279",
                    ],
                    [
                        "oldTableId" => "348",
                        "newTableId" => "280",
                    ],
                    [
                        "oldTableId" => "349",
                        "newTableId" => "281",
                    ],
                    [
                        "oldTableId" => "350",
                        "newTableId" => "282",
                    ],
                    [
                        "oldTableId" => "351",
                        "newTableId" => "283",
                    ],
                    [
                        "oldTableId" => "352",
                        "newTableId" => "284",
                    ],
                    [
                        "oldTableId" => "353",
                        "newTableId" => "285",
                    ],
                    [
                        "oldTableId" => "354",
                        "newTableId" => "286",
                    ],
                    [
                        "oldTableId" => "355",
                        "newTableId" => "287",
                    ],
                    [
                        "oldTableId" => "356",
                        "newTableId" => "288",
                    ],
                    [
                        "oldTableId" => "357",
                        "newTableId" => "289",
                    ],
                    [
                        "oldTableId" => "358",
                        "newTableId" => "290",
                    ],
                    [
                        "oldTableId" => "359",
                        "newTableId" => "291",
                    ],
                    [
                        "oldTableId" => "360",
                        "newTableId" => "292",
                    ],
                    [
                        "oldTableId" => "361",
                        "newTableId" => "293",
                    ],
                    [
                        "oldTableId" => "362",
                        "newTableId" => "294",
                    ],
                    [
                        "oldTableId" => "363",
                        "newTableId" => "295",
                    ],
                    [
                        "oldTableId" => "364",
                        "newTableId" => "296",
                    ],
                    [
                        "oldTableId" => "365",
                        "newTableId" => "297",
                    ],
                    [
                        "oldTableId" => "366",
                        "newTableId" => "298",
                    ],
                    [
                        "oldTableId" => "367",
                        "newTableId" => "299",
                    ],
                    [
                        "oldTableId" => "368",
                        "newTableId" => "300",
                    ],
                    [
                        "oldTableId" => "369",
                        "newTableId" => "301",
                    ],
                    [
                        "oldTableId" => "370",
                        "newTableId" => "302",
                    ],
                    [
                        "oldTableId" => "371",
                        "newTableId" => "303",
                    ],
                    [
                        "oldTableId" => "372",
                        "newTableId" => "304",
                    ],
                    [
                        "oldTableId" => "373",
                        "newTableId" => "305",
                    ],
                    [
                        "oldTableId" => "374",
                        "newTableId" => "306",
                    ],
                    [
                        "oldTableId" => "375",
                        "newTableId" => "335",
                    ],
                    [
                        "oldTableId" => "376",
                        "newTableId" => "336",
                    ],
                    [
                        "oldTableId" => "377",
                        "newTableId" => "337",
                    ],
                    [
                        "oldTableId" => "378",
                        "newTableId" => "338",
                    ],
                    [
                        "oldTableId" => "379",
                        "newTableId" => "339",
                    ],
                    [
                        "oldTableId" => "380",
                        "newTableId" => "340",
                    ],
                    [
                        "oldTableId" => "381",
                        "newTableId" => "341",
                    ],
                    [
                        "oldTableId" => "382",
                        "newTableId" => "342",
                    ],
                    [
                        "oldTableId" => "383",
                        "newTableId" => "343",
                    ]
                ];

                updateTableIds($oldAndNewTableIdArr);

                $oldAndNewCoreKeysIdLangKeyArr = [
                    [
                        "oldCoreKeysId" => "ps-itm00047",
                        "newCoreKeysId" => "ps-itm00041",
                        "newNameLangKey" => "ps-itm00041_280903_name",
                        "newPlaceholderLangKey" => "ps-itm00041_280903_placeholder",
                        "oldNameLangKey" => "ps-itm00047_280903_name",
                        "oldPlaceholderLangKey" => "ps-itm00047_280903_placeholder"
                    ],
                    [
                        "oldCoreKeysId" => "ps-itm00048",
                        "newCoreKeysId" => "ps-itm00042",
                        "newNameLangKey" => "ps-itm00042_280904_name",
                        "newPlaceholderLangKey" => "ps-itm00042_280904_placeholder",
                        "oldNameLangKey" => "ps-itm00048_280904_name",
                        "oldPlaceholderLangKey" => "ps-itm00048_280904_placeholder"
                    ],
                    [
                        "oldCoreKeysId" => "ps-itm00049",
                        "newCoreKeysId" => "ps-itm00043",
                        "newNameLangKey" => "ps-itm00043_280905_name",
                        "newPlaceholderLangKey" => "ps-itm00043_280905_placeholder",
                        "oldNameLangKey" => "ps-itm00049_280905_name",
                        "oldPlaceholderLangKey" => "ps-itm00049_280905_placeholder"
                    ],
                    [
                        "oldCoreKeysId" => "ps-itm00050",
                        "newCoreKeysId" => "ps-itm00044",
                        "newNameLangKey" => "ps-itm00044_280906_name",
                        "newPlaceholderLangKey" => "ps-itm00044_280906_placeholder",
                        "oldNameLangKey" => "ps-itm00050_280906_name",
                        "oldPlaceholderLangKey" => "ps-itm00050_280906_placeholder"
                    ],
                    [
                        "oldCoreKeysId" => "ps-itm00051",
                        "newCoreKeysId" => "ps-itm00045",
                        "newNameLangKey" => "ps-itm00045_280907_name",
                        "newPlaceholderLangKey" => "ps-itm00045_280907_placeholder",
                        "oldNameLangKey" => "ps-itm00051_280907_name",
                        "oldPlaceholderLangKey" => "ps-itm00051_280907_placeholder"
                    ]
                ];

                updateCustomFields($oldAndNewCoreKeysIdLangKeyArr);
            }
        }catch(Exception $_) {}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('', function (Blueprint $table) {

        });
    }
};
