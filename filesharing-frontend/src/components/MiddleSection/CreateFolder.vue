<template>
  <div v-if="showPopup" class="popup">
    <div class="popup-content">
      <h2>Create New Folder</h2>
      <input type="text" v-model="folderName" placeholder="Folder Name">
      <button @click="createFolder">Create</button>
      <button @click="cancelCreateFolder">Cancel</button>
    </div>
  </div>
</template>

<script>
import emitter from '../../eventbus';

export default {
  name: 'CreateFolder',
  data() {
    return {
      folderName: '',
      selectedFolderId: null
    };
  },
  computed: {
    showPopup() {
      return this.$store.state.showCreateFolderPopup;
    }
  },
  mounted() {
    emitter.on('folderClicked', this.handleFolderClicked);
  },
  methods: {
    handleFolderClicked(folderId) {
      this.selectedFolderId = folderId;
    },
    async createFolder() {
      if (this.folderName) {
        try {

          this.token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];

          const folderIdCookie = document.cookie.split('; ').find(row => row.startsWith('folderId='));
          let parent_id = null;

          if (folderIdCookie) {
            parent_id = folderIdCookie.split('=')[1];
          }

          const response = await fetch('http://127.0.0.1:8000/api/folders', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'Authorization': 'Bearer ' + this.token //space is needed after bearer, giving error otherwise
            },
            body: JSON.stringify({ name: this.folderName, path: '/storage', parent_id: parent_id })
          });

          if (!response.ok) {
            alert("Can't create folder now,maximum nesting level of 6 has reached");
            // document.cookie = 'folderId=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
            return;
          }

          const data = await response.json();
          console.log(data);
          alert(`Folder '${this.folderName}' created!`);
          this.$store.dispatch('refreshFolderList');
          this.folderName = '';
          this.hidePopup();
          document.cookie = 'folderId=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
          document.cookie = 'filefolderid=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        } catch (error) {
          console.error('Error creating folder:', error);
          alert('Error creating folder: ' + error.message);
        }
      } else {
        alert('Please enter a folder name.');
      }
    },
    cancelCreateFolder() {
      this.hidePopup();
    },
    hidePopup() {
      this.$store.dispatch('hideCreateFolderPopup');
    }
  }
};
</script>

<style scoped>
body {
  font-family: Arial, sans-serif;
}

button {
  margin: 10px;
  padding: 10px 20px;
  font-size: 16px;
  cursor: pointer;
}

.popup {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
}

.popup-content {
  background: white;
  padding: 20px;
  border-radius: 8px;
  width: 400px;
  text-align: center;
}

.popup-content h2 {
  margin-bottom: 20px;
}

.popup-content input {
  width: 80%;
  padding: 10px;
  margin-bottom: 20px;
  font-size: 16px;
}

.popup-content button {
  margin: 5px;
  padding: 10px 20px;
  font-size: 16px;
  cursor: pointer;
}
</style>