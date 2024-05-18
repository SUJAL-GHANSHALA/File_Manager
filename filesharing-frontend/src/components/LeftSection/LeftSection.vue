<template>
    <div class="left-section">
        <div class="left-section-top-img">
            <div>
                <img src="../../assets/Frame_5.png" alt="">
            </div>
        </div>

        <div class="add-folder">
            <div class="add-folder2">
                <div class="add addfolder">
                    <p>Add Folder</p>
                </div>
                <div class="add addfile">
                    <label for="file-upload" class="file-upload-label">Add File</label>
                    <input type="file" id="file-upload" @change="handleFileUpload">
                </div>
            </div>
        </div>

        <div class="storage-parent">
            <div class="storage-new">
                <div class="storage" @click="toggleStorageList">
                    <img src="../../assets/arrowright.png" alt="" class="arrowright">
                    <img src="../../assets/harddrive.png" alt="" class="harddrive-img">
                    <div class="storage-text storage-text-new">Storage</div>
                </div>
                <div class="storage2" v-show="showStorageList">
                    <ul class="storage-list">
                        <li> <img src="../../assets/arrowright.png" alt=""> <img src="../../assets/storagefolder.png"
                                alt=""> <span class="storage-text">Defect images</span></li>
                        <li> <img src="../../assets/arrowright.png" alt=""> <img src="../../assets/storagefolder.png"
                                alt=""> <span class="storage-text">Assets</span></li>
                        <li> <img src="../../assets/arrowright.png" alt=""> <img src="../../assets/storagefolder.png"
                                alt=""> <span class="storage-text">UI files</span></li>
                        <li> <img src="../../assets/arrowright.png" alt=""> <img src="../../assets/storagefolder.png"
                                alt=""> <span class="storage-text">Documentation</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="list-parent">
            <div>
                <ul class="ul-list">
                    <li><img src="../../assets/starframe.png" alt=""></li>
                    <li><img src="../../assets/sharedframe.png" alt=""> </li>
                    <li><img src="../../assets/statisticsframe.png" alt=""> </li>
                    <li><img src="../../assets/settingsframe.png" alt=""> </li>
                </ul>
            </div>
        </div>

        <div class="leftsection-footer">
            <div class="leftsection-footer2">
                <div class="leftsection-cloud">
                    <img src="../../assets/frame8.png" alt="">
                </div>
                <div class="leftsection-bar">
                    <!-- <img src="../../assets/framebar.png" alt=""> -->
                    <div class="progressBar"></div>
                    <p>You have used 0 GB out of 15 GB.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- js here -->
<script >
    // import { ref } from 'vue';
    export default {
        name: 'LeftSection',
        data() {
            return {
                showStorageList: false
            };
        },
        methods: {
            toggleStorageList() {
                this.showStorageList = !this.showStorageList;
            },
            handleFileUpload(event){
                const file = event.target.files[0];
                if(file) {
                    alert("sujal");
                    console.log(file.name);
                }
            }
        }
    }
</script>

<!-- css here -->
<style>
    .left-section{
        width: 20%;
        height: 100vh;
        /* border: 1px solid red; */
        background-color: #37A0EA;
        /* display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center; */
        font-family: sans-serif;
        font-size: 16px;
    }
    .left-section-top-img{
        background-color: #3093da;
        width: 100%;
    }
    .left-section-top-img img{
        height: 60px;
        width: 100%;
    }
    .add-folder{
        margin: 20px 0;
    }
    .add-folder2{
        /* border: 1px solid red; */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-around;
    }
    .addfolder{
        margin: 8px;
    }
    .add{
        padding: 10px;
        width: 180px;
        border:2px solid #3093da;
        border-radius: 40px;
        text-align: center;
        border: blue;
        background-color: rgba(255, 255, 255, 0.8);;
        cursor: pointer;
    }
    .add:hover{
        background-color: gray;
    }
    .storage-parent{
        margin-left: 20px;
        /* border: 1px solid red; */
    }
    .storage-new{
        /* border: 1px solid red; */
        width: 100%;
        /* margin: auto; */
    }
    .storage{
        margin-left: 5px;
        /* position: relative; */
        /* right: 10%; */
    }
    .storage-text{
        display: inline-block;
        position: relative;
        left: 5px;
        bottom: 5px;
        color: white;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
    }
    .storage-text-new{
        font-size: 20px;
    }
    .arrowright{
        cursor: pointer;
    }
    .storage2{
        margin-left: 18px;
    }
    .storage-list{
        list-style-type: none;
        position: relative;
        top: 4px;
    }
    .storage-list li {
        /* border: 1px solid red; */
        /* width: 95%; */
        /* position: relative;
        left: 10%; */
        /* display: none; */
    }
    .list-parent{
        margin-top: 20px;
        margin-left: 20px;
        /* border: 1px solid red; */
    }
    .ul-list{
        list-style-type: none;
        cursor: pointer;
    }

    .leftsection-footer{
        /* border: 1px solid red; */
        position: absolute;
        /* top: 335px; */
        bottom: 1%;
        padding: 5px;
        /* padding-bottom: 14px; */
    }
    .leftsection-footer2{
        margin-left: 12px;
    }
    .leftsection-bar img{
        width: 90%;
    }
    .leftsection-bar p{
        margin-top: 1px;
        font-size: 15px;
        color: white;
    }
    #file-upload {
    display: none;
    }

    #file-upload::-webkit-file-upload-button {
    visibility: hidden;
    }
    /* #file-upload::before {
    content: 'No file chosen';
    display: inline-block;
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    border-radius: 4px;
    } */
    .progressBar{
        height: 10px;
        width: 85%;
        border-radius: 5px;
        background-color: white;
    }
</style>