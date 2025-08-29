<template>
    <div class="blog" v-for="post in posts.data" :key="post.id">
        <div class="w-full h-full blog">
            <div class="rounded-lg shadow-lg bg-white max-w-sm h-full blog">
                <a href="#!">
                    <img class="rounded-t-lg" src="https://mdbootstrap.com/img/new/standard/nature/184.jpg" alt=""/>
                </a>

                <div class="p-6">
                    <h5 class="text-gray-900 text-xl font-semibold mb-2">
                        {{ post.title }}
                    </h5>
                    <ps-label class="mb-4 text-xs inline-block py-1 px-3 leading-none text-center whitespace-nowrap align-baseline font-bold bg-indigo-500 text-white rounded-full">
                                {{ post.category.title }}
                            </ps-label>
                    <p class="text-gray-700 text-base mb-4">
                        {{ post.excerpt}}
                    </p>
                    <ps-button type="button" class="inline-block px-6 py-3 bg-blue-600 text-indigo-500 font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out">Button</ps-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "BlogCard",
        props: ['posts']
    }
</script>

<style scoped>

</style>
