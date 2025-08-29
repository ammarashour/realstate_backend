<template>
    <Head :title="$t('edit_blog')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div
                    class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl"
                >
                    <ps-label-header-6
                        textColor="text-secondary-800 dark:text-secondary-100"
                        >{{ $t("blog__be_blog_info") }}</ps-label-header-6
                    >
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form @submit.prevent="handleSubmit(blog.id)">
                        <div class="flex flex-col w-full gap-6 md:w-1/2">
                            <div>
                                <ps-label class="text-base">{{
                                    $t("blog_photo_label")
                                }}</ps-label>
                                <ps-label-title-3>{{
                                    $t("core__be_recommended_size_400_200")
                                }}</ps-label-title-3>
                                <!-- <div
                                    v-if="blog.cover[0] && false"
                                    class="flex items-end pt-4"
                                >
                                    <img
                                        v-lazy="{
                                            src:
                                                $page.props.uploadUrl +
                                                '/' +
                                                blog.cover[0]?.img_path,
                                            loading:
                                                $page.props.sysImageUrl +
                                                '/loading_gif.gif',
                                            error:
                                                $page.props.sysImageUrl +
                                                '/default_photo.png',
                                        }"
                                        width="400"
                                        height="200"
                                        class="h-48 w-96"
                                        alt="blog cover"
                                    />
                                    <ps-button
                                        type="button"
                                        @click="
                                            replaceImageClicked(
                                                blog.cover[0].id
                                            )
                                        "
                                        rounded="rounded-full"
                                        shadow="drop-shadow-2xl"
                                        class="mb-2 -ms-10"
                                        colors="bg-white text-primary-500 dark:bg-secondaryDark-black"
                                        border="border border-1 dark:border-secondary-700 border-secondary-300"
                                        padding="p-1.5"
                                        hover=""
                                        focus=""
                                    >
                                        <ps-icon name="pencil-btn" />
                                    </ps-button>
                                    <ps-image-icon-modal
                                        ref="ps_image_icon_modal"
                                    />
                                    <ps-action-modal ref="ps_action_modal" />
                                    <ps-danger-dialog ref="ps_danger_dialog" />
                                </div> v-else
                            -->
                                <ps-image-upload
                                    uploadType="image"
                                    v-model:imageFile="form.cover"
                                    :imagePath="
                                        $page.props.uploadUrl +
                                        '/' +
                                        blog.cover?.img_path
                                    "
                                />
                                <ps-label-caption
                                    textColor="text-red-500 "
                                    class="block mt-2"
                                    >{{ errors.cover }}</ps-label-caption
                                >
                            </div>

                            <div
                                v-for="(
                                    coreField, index
                                ) in coreFieldFilterSettings.filter(
                                    (coreField) =>
                                        coreField.original_field_name ===
                                            'name' &&
                                        coreField.enable === 1 &&
                                        coreField.is_delete === 0
                                )"
                                :key="index"
                            >
                                <ps-label class="text-base"
                                    >{{ $t(coreField.label_name) }}
                                    <span
                                        v-if="coreField.mandatory == 1"
                                        class="font-medium text-red-800 ms-1"
                                        >*</span
                                    ></ps-label
                                >
                                <ps-input
                                    ref="name"
                                    type="text"
                                    v-model:value="form.name"
                                    :placeholder="$t(coreField.placeholder)"
                                    @keyup="
                                        coreField.mandatory == 1
                                            ? validateEmptyInput(
                                                  'name',
                                                  form.name
                                              )
                                            : ''
                                    "
                                    @blur="
                                        coreField.mandatory == 1
                                            ? validateEmptyInput(
                                                  'name',
                                                  form.name
                                              )
                                            : ''
                                    "
                                />
                                <ps-label-caption
                                    v-if="coreField.mandatory == 1"
                                    textColor="text-red-500 "
                                    class="block mt-2"
                                    >{{ errors.name }}</ps-label-caption
                                >
                            </div>

                            <!-- for location city dropdown -->
                            <div
                                v-for="(
                                    coreField, index
                                ) in coreFieldFilterSettings.filter(
                                    (coreField) =>
                                        coreField.original_field_name ===
                                            'location_city_id' &&
                                        coreField.enable === 1 &&
                                        coreField.is_delete === 0
                                )"
                                :key="index"
                            >
                                <ps-label class="text-base"
                                    >{{ $t(coreField.label_name)
                                    }}<span
                                        v-if="coreField.mandatory == 1"
                                        class="font-medium text-red-800 ms-1"
                                        >*</span
                                    ></ps-label
                                >
                                <ps-dropdown
                                    align="left"
                                    class="w-full mt-1 lg:mt-2"
                                >
                                    <template #select>
                                        <ps-dropdown-select
                                            ref="city"
                                            :placeholder="
                                                $t(coreField.placeholder)
                                            "
                                            :showCenter="true"
                                            :selectedValue="
                                                form.location_city_id == ''
                                                    ? ''
                                                    : cities.filter(
                                                          (city) =>
                                                              city.id ==
                                                              form.location_city_id
                                                      )[0].name
                                            "
                                            @change="
                                                coreField.mandatory = 1
                                                    ? validateEmptyInput(
                                                          'location_city_id',
                                                          form.location_city_id
                                                      )
                                                    : ''
                                            "
                                            @blur="
                                                coreField.mandatory = 1
                                                    ? validateEmptyInput(
                                                          'location_city_id',
                                                          form.location_city_id
                                                      )
                                                    : ''
                                            "
                                        />
                                    </template>
                                    <template #list>
                                        <div
                                            class="w-full rounded-md shadow-xs bg-background dark:bg-backgroundDark"
                                        >
                                            <div class="z-30 pt-2">
                                                <div
                                                    v-if="cities.length == null"
                                                >
                                                    <ps-label
                                                        class="flex p-2"
                                                        @click="
                                                            route('city.index')
                                                        "
                                                        >{{
                                                            $t(
                                                                "core__be_add_new_city"
                                                            )
                                                        }}</ps-label
                                                    >
                                                </div>
                                                <div v-else>
                                                    <div
                                                        v-for="city in cities"
                                                        :key="city.id"
                                                        class="flex items-center px-2 py-4 cursor-pointer w-96 hover:bg-primary-000 dark:hover:bg-secondary-700"
                                                        @click="
                                                            [
                                                                (form.location_city_id =
                                                                    city.id),
                                                                (coreField.mandatory =
                                                                    1
                                                                        ? validateEmptyInput(
                                                                              'location_city_id',
                                                                              form.location_city_id
                                                                          )
                                                                        : ''),
                                                            ]
                                                        "
                                                    >
                                                        <ps-label
                                                            class="ms-2"
                                                            :class="
                                                                city.id ==
                                                                form.location_city_id
                                                                    ? ' font-bold'
                                                                    : ''
                                                            "
                                                            >{{
                                                                city.name
                                                            }}</ps-label
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </ps-dropdown>
                                <ps-label-caption
                                    v-if="coreField.mandatory == 1"
                                    textColor="text-red-500 "
                                    class="block mt-2"
                                    >{{
                                        errors.location_city_id
                                    }}</ps-label-caption
                                >
                            </div>
                            <!-- end location city -->

                            <div
                                class="py-3"
                                v-for="(
                                    coreField, index
                                ) in coreFieldFilterSettings.filter(
                                    (coreField) =>
                                        coreField.original_field_name ===
                                            'description' &&
                                        coreField.enable === 1 &&
                                        coreField.is_delete === 0
                                )"
                                :key="index"
                            >
                                <ps-label class="text-base"
                                    >{{ $t(coreField.label_name) }}
                                    <span
                                        v-if="coreField.mandatory == 1"
                                        class="font-medium text-red-800 ms-1"
                                        >*</span
                                    ></ps-label
                                >
                                <!-- <ps-textarea rows="3" ref="description" v-model:value="form.description"
                                    :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory == 1 ? validateEmptyInput('description', form.description) : ''"
                                    @blur="coreField.mandatory == 1 ? validateEmptyInput('description', form.description) : ''"
                                ></ps-textarea> -->
                                <editor ref="description" class="dark:bg-secondaryDark:black" v-model="form.description" :enableImageUpload="false"
                                    @keyup="coreField.mandatory == 1 ? validateEmptyInput('description', form.description) : ''"
                                    @blur="coreField.mandatory == 1 ? validateEmptyInput('description', form.description) : ''"/>
                                <ps-label-caption
                                    v-if="coreField.mandatory == 1"
                                    textColor="text-red-500 "
                                    class="block mt-2"
                                    >{{ errors.description }}</ps-label-caption
                                >
                            </div>

                            <div>
                                <ps-label class="text-base">{{
                                    $t("blog__blog_status")
                                }}</ps-label>
                                <ps-checkbox-value
                                    v-model:value="form.status"
                                    class="font-normal"
                                    title="Publish"
                                />
                            </div>

                            <div class="flex flex-row justify-end mb-2.5">
                                <ps-button
                                    @click="handleCancel()"
                                    textSize="text-base"
                                    type="reset"
                                    class="me-4"
                                    colors="text-primary-500"
                                    hover=""
                                    >{{ $t("core__be_btn_cancel") }}</ps-button
                                >
                                <ps-button
                                    class="transition-all duration-300 min-w-3xs"
                                    padding="px-7 py-2"
                                    rounded="rounded"
                                    hover=""
                                    focus=""
                                >
                                    <ps-loading
                                        v-if="loading"
                                        theme="border-2 border-t-2 border-text-8 border-t-primary-500"
                                        loadingSize="h-5 w-5"
                                    />
                                    <ps-icon
                                        v-if="success"
                                        name="check"
                                        w="20"
                                        h="20"
                                        class="me-1.5 transition-all duration-300"
                                    />
                                    <ps-label
                                        v-if="success"
                                        class="transition-all duration-300"
                                        textColor="text-white dark:text-secondaryDark-black"
                                        >{{
                                            $t("core__be_btn_saved")
                                        }}</ps-label
                                    >
                                    <ps-label
                                        v-if="!loading && !success"
                                        textColor="text-white dark:text-secondaryDark-black"
                                    >
                                        {{ $t("core__be_btn_save") }}
                                    </ps-label>
                                </ps-button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- card body end -->
            </div>
        </ps-card>
    </ps-layout>
