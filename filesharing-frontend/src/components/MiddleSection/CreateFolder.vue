<template>
  <div v-if="showPopup" class="popup">
    <div class="popup-content">
      <h2>Create New Folder</h2>
      <input type="text" v-model="folderName" placeholder="Folder Name">
      <button @click="createFolder">Create</button>
      <button @click="cancelCreateFolder">Cancel</button> <!-- Add click event handler -->
    </div>
  </div>
</template>

<script>
export default {
  name: 'CreateFolder',
  data() {
    return {
      folderName: ''
    };
  },
  computed: {
    showPopup() {
      return this.$store.state.showCreateFolderPopup; // Access showCreateFolderPopup from Vuex store
    }
  },
  methods: {
    async createFolder() {
      if (this.folderName) {
        try {
          let parent_id = null;
          const token = 'Bearer 1|GezUOhGwza1FcCulW6j3UsWq6EhayrL2v2tXlyLY7e0e92e1';
          const response = await fetch('http://127.0.0.1:8000/api/folders', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'Authorization': token
            },
            body: JSON.stringify({ name: this.folderName, path: '/storage', parent_id })
          });

          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }

          const data = await response.json();
          console.log(data);
          alert(`Folder '${this.folderName}' created!`);
          this.$store.dispatch('refreshFolderList'); // Dispatch action to refresh folder list
          this.folderName = '';
          this.hidePopup();
          // location.reload();
        } catch (error) {
          console.error('Error creating folder:', error);
          alert('Error creating folder: ' + error.message);
        }
      } else {
        alert('Please enter a folder name.');
      }
    },
    cancelCreateFolder() {
      this.hidePopup(); // hide the component when "Cancel" button is clicked
    },
    hidePopup() {
      this.$store.dispatch('hideCreateFolderPopup'); // Dispatch action to hide CreateFolder component
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
  