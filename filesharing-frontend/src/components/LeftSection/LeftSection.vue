<template>
    <div class="left-section">
        <div class="left-section-top-img">
            <div>
                <img src="../../assets/Frame_5.png" alt="">
            </div>
        </div>

        <div class="add-folder">
            <div class="add-folder2">
                <div class="add addfolder" @click ="toggleCreateFolder">
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
                    <li @click="handlestarclick"><img src="../../assets/starframe.png" alt=""></li>
                    <li @click="handleShareWithMeClick"><img src="../../assets/sharedframe.png" alt=""> </li>
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
    // import {bus} from '../../eventbus.js';
    // import { ref } from 'vue';
    import emitter from '../../eventbus';

export default {
    name: 'LeftSection',
    data() {
        return {
            showStorageList: false,
        };
    },
    methods: {
        handleShareWithMeClick() {
            emitter.emit('share-with-me');
        },
        toggleStorageList() {
            this.showStorageList = !this.showStorageList;
        },
        async handlestarclick() {
            this.token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];

            try {
                const response = await fetch('http://127.0.0.1:8000/api/files/starred', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + this.token
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    emitter.emit('starredFilesFetched', data);
                } else {
                    console.error('Failed to fetch starred files');
                }
            } catch (error) {
                console.error('Error fetching starred files:', error);
            }
        },
        async handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                try {

                    this.token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];
                    
                    const filefolderIdCookie = document.cookie.split('; ').find(row => row.startsWith('filefolderid='));
                    let folder_id = null;
                    if (filefolderIdCookie) {
                        folder_id = filefolderIdCookie.split('=')[1];
                    }
                
                    // let formData = new FormData();
                    // formData.append('file', file);
                    // formData.append('folder_id',folder_id); 

                    let formData = new FormData();
                    formData.append('file', file);

                    if (folder_id !== null) {
                        formData.append('folder_id', folder_id.toString()); // Make sure it's a string
                    } else {
                        console.error("Folder ID is null or undefined!");
                    }

                    const response = await fetch('http://127.0.0.1:8000/api/files', {
                        method: 'POST',
                        headers: {
                            // 'Content-Type': 'application/json',
                            'Accept': '/*',
                            'Authorization': 'Bearer ' +this.token
                        },
                        body: formData
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();
                    alert('File uploaded successfully:', data);
                    document.cookie = 'folderId=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                    document.cookie = 'filefolderid=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                    
                    // dispatch vuex action to refresh the folder list
                    this.$store.dispatch('refreshFolderList');
                } catch (error) {
                    console.error('Error uploading file:', error);
                }
            }
        },
        toggleCreateFolder() {
            this.$store.dispatch('toggleCreateFolderPopup');
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