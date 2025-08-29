<template>
    <nav class="rounded-md w-full">
        <ol class="list-reset flex">
            <li class="flex text-default">
                <ps-icon-1 :name="iconName" class="" :theme="iconColor" />
                <slot />
            </li>
        </ol>
    </nav>
</template>
<script>
    import PsIcon1 from "@/Components/Core/Icons/PsIcon1.vue";
    export default {
        name: "PsBreadcrumb",
        components: {
            PsIcon1
        },
        props: {
            iconName: {
                type: String,
                default: "badge"
            },
            iconColor: {
                type: String,
                default: "#4B5563"
            }
        }
    }
</script>