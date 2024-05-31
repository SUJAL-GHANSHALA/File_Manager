<template>
  <div>
    <div :style="{ top: `${position.top}px`, left: `${position.left}px` }" class="options-menu">
      <button @click="previewFile">Preview</button>
      <button @click="toggleStar">{{ isStarred ? 'Remove from star' : 'Add to star' }}</button>
      <button @click="downloadAsset">Download</button>
      <button @click="showRenameCard">Rename</button>
      <button @click="deleteForever" class="delete">Delete Forever</button>
      <button @click="move">Move</button>
      <button @click="viewDetails">Details</button>
      <button @click="closeMenu" class="close">Close</button>
    </div>

    <!-- rename file card starts -->
    <transition name="fade">
      <div v-if="showRenameDialog" class="rename-card">
        <h2>Rename File</h2>
        <input v-model="newFileName" type="text" placeholder="Enter new file name">
        <button @click="renameFile">Rename</button>
        <button @click="cancelRename">Cancel</button>
      </div>
    </transition>
    <!-- rename file card ends -->
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
      newFileName: '',
      isStarred: false
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
          // location.reload();
          return;
        }
      } catch (error) {
        console.error('Error toggling star status:', error);
        alert('Failed to toggle star status');
      }
    },
    downloadAsset() {
      console.log('Download asset');
    },
    renameAsset() {
      console.log('Rename asset');
    },
    move() {
      console.log('Move asset to trash');
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
    }
  }
};
</script>


<style scoped>
.options-menu {
  position: absolute;
  background-color: white;
  border: 1px solid #ddd;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  padding: 10px;
  z-index: 1000;
  font-family: Arial, sans-serif;
}

.options-menu button {
  display: block;
  width: 100%;
  margin-bottom: 5px;
  padding: 5px;
  border: none;
  background: none;
  text-align: left;
  cursor: pointer;
  font-family: Arial, sans-serif;
}

.options-menu button:hover {
  background-color: #f2f2f2;
}

.options-menu .delete {
  color: #ff4136;
}

.options-menu .close {
  color: #0074d9;
}

.rename-card {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: white;
  border: 1px solid #ddd;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  padding: 20px;
  z-index: 1001;
  font-family: Arial, sans-serif;
  width: 300px; 
  transition: all 0.3s ease;
}

.rename-card h2 {
  margin-bottom: 10px;
}

.rename-card input {
  display: block;
  width: calc(100% - 20px); 
  margin-bottom: 10px;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.rename-card button {
  padding: 8px 15px;
  border: none;
  border-radius: 4px;
  background-color: #0074d9;
  color: white;
  cursor: pointer;
}

.rename-card button + button {
  margin-left: 10px;
}


.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>