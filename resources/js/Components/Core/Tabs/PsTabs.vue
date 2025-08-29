<template>
    <div class="dark:border-gray-700">
        <div class="w-full me-2">
            <a href="#" class="inline-flex px-4 py-2 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-indigo-300 dark:hover:text-gray-300 group">
                <slot />
            </a>
        </div>
    </div>
</template>


<script>
export default {
    name: "PsTabs",
    components: {
    }
};
</script>