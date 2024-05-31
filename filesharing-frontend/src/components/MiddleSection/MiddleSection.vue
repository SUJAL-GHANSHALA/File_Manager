<template>
  <div class="middle-section widthhandle">
    <!-- nav bar starts -->
    <div class="nav-bar">
      <div class="mid-sec-left">
        <img src="../../assets/middlesectionhard.png" alt="" class="mid-sec-left-img">
        <h2>Storage</h2>
      </div>

      <div class="mid-sec-right">
        <div class="mid-sec-right-left mid-sec-right-form">
          <form action="">
            <img src="../../assets/search.png" alt="" class="mid-sec-right-form-img">
            <input type="text" placeholder="Search here...">
          </form>
        </div>
        <div class="mid-sec-right-right">
          <div class="profile-logo"><img src="../../assets/ShaneSujal.png" alt=""></div>

          <div class="profile-icon" @click="toggleProfileDropdown">
            <img src="../../assets/profileicon.png" alt="">
          </div>
          <!-- profile hidden starts -->
          <ProfileDropdown :isVisible="showProfileDropdown" />
          <!-- profile hidden ends -->
        </div>
      </div>
    </div>
    <!-- nav-bar ends -->

    <!-- recent section starts -->
    <div class="cards">
      <div class="recent">
        <div class="recent-left">
          <h3>Recent</h3>
        </div>
        <!-- <div class="recent-new">
          <img src="../../assets/recentnew.png" alt="">
        </div> -->
      </div>
      <div class="recent-wrapper">
        <div class="recent-data-contents" v-if="recentItems.length > 0">
          <div class="recent-card-wrapper" v-for="(item, index) in recentItems" :key="index">
            <div class="card-upper-sec">
              <img :src="item.url" :alt="item.title" class="dataImagetitle" />
              <p>{{ item.title }}</p>
            </div>
            <div class="card-lower-sec">
              <img :src="item.url" alt="DataImage" class="dataImage" />
            </div>
          </div>
        </div>
        <div v-else>
          <div class="no-files">No Recent File Available</div>
        </div>
      </div>
    </div>
    <!-- recent section ends -->

    <div class="myfiles">
      <h3>My Files</h3>
    </div>
    <div class="table-section">
      <div class="table">
        <TablePage />
      </div>
    </div>
  </div>
  <CreateFolder v-if="showCreateFolderPopup" />
  <ManageAccess :fileName="'sujal.mp3'" />
  <RightSection />
</template>
  
  <script>
  import emitter from '../../eventbus.js';
  import ProfileDropdown from './ProfileDropdown.vue';
  import ManageAccess from './ManageAccess.vue';
  import CreateFolder from './CreateFolder.vue';
  import RightSection from '../RightSection/RightSection.vue';
  import TablePage from './TablePage.vue';
  import { mapState, mapActions } from 'vuex';
  
  export default {
    name: "MiddleSection",
    components: {
      TablePage,
      RightSection,
      CreateFolder,
      ManageAccess,
      ProfileDropdown
    },
    data() {
      return {
        recentItems: [] 
      };
    },
    computed: {
      ...mapState(['showCreateFolderPopup', 'showProfileDropdown']), 
    },
    methods: {
      ...mapActions(['toggleProfileDropdown']),
      addRecentItem(item) {
        console.log(item);
        this.recentItems = [item, ...this.recentItems].slice(0, 4);
      },
      getImageForItem(item) {
        
        if (item.type === 'folder') {
          return this.imageIcon;
        } else if (item.extension === 'docx') {
          return require('../../assets/dummyProfile.png'); 
        } else if (item.extension === 'pdf') {
          return require('../../assets/imageIcon.png'); 
        }

        return this.dummyImage; 
      }
    },
    created() {
      emitter.on('file-accessed', this.addRecentItem); 
    },
    beforeUnmount() {
      emitter.off('file-accessed', this.addRecentItem); 
    }
  };
  </script>