</template>

<script>
import { defineComponent, ref } from "vue";
import PsLayout from "@/Components/PsLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import useValidators from "@/Validation/Validators";
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader6.vue";
import PsCard from "@/Components/Core/Card/PsCard.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";
import PsActionModal from "@/Components/Core/Modals/PsActionModal.vue";
import PsImageIconModal from "@/Components/Core/Modals/PsImageIconModal.vue";
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
import PsTextarea from "@/Components/Core/Textarea/PsTextarea.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import { trans } from "laravel-vue-i18n";
import Editor from "@/Components/Core/Editor/Editor.vue";

export default defineComponent({
    name: "Edit",
    components: {
        Head,
        PsBreadcrumb2,
        PsImageUpload,
        PsInput,
        PsLabel,
        PsButton,
        PsLabelHeader6,
        PsCard,
        PsIcon,
        PsLoading,
        PsCheckboxValue,
        PsActionModal,
        PsImageIconModal,
        PsDangerDialog,
        PsLabelCaption,
        PsLabelTitle3,
        PsTextarea,
        PsDropdown,
        PsDropdownSelect,
        Editor
    },
    layout: PsLayout,
    props: [
        "errors",
        "blog",
        "cities",
        "coreFieldFilterSettings",
        "validation",
    ],
    data() {
        return {
            location_city: "",
        };
    },
    setup(props) {
        const loading = ref(false);
        const success = ref(false);
        const ps_action_modal = ref();
        const ps_image_icon_modal = ref();
        const ps_danger_dialog = ref();
        const name = ref();
        const location_city_id = ref();
        const description = ref();

        // Client Side Validation
        const { isEmpty, minLength } = useValidators();

        const validateNameInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue
                ? isEmpty(fieldName, fieldValue)
                : minLength(fieldName, fieldValue, 3);
            if (fieldName == "name") {
                name.value.isError = !Boolean(props.errors.name) ? false : true;
            }
        };

        const validateEmptyInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue
                ? isEmpty(fieldName, fieldValue)
                : "";
            if (fieldName == "description") {
                description.value.isError = !Boolean(props.errors.description)
                    ? false
                    : true;
            }
            if (fieldName == "location_city_id") {
                location_city_id.value.isError = !Boolean(
                    props.errors.location_city_id
                )
                    ? false
                    : true;
            }
        };

        let form = useForm({
            name: props.blog.name,
            description: props.blog.description,
            location_city_id: props.blog.location_city_id,
            location_city_id: props.cities.find(
                (element) => element.id == props.blog.location_city_id
            )
                ? props.blog.location_city_id
                : "",
            status: props.blog.status == 1 ? true : false,
            cover: "",
            cover_id: props.blog.cover ? props.blog.cover.id : "",
            _method: "put",
        });

        function handleSubmit(id) {
            this.$inertia.post(route("blog.update", id), form, {
                forceFormData: true,
                onBefore: () => {
                    loading.value = true;
                },
                onSuccess: () => {
                    loading.value = false;
                    success.value = true;
                    setTimeout(() => {
                        success.value = false;
                    }, 2000);
                },
                onError: () => {
                    loading.value = false;
                    name.value.isError = !Boolean(props.errors.name)
                        ? false
                        : true;
                },
            });
        }

        // function replaceImageClicked(id) {
        //     ps_action_modal.value.openModal(
        //         trans("conf_modal_label"),
        //         trans("core__be_replace_img_label"),
        //         trans("core__be_remove_img_label"),
        //         "image",
        //         "trash",
        //         "24",
        //         "24",
        //         () => {
        //             ps_image_icon_modal.value.openModal(
        //                 trans("core__be_upload_photo"),
        //                 "cloudUpload",
        //                 (imageFile) => {
        //                     let imageForm = useForm({
        //                         image: imageFile,
        //                         _method: "put",
        //                     });

        //                     this.$inertia.post(
        //                         route("image.replace", id),
        //                         imageForm
        //                     );
        //                 }
        //             );
        //         },
        //         () => {
        //             ps_danger_dialog.value.openModal(
        //                 trans("core__be_remove_label"),
        //                 trans("core__be_are_u_sure_remove_photo"),
        //                 trans("core__be_btn_confirm"),
        //                 trans("core__be_btn_cancel"),
        //                 () => {
        //                     this.$inertia.delete(route("image.destroy", id), {
        //                         onBefore: () => {
        //                             loading.value = true;
        //                         },
        //                         onSuccess: () => {
        //                             loading.value = false;
        //                             success.value = true;
        //                             setTimeout(() => {
        //                                 success.value = false;
        //                             }, 2000);
        //                         },
        //                         onError: () => {
        //                             loading.value = false;
        //                         },
        //                     });
        //                 },
        //                 () => {}
        //             );
        //         },
        //         !props.validation.includes("cover")
        //     );
        // }

        return {
            validateNameInput,
            validateEmptyInput,
            description,
            location_city_id,
            handleSubmit,
            ps_action_modal,
            form,
            loading,
            success,
            // replaceImageClicked,
            ps_danger_dialog,
            ps_image_icon_modal,
        };
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans("core__be_dashboard_label"),
                    url: route("admin.index"),
                },
                {
                    label: trans("blog_module"),
                    url: route("blog.index"),
                },
                {
                    label: trans("blog__edit_blog_module"),
                    color: "text-primary-500",
                },
            ];
        },
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route("blog.index"));
        },
    },
});
</script>
