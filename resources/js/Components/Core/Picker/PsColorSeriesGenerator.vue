<template>
    <div>
        <ps-label textColor="text-base font-semibold text-secondary-800">{{ title }}</ps-label>
        <div class="flex items-center gap-3 mt-6">
            <span class="block w-9 h-9 rounded-full cursor-pointer" :style="{background:brandColor}" @click="primaryColorClick(brandColor)"></span>
            <div>
                <ps-input-group class="h-8 w-60" v-model:value="brandColor" 
                    :onformchange="generateColor(brandColor)"
                    :placeholder="$t('#123456')" 
                    theme="rounded text-secondary-500" 
                    background="bg-white dark:bg-blue-100 rounded border border-gray-200 shadow-sm ">
                    <template #rightButton>
                        <div class="px-3 py-2 border-l cursor-pointer" @click="generateColor(brandColor)">
                            <ps-icon class="text-secondary-500" name="re-new" w="16" h="16" viewBox="0 0 16 16"/>
                        </div>
                    </template>
                </ps-input-group>
            </div>
        </div>
        <div class="flex mt-3">
            <div class="" v-for="(color,index) in oldColors" :key="index">
                <span :class="['block w-12 h-12', (color.key.split('_')[1]=='500' || color.key.split('-')[1]=='500')?'rounded-full':'']" :style="{background:color.value}" ></span>
                <ps-label textColor="text-center mt-2">{{ color.key.split('-').length > 1 ? color.key.split('-')[1] : color.key.split('_')[1] }}</ps-label>
            </div>
        </div>
    </div>
    <ps-color-picker-modal ref="ps_color_picker_modal"/>
</template>

<script>
import { ref } from 'vue';
import { lightness } from 'simpler-color';
import { useForm } from "@inertiajs/vue3";
import { router } from '@inertiajs/vue3';
import PsInputGroup from "@/Components/Core/Input/PsInputGroup.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsColorPickerModal from '@/Components/Core/Modals/PsColorPickerModal.vue';

    export default {
        name : "PsColorSeriesGenerator",
        components : {
            PsInputGroup,
            PsIcon,
            PsLabel,
            PsColorPickerModal,
        },
        props:['title', 'activeColor'],
        setup(props, {emit}){
            const ps_color_picker_modal = ref();
            const oldColors = ref(props.activeColor);
            const brandColor = ref(props.activeColor[5].value);
            // const brandColor = ref(props.activeColor.filter(e=>e.key.split('_')[1] == '500')[0].value);

            function generateColor(color){
                if (!/^#[0-9A-F]{6}$/i.test(color)) {
                    console.error('Invalid color format. Color must include # and be 7 characters long.');
                    return;
                }
                
                brandColor.value = color;
                let is_achromatic = false;
                if(oldColors.value[0].title == 'achromatic' || oldColors.value[0].title == 'feAchromatic'){
                    is_achromatic = true;
                }
                oldColors.value[0].value = is_achromatic ? '#FFFFFF' : lightness(color, 95);
                oldColors.value[1].value = is_achromatic ? lightness(color, 92) : lightness(color, 88);
                oldColors.value[2].value = is_achromatic ? lightness(color, 84) : lightness(color, 83);
                oldColors.value[3].value = is_achromatic ? lightness(color, 72) : lightness(color, 78);
                oldColors.value[4].value = is_achromatic ? lightness(color, 60) : lightness(color, 70);
                oldColors.value[5].value = color;
                oldColors.value[6].value = is_achromatic ? lightness(color, 40) : lightness(color, 36);
                oldColors.value[7].value = is_achromatic ? lightness(color, 21) : lightness(color, 28);
                oldColors.value[8].value = is_achromatic ? lightness(color, 16) : lightness(color, 22);
                oldColors.value[9].value = is_achromatic ? lightness(color, 7) : lightness(color, 17);
                emit('generated-color', oldColors.value);
                // console.log(oldColors.value);
            }

            function colorClick(data){
                console.log(data);
                ps_color_picker_modal.value.openModal(
                    data.value,
                    (selectedColor) => {
                        const index = oldColors.value.indexOf(data);
                        oldColors.value[index].value = selectedColor;
                        emit('generated-color', oldColors.value);
                        let colorForm = useForm({
                            id: data.id,
                            value: selectedColor,
                            "_method": "put"
                        })
                        colorForm.post(route("color.update", data.id));
                        
                        // router.post(route("color.update", data.id), colorForm,{
                        //     forceFormData: true,
                        //     onFinish: () => {
                        //         this.$inertia.get(route("mobile_setting.index",))
                        //     },
                        // });
                    },
                    () => {}
                );
            }

            function primaryColorClick(data){
                console.log(data);
                ps_color_picker_modal.value.openModal(
                    data,
                    (selectedColor) => {
                        brandColor.value = selectedColor;
                        generateColor(selectedColor);
                    },
                    () => {}
                );
            }

            return {
                ps_color_picker_modal,
                oldColors,
                brandColor,
                generateColor,
                colorClick,
                primaryColorClick,
            }
        }
    }
</script>

