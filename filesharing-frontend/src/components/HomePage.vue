<template>
    <div class="container">
        <LeftSection/>
        <MiddleSection/>
        <!-- <RightSection/> -->
    </div>
</template>

<script>
    import LeftSection from "./LeftSection/LeftSection";
    import MiddleSection from "./MiddleSection/MiddleSection";
    // import RightSection from "./RightSection/RightSection";

    export default {
        name: "HomePage",
        components: {
            LeftSection,
            MiddleSection,
            // RightSection
        },
        mounted() {
            console.warn("sujal"); //in this function will try to user to redirect page after he successfully sign up
            // let user = localStorage.getItem('user-info'); //this code is by you tuber, just for understanding purpose,like when user logged, he should not visit the signup/signin again
            // if(!user){
            //     this.$router.push({name:'Home'})
            // }
        }
    };
</script>

<style>
    *{
        margin: 0;
        padding: 0;
    }
    .container{
        display: flex;
    }
</style>