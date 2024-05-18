<template>
    <div :class="{'detail-main-section-wrapper': true, 'hidden': !isVisible}">
        <!-- <div class="topbox">
            <div class="details">
                <h4>details</h4>
            </div>
            <div class="comments">
                <h4>comments</h4>
            </div>
        </div> -->
        <div class="detail-header">
            <p>sujal.jpg</p>
            <img :src="cross" @click="hideDetail" />
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
</template>

<!-- js here -->
<script>
export default {
    name: 'RightSection',
    data() {
        return {
            cross: require('../../assets/cross.png'),
            dummyDetailImage: require('../../assets/dummyrecentImage.png'),
            profile: require('../../assets/dummyProfile.png'),
            key: require('../../assets/key.png'),
            isVisible: true
        }
    },
    methods: {
        hideDetail() {
            this.isVisible = false;
        }
    }
}
</script>

<!-- css here -->
<style>
.hidden{
    display: none;
}
.right-section {
    /* width: 20%; */
    height: 100vh;
    /* border: 1px solid blue; */
}

.detail-main-section-wrapper {
    position: absolute;
    /* left: 50%; */
    right: 0;
    background-color: white;
    /* display: none; */
    width: 20%;
    box-shadow: 0 0 0.2px grey;
}

.detail-main-section-wrapper .detail-header {
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
.filedetails{
    margin-bottom: 10px;
}
.heading-file-detail p {
    font-weight: 600;
    padding: 15px 0;
}
</style>