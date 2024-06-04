<template>
  <div>
    <div :style="{ top: `${position.top}px`, left: `${position.left}px` }" class="options-menu">
      <button @click="previewFile">Preview</button>
      <button @click="toggleStar">{{ isStarred ? 'Remove from star' : 'Add to star' }}</button>
      <button @click="showRenameCard">Rename</button>
      <button @click="deleteForever" class="delete">Delete Forever</button>
      <button @click="move">Move</button>
      <button @click="viewDetails">Details</button>
      <button @click="closeMenu" class="close">Close</button>
    </div>

    <!-- Rename file card starts -->
    <transition name="fade">
      <div v-if="showRenameDialog" class="rename-card">
        <h2>Rename File</h2>
        <input v-model="newFileName" type="text" placeholder="Enter new file name">
        <button @click="renameFile">Rename</button>
        <button @click="cancelRename" class="cancelButton">Cancel</button>
      </div>
    </transition>
    <!-- Rename file card ends -->

    <!-- Preview modal starts -->
    <div v-if="showPreview" class="preview-modal" @click="closePreview">
      <div class="preview-content" @click.stop>
        <button class="close" @click="closePreview">×</button>
        <template v-if="fileType === 'jpg'">
          <img :src="previewUrl" alt="File Preview">
        </template>
        <template v-else-if="fileType === 'png'">
          <iframe :src="previewUrl" frameborder="0"></iframe>
        </template>
        <template v-else-if="fileType === 'pdf'">
          <iframe :src="previewUrl" frameborder="0"></iframe>
        </template>
        <!-- add more templates here -->
      </div>
    </div>
    <!-- Preview modal ends -->


    <!-- Move file card starts -->
    <transition name="fade">
      <div v-if="showMoveDialog" class="move-card">
        <h2>Move File</h2>
        <select v-model="selectedFolder" @change="handleFolderChange">

          <!-- default option available -->
          <option value="null">Root</option>
          <!-- iterate over fetched folders -->
          <option :value="folder.id" v-for="folder in folders" :key="folder.id">{{ folder.name }}</option>
        </select>
        <button @click="confirmMove">Move</button>
        <button @click="cancelMove">Cancel</button>
      </div>
    </transition>
    <!-- Move file card ends -->

  </div>
</template>

<script>
import emitter from '../../eventbus.js';

export default {
  name: 'OptionButton',
  props: {
    position: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      showRenameDialog: false,
      showPreview: false,
      newFileName: '',
      previewUrl: '',
      fileType: '',
      isStarred: false,
      token: '',
      showMoveDialog: false,
      selectedFolder: null
    };
  },
  created() {
    this.getFileDetails();
  },
  methods: {
    async getFileDetails() {
      const filefolderIdCookie = document.cookie.split('; ').find(row => row.startsWith('optionfileId='));
      let fileId = null;
      if (filefolderIdCookie) {
        fileId = filefolderIdCookie.split('=')[1];
      }

      this.token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];

      try {
        const response = await fetch(`http://127.0.0.1:8000/api/files/${fileId}`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + this.token
          }
        });

        if (response.ok) {
          const data = await response.json();
          this.isStarred = data.is_starred;
        }
      } catch (error) {
        console.error('Error fetching file details:', error);
      }
    },
    closeMenu() {
      document.cookie = 'optionfileId=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
      this.$emit('close');
    },
    async toggleStar() {
      const filefolderIdCookie = document.cookie.split('; ').find(row => row.startsWith('optionfileId='));
      let fileId = null;
      if (filefolderIdCookie) {
        fileId = filefolderIdCookie.split('=')[1];
      }

      this.token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];

      try {
        const response = await fetch(`http://127.0.0.1:8000/api/files/${fileId}/star`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + this.token
          }
        });

        if (response.ok) {
          const data = await response.json();
          this.isStarred = data.is_starred;
          alert(data.is_starred ? 'File starred successfully' : 'File unstarred successfully');
          document.cookie = 'optionfileId=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
          this.$emit('close');
          return;
        }
      } catch (error) {
        console.error('Error toggling star status:', error);
        alert('Failed to toggle star status');
      }
    },
    renameAsset() {
      console.log('Rename asset');
    },
    async move() {
      try {
        this.token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];

        const response = await fetch('http://127.0.0.1:8000/api/folders', {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + this.token
          }
        });

        if (response.ok) {
          const data = await response.json();
          console.log(data); // Ensure data structure

          // Assuming folders are directly available in data
          this.folders = data;

          // Check if the root folder exists
          const rootFolder = this.folders.find(folder => folder.parent_id === null);
          if (!rootFolder) {
            // If root folder does not exist, add a dummy root folder
            const dummyRootFolder = {
              id: 'null',
              name: 'Root'
            };
            this.folders.unshift(dummyRootFolder); 
            this.selectedFolder = 'root'; 
          } else {
            this.selectedFolder = rootFolder.id; 
          }
        } else {
          console.error('Failed to fetch folders');
        }
      } catch (error) {
        console.error('Error fetching folders:', error);
      }
      this.showMoveDialog = true;
    },
    async confirmMove() {
      try {
        const filefolderIdCookie = document.cookie.split('; ').find(row => row.startsWith('optionfileId='));
        let fileId = null;
        if (filefolderIdCookie) {
          fileId = filefolderIdCookie.split('=')[1];
        }

        const movefolderId = document.cookie.split('; ').find(row => row.startsWith('moveFolderId='));
        alert(movefolderId);
        
        let parentFolderId = null;
        if (filefolderIdCookie) {
          parentFolderId = movefolderId.split('=')[1];
        }

        this.token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];

        const response = await fetch(`http://127.0.0.1:8000/api/files/${fileId}/move`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + this.token
          },
          body: JSON.stringify({
            folder_id: parentFolderId 
          })
        });

        if (response.ok) {
      
          alert('File moved successfully');
          document.cookie = 'moveFolderId=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
          document.cookie = 'optionfileId=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
          location.reload();
          // this.showMoveDialog = false;
        } else {
          console.error('Failed to move file');
        }
      } catch (error) {
        console.error('Error moving file:', error);
      }
    },

    setFolderIdCookie(folderId) {
      document.cookie = `moveFolderId=${folderId}; expires=Thu, 01 Jan 2070 00:00:00 UTC; path=/`;
    },
    // Method to handle folder change
    handleFolderChange() {
      // Get the selected folder ID
      const selectedFolderId = this.selectedFolder;
      // Set the selected folder ID in a cookie
      this.setFolderIdCookie(selectedFolderId);
    },
    cancelMove () {
      this.showMoveDialog = false;
    },
    viewDetails() {
      emitter.emit('showDetails');
      this.$emit('close');
    },
    async deleteForever() {
      const filefolderIdCookie = document.cookie.split('; ').find(row => row.startsWith('optionfileId='));
      let fileId = null;
      if (filefolderIdCookie) {
        fileId = filefolderIdCookie.split('=')[1];
      }

      this.token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];

      try {
        const response = await fetch(`http://127.0.0.1:8000/api/files/${fileId}`, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + this.token
          }
        });

        if (response.status === 204) {
          alert('File deleted successfully');
          document.cookie = 'optionfileId=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
          this.$emit('close');
          location.reload();
          return;
        }
      } catch (error) {
        console.error('Error deleting file:', error);
        alert('Failed to delete file');
      }
      this.$emit('close');
    },
    showRenameCard() {
      this.showRenameDialog = true;
    },
    async renameFile() {
      const filefolderIdCookie = document.cookie.split('; ').find(row => row.startsWith('optionfileId='));
      let fileId = null;
      if (filefolderIdCookie) {
        fileId = filefolderIdCookie.split('=')[1];
      }

      this.token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];

      try {
        const response = await fetch(`http://127.0.0.1:8000/api/files/${fileId}/rename`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + this.token
          },
          body: JSON.stringify({
            name: this.newFileName,
          }),
        });

        if (response.ok) {
          alert('File renamed successfully');
          document.cookie = 'optionfileId=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
          this.$emit('close');
          location.reload();
          return;
        } else {
          alert("File name already exists");
        }
      } catch (error) {
        console.error('Error renaming file:', error);
        alert('Failed to rename file');
      }
      this.showRenameDialog = false;
    },
    cancelRename() {
      this.showRenameDialog = false;
    },
    async previewFile() {
      const filefolderIdCookie = document.cookie.split('; ').find(row => row.startsWith('optionfileId='));
      let fileId = null;
      if (filefolderIdCookie) {
        fileId = filefolderIdCookie.split('=')[1];
      }

      this.token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];

      try {
        const response = await fetch(`http://127.0.0.1:8000/api/files/${fileId}`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + this.token
          }
        });

        if (response.ok) {
          const data = await response.json();
          this.previewUrl = data.url;
          this.fileType = data.extension; 
          this.showPreview = true;
        }
      } catch (error) {
        console.error('Error fetching file preview:', error);
        alert('Failed to fetch file preview');
      }
    },
    closePreview() {
      this.showPreview = false;
    }
  }
};
</script>

<style scoped>
.options-menu {
  position: absolute;
  background-color: white;
  border: 1px solid #ddd;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
  border-radius: 5px;
  padding: 10px;
  z-index: 1000;
}
.options-menu button {
  display: block;
  width: 100%;
  text-align: left;
  padding: 8px 10px;
  border: none;
  background: none;
  cursor: pointer;
}
.options-menu button.delete {
  color: red;
}
.options-menu button.close {
  color: gray;
}

.rename-card {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: #f9f9f9;
  padding: 20px;
  border: 1px solid #ccc;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  z-index: 1001;
  max-width: 400px;
  width: 90%;
}

.rename-card h2 {
  margin-top: 0;
  font-size: 1.2em;
  color: #333;
}


.rename-card input[type="text"] {
  width: 100%;
  padding: 10px;
  margin-top: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  box-sizing: border-box;
}

.rename-card button {
  margin-top: 15px;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  background-color: #007bff;
  color: #fff;
  font-size: 1em;
  transition: background-color 0.3s ease;
}
.cancelButton {
   position: relative;
   left: 160px;
}

.rename-card button:hover {
  background-color: #0056b3;
}

.preview-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1002;
}
.preview-content {
  position: relative;
  background-color: white;
  padding: 20px;
  border-radius: 5px;
  max-width: 90%;
  max-height: 90%;
  overflow: auto;
}
.preview-content img {
  max-width: 100%;
  height: auto;
}
.preview-content iframe {
  width: 100%;
  height: 500px;
}
.preview-content .close {
  position: absolute;
  top: 0px;
  right: 0px;
  font-size: 40px;
  cursor: pointer;
  background: none;
  border: none;
  color: gray;
}

.move-card {
  position: absolute;
  top: 30%;
  left: 40%;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  padding: 20px;
  max-width: 400px;
  margin: 0 auto;
  text-align: center;
}

.move-card h2 {
  margin-bottom: 20px;
  font-size: 1.5em;
  color: #333;
}

.move-card select {
  width: 100%;
  padding: 10px;
  margin-bottom: 20px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.move-card-buttons {
  display: flex;
  justify-content: space-between;
}

.move-card button {
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1em;
}

.move-card button:first-of-type {
  background-color: #4caf50;
  color: #fff;
}

.move-card button.cancelButton {
  background-color: #f44336;
  color: #fff;
}

</style>