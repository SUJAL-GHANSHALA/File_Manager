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
    createFolder() {
      if (this.folderName) {
        alert(`Folder '${this.folderName}' created!`);
        this.sendDataToBackend();
        this.folderName = '';
        this.hidePopup(); // hide the component after creating the folder
      } else {
        alert('Please enter a folder name.');
      }
    },
    cancelCreateFolder() {
      this.hidePopup(); // hide the component when "Cancel" button is clicked
    },
    hidePopup() {
      this.$store.dispatch('hideCreateFolderPopup'); // Dispatch action to hide CreateFolder component
    },
    sendDataToBackend() {
      // function to send data to backend
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
  