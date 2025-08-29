<template>
  <div class="flex items-center">
      <div v-for="star in maxStars" :class="{ 'active': star <= stars }" :key="star" @click="rate(star)" class="star me-0.5">

        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" :class="star <= grade ? '' : 'hidden ' ">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.6734 1.66974C12.5474 1.41283 12.2862 1.25 12 1.25C11.7139 1.25 11.4527 1.41283 11.3267 1.66974L8.50041 7.43218L2.89079 8.258C2.61006 8.29932 2.37669 8.49554 2.28778 8.76501C2.19888 9.03448 2.26966 9.33105 2.47068 9.53132L6.67678 13.7218L5.26863 19.9241C5.20408 20.2085 5.31017 20.5044 5.54064 20.683C5.77112 20.8616 6.08421 20.8904 6.34342 20.7569L12 17.8436L17.6566 20.7569C17.9158 20.8904 18.2289 20.8616 18.4594 20.683C18.6899 20.5044 18.796 20.2085 18.7314 19.9241L17.3233 13.7218L21.5294 9.53132C21.7304 9.33105 21.8012 9.03448 21.7123 8.76501C21.6234 8.49554 21.39 8.29932 21.1093 8.258L15.4996 7.43218L12.6734 1.66974Z" fill="#F59E0B"/>
        </svg>

        <div class="w-6 h-6  overflow-hidden" v-show="grade-star == -0.5" >

            <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24">
            <path fill="#E5E7EB" d="M21.951 9.67a1 1 0 0 0-.807-.68l-5.699-.828-2.548-5.164A.978.978 0 0 0 12 2.486v16.28l5.097 2.679a1 1 0 0 0 1.451-1.054l-.973-5.676 4.123-4.02a1 1 0 0 0 .253-1.025z"/>
            <path fill="#F59E0B" d="M11.103 2.998 8.555 8.162l-5.699.828a1 1 0 0 0-.554 1.706l4.123 4.019-.973 5.676a1 1 0 0 0 1.45 1.054L12 18.765V2.503c-.356.001-.703.167-.897.495z"/>
            </svg>

        </div>

        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" :class="star <= grade || grade-star == -0.5? 'hidden ' : '' " xmlns="http://www.w3.org/2000/svg" >
            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.6734 1.66974C12.5474 1.41283 12.2862 1.25 12 1.25C11.7139 1.25 11.4527 1.41283 11.3267 1.66974L8.50041 7.43218L2.89079 8.258C2.61006 8.29932 2.37669 8.49554 2.28778 8.76501C2.19888 9.03448 2.26966 9.33105 2.47068 9.53132L6.67678 13.7218L5.26863 19.9241C5.20408 20.2085 5.31017 20.5044 5.54064 20.683C5.77112 20.8616 6.08421 20.8904 6.34342 20.7569L12 17.8436L17.6566 20.7569C17.9158 20.8904 18.2289 20.8616 18.4594 20.683C18.6899 20.5044 18.796 20.2085 18.7314 19.9241L17.3233 13.7218L21.5294 9.53132C21.7304 9.33105 21.8012 9.03448 21.7123 8.76501C21.6234 8.49554 21.39 8.29932 21.1093 8.258L15.4996 7.43218L12.6734 1.66974Z" fill="#D1D5DB"/>
        </svg>
          
      </div>
  </div>
</template>

<script>

export default {
    name: 'RatingView',
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
    },
    setup(props) {
        let stars;

        function rate(star) {
            if (star <= props.maxStars && star >= 0) {
                stars = props.grade === star ? star - 1 : star;
            }
        }

        return {
            rate,
            stars
        }
    },

}
</script>
props: {
    'grade':{ type : Number, default : 0 },
    'maxStars': { type : Number, default : 5 },
}
