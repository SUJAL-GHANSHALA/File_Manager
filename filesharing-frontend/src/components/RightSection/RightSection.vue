<!-- <template>
    <div :class="{'detail-main-section-wrapper': true, 'hidden': !isVisible}">
        <div class="topbox">
            <div :class="{'details': true, 'active': activeSection === 'details'}" @click="activeSection = 'details'">
                <h4>Details</h4>
            </div>
            <div :class="{'comments': true, 'active': activeSection === 'comments'}" @click="activeSection = 'comments'">
                <h4>Comments</h4>
            </div>
            <img :src="cross" class="cross-icon" @click="hideDetail" />
        </div>
        <div v-if="activeSection === 'details'">
            <div class="detail-header">
                <p>sujal.jpg</p>
            </div>
            <div class="img-section">
                <img :src="dummyDetailImage" />
            </div>
            <div class="access-section">
                <p>Who has access</p>
                <div class="all-access-data">
                    <img :src="profile" class="access-members" />
                    <img :src="profile" class="access-members" />
                </div>
                <div class="access-btn">
                    <button>
                        <img :src="key" />
                        Manage access
                    </button>
                </div>
            </div>
            <div class="file-details-section">
                <div class="heading-file-detail">
                    <p>File Detail:</p>
                </div>
                <div class="file-detail">
                    <p><strong>Location</strong></p>
                    <p class="filedetails">My Files</p>
                    <p><strong>Type</strong></p>
                    <p  class="filedetails">Image</p>
                    <p><strong>Size</strong></p>
                    <p  class="filedetails">1.2MB</p>
                    <p><strong>Owner</strong></p>
                    <p  class="filedetails">Sujal</p>
                    <p><strong>Modified</strong></p>
                    <p class="filedetails">13 mar 2022 Sujal</p>
                    <p><strong>Created</strong></p>
                    <p>12 mar 2022</p>
                </div>
            </div>
        </div>
        <div v-else class="comments">
            <div class="comments-section">
                <textarea placeholder="Add your comment here..."></textarea>
                <button @click="sendComment">Send</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'RightSection',
    data() {
        return {
            cross: require('../../assets/cross.png'),
            dummyDetailImage: require('../../assets/dummyrecentImage.png'),
            profile: require('../../assets/dummyProfile.png'),
            key: require('../../assets/key.png'),
            isVisible: false,
            activeSection: 'details'
        }
    },
    methods: {
        hideDetail() {
            this.isVisible = false;
        },
        sendComment() {
            // Empty function to handle sending comments to the backend
        }
    }
}
</script> -->
<template>
    <div :class="{'detail-main-section-wrapper': true, 'hidden': !isVisible}">
      <div class="topbox">
        <div :class="{'details': true, 'active': activeSection === 'details'}" @click="activeSection = 'details'">
          <h4>Details</h4>
        </div>
        <div :class="{'comments': true, 'active': activeSection === 'comments'}" @click="activeSection = 'comments'">
          <h4>Comments</h4>
        </div>
        <img :src="cross" class="cross-icon" @click="hideDetail" />
      </div>
      <div v-if="activeSection === 'details'">
        <div class="detail-header">
          <p>sujal.jpg</p>
        </div>
        <div class="img-section">
          <img :src="dummyDetailImage" />
        </div>
        <div class="access-section">
          <p>Who has access</p>
          <div class="all-access-data">
            <img :src="profile" class="access-members" />
            <img :src="profile" class="access-members" />
          </div>
          <div class="access-btn">
            <button @click="showManageAccess = true">
              <img :src="key" />
              Manage access
            </button>
          </div>
        </div>
        <div class="file-details-section">
          <div class="heading-file-detail">
            <p>File Detail:</p>
          </div>
          <div class="file-detail">
            <p><strong>Location</strong></p>
            <p class="filedetails">My Files</p>
            <p><strong>Type</strong></p>
            <p  class="filedetails">Image</p>
            <p><strong>Size</strong></p>
            <p  class="filedetails">1.2MB</p>
            <p><strong>Owner</strong></p>
            <p  class="filedetails">Sujal</p>
            <p><strong>Modified</strong></p>
            <p class="filedetails">13 mar 2022 Sujal</p>
            <p><strong>Created</strong></p>
            <p>12 mar 2022</p>
          </div>
        </div>
      </div>
      <div v-else class="comments">
        <div class="comments-section">
          <textarea placeholder="Add your comment here..."></textarea>
          <button @click="sendComment">Send</button>
        </div>
      </div>
      <ManageAccess 
        v-if="showManageAccess" 
        :fileName="'sujal.jpg'" 
        :showPopup="showManageAccess"
        @close-popup="showManageAccess = false"
      />
    </div>
  </template>
  
  <script>
  import emitter from '../../eventbus.js';
  import ManageAccess from '../MiddleSection/ManageAccess.vue';
  
  export default {
    name: 'RightSection',
    components: {
      ManageAccess
    },
    data() {
      return {
        cross: require('../../assets/cross.png'),
        dummyDetailImage: require('../../assets/dummyrecentImage.png'),
        profile: require('../../assets/dummyProfile.png'),
        key: require('../../assets/key.png'),
        isVisible: false,
        activeSection: 'details',
        showManageAccess: false
      }
    },
    created() {
      emitter.on('showDetails', this.showDetail);
    },
    beforeUnmount() {
      emitter.off('showDetails', this.showDetail);
    },
    methods: {
      hideDetail() {
        this.isVisible = false;
      },
      showDetail() {
        this.isVisible = true;
        this.activeSection = 'details';
      },
      sendComment() {
        // Empty function to handle sending comments to the backend
      }
    }
  }
  </script>
  


<style>
.hidden {
    display: none;
}
.right-section {
    height: 100vh;
}
.detail-main-section-wrapper {
    position: absolute;
    right: 0;
    background-color: white;
    width: 20%;
    box-shadow: -2px 0 5px rgba(0,0,0,0.1);
    border-left: 1px solid #e0e0e0;
}
.topbox {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
    height: 3rem;
    box-shadow: 0 0.6px 0 grey;
    border-radius: 0 0 0 10px;
}
.topbox .details, .topbox .comments {
    flex: 1;
    text-align: center;
    cursor: pointer;
}
.topbox .active {
    font-weight: bold;
    border-bottom: 2px solid #5f40db;
}
.cross-icon {
    cursor: pointer;
    width: 1rem;
    height: 1rem;
}
.detail-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
    height: 3rem;
    box-shadow: 0 0.6px 0 grey;
    border-radius: 0 0 0 10px;
}
.detail-header p {
    font-weight: 600;
}
.img-section {
    height: 11rem;
    display: flex;
    justify-content: center;
    align-items: center;
}
.access-section {
    border-top: 0.2px solid grey;
    border-bottom: 0.2px solid grey;
    margin: 0 30px;
    padding: 7px 0;
}
.access-section p {
    font-weight: 600;
}
.all-access-data {
    display: flex;
    flex-wrap: wrap;
}
.access-btn {
    display: flex;
    justify-content: flex-end;
}
.access-btn button {
    width: 10rem;
    height: 3rem;
    display: flex;
    align-items: center;
    font-size: 14px;
    font-weight: 600;
    gap: 4px;
    background-color: white;
    border: 1px solid #2e2e2e;
    color: #2e2e2e;
}
.access-btn button:hover {
    background-color: #5f40db;
    color: white;
}
.file-details-section {
    margin: 0 30px;
    padding: 7px 0;
    height: 400px;
    overflow-y: auto;
}
.file-detail {
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.filedetails {
    margin-bottom: 10px;
}
.heading-file-detail p {
    font-weight: 600;
    padding: 15px 0;
}
.comments-section {
    margin: 20px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}
.comments-section textarea {
    flex-grow: 1;
    width: calc(100% - 40px);
    padding: 10px;
    margin-bottom: 10px;
    resize: none;
}
.comments-section button {
    width: 100%;
    padding: 10px;
    background-color: #5f40db;
    color: white;
    border: none;
    cursor: pointer;
}
</style>