<style>
    .dataImagetitle{
      height: 20px;
      width: 30px;
    }
    .dataImage{
      height: 120px;
      width: 250px;
    }
    .card-upper-sec{
      height: 20px;
      width: 30px;
      margin-bottom: 5px;
    }
    .card-lower-sec{
      height: 80px;
      width: 230px;
    }
    .middle-section {
    /* border: 1px solid red; */
    width: 65%;
    height: 100vh;
    font-family: sans-serif;
    }
    /* nav-bar starts */
    .nav-bar{
        height: 60px;
        /* border: 1px solid red; */
        display: flex;
        justify-content: space-between;
    }
    .mid-sec-left {
        /* border: 1px solid blue; */
        display: flex;
        align-items: center;
        width: 36%;
    }
    .mid-sec-left h2{
        font-size: 18px;
    }
    .mid-sec-left-img{
        margin-left: 10px;
        margin-right: 5px;
        /* width: 100%; */
        height: 25px;
    }
    .mid-sec-right {
        /* border: 1px solid blue; */
        display: flex;
        justify-content: space-evenly;
        align-items: center;
        width: 55%;
    }
    .mid-sec-right-left{
        /* border: 1px solid red; */
        width: 35%;
        /* width: 60%; */
    }
    .mid-sec-right-form-img{
        margin-top: 3px;
        height: 19px;
        cursor: pointer;
    }
    .mid-sec-right-form form{
        display: flex;
        align-items: center;
    }
    .mid-sec-right-form{
        width: 60%;
        border: 1px solid black;
        border-radius: 6px;
        padding-left: 6px;
    }
    .mid-sec-right-form input{
        padding: 10px;
        width: 80%;
        /* width: 400px; */
        border: none;
    }
    .mid-sec-right-form input:focus{
        outline: none;
    }
    .mid-sec-right-right{
        width: 20%;
        /* border: 1px solid blue; */
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .profile-logo{
        height: 36px;
        width: 36px;
        border-radius: 20px;
        /* border: 1px solid black; */
        cursor: pointer;
        position: relative;
        left: 50px;
    }
    .profile-logo img {
      height: 36px;
      width: 36px;
    }
    .profile-name p{
        font-size: 17px;
        font-weight: 600;
    }
    .profile-icon{
        cursor: pointer;
    }
    /* nav-bar ends */

    .profile-name p{
        font-size: 15px;
    }

    /* cards starts */
    .recent {
        display: flex;
        align-items:end;
        justify-content: space-between;
        /* border: 1px solid red; */
        height: 50px;
    }
    .recent-left{
        margin-left: 15px;
    }
    .profile-name p {
        font-size: 15px;
    }
    .recent-new{
        /* border: 1px solid red; */
        margin-right: 20px;
    }
    .recent-new img{
        position: relative;
        top: 15px;
        right: 15px;
        /* border: 1px solid red; */
        height: 40px;
        cursor: pointer;
    }
    .cards {
    /* border: 1px solid red; */
        height: 250px;
    }
    .header-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 5px 20px;
    }
    .header-section p {
        font-weight: 500;
    }
    .header-section button {
        width: 5rem;
        display: flex;
        align-items: center;
        gap: 4px;
        justify-content: center;
    }
    .recent-data-contents {
        padding: 5px 20px;
        display: flex;
        /* align-items: center;
        justify-content: space-around; */
        gap: 9px; 
    }
    .recent-card-wrapper {
        /* border: 1px solid red; */
        width: fit-content;
        background-color: white;
        border-radius: 5px;
        box-shadow: 0 0 0.3px grey;
        padding: 15px;
        height: 150px;
        width: 270px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        cursor: pointer;
    }
    .recent-card-wrapper .card-upper-sec {
        display: flex;
        gap: 2px;
        padding: 5px 2px;
    }
    .recent-card-wrapper .card-lower-sec {
        /* display: flex;
        justify-content: center; */
    }
    .card-lower-sec .dataImage {
        width: 90%;
    }

    .myfiles{
        margin-left: 13px;
    }
    .table{
        /* border: 1px solid red; */
        height: auto;
    }
    .table-section{
        /* border: 1px solid red; */
        height: auto;
        margin-top: 15px;
    }
    .widthhandle{
        width: 80%;
    }
    .no-files {
      margin-top: 10px;
      height: 180px;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #f9f9f9;
      border: 2px dashed #ccc;
      border-radius: 10px;
      font-family: Arial, sans-serif;
      font-size: 18px;
      color: #666;
      text-align: center;
      padding: 20px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .no-files::before {
      content: "😞";
      font-size: 36px;
      display: block;
      margin-bottom: 10px;
    }
    .no-files:hover {
      background-color: #f2f2f2;
    }


</style>