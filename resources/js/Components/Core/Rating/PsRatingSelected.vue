<template>
    <div class="flex items-center">
        <div @click="rate(star)" v-for="star in maxStars" :class="{ 'active': star <= stars }" :key="star" class="star">
            <ps-icon name="starFill" v-if="star <= stars" class="text-primary-500 dark:text-primaryDark-accent " w="25" h="25"/>
            <ps-icon name="starOutline" v-else class="text-primary-500 dark:text-primaryDark-accent " w="25" h="25"/>
        </div>
    </div>
</template>

<script>

export default {
    name: 'RatingSelected',
    components : {

    },
    props: {
        'grade': {
            type : Number,
            default : 0
        },
        'maxStars': {
            type : Number,
            default : 5
        },
        'hasCounter': {
            type : Boolean,
            default : true
        },
    },
    data() {
        return {
            stars: this.grade
        }
    },
    methods: {
        rate(star) {
            if (typeof star === 'number' && star <= this.maxStars && star >= 0) {
                this.stars = this.stars === star ? star - 1 : star;
            }
        },
        getRating() {
            return this.stars;
        }
    },
}
</script>
