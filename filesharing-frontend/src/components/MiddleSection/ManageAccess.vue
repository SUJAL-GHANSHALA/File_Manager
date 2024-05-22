<!-- ManageAccess.vue -->
<!-- <template>
    <div v-if="showPopup" class="popup">
      <div class="popup-content">
        <h2>Manage Access for {{ fileName }}</h2>
        <input type="email" v-model="email" placeholder="Add people through email">
        <div class="access-options">
          <label>
            <input type="radio" value="restricted" v-model="accessType">
            Restricted
          </label>
          <label>
            <input type="radio" value="private" v-model="accessType">
            Private
          </label>
          <label>
            <input type="radio" value="public" v-model="accessType">
            Public
          </label>
        </div>
        <p class="access-message">{{ accessMessage }}</p>
        <div class="button-row">
          <button class="copy-link" @click="copyLink">Copy Link</button>
          <button class="done" @click="done">Done</button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      fileName: {
        type: String,
        required: true
      }
    },
    data() {
      return {
        showPopup: false,
        email: '',
        accessType: 'restricted'
      };
    },
    computed: {
      accessMessage() {
        if (this.accessType === 'restricted') {
          return 'The file is restricted, anyone who has access can see the file.';
        } else if (this.accessType === 'private') {
          return 'The file is private, add a user or copy the link to share.';
        } else {
          return 'The file is public, anyone with the link can access the file.';
        }
      }
    },
    methods: {
      copyLink() {
        // Function to copy the link to clipboard
      },
      done() {
        // Function to handle the Done action
      }
    }
  };
  </script> -->
  <template>
    <div v-if="showPopup" class="popup">
      <div class="popup-content">
        <button class="close-button" @click="$emit('close-popup')">X</button>
        <h2>Manage Access for {{ fileName }}</h2>
        <input type="email" v-model="email" placeholder="Add people through email">
        <div class="access-options">
          <label>
            <input type="radio" value="restricted" v-model="accessType">
            Restricted
          </label>
          <label>
            <input type="radio" value="private" v-model="accessType">
            Private
          </label>
          <label>
            <input type="radio" value="public" v-model="accessType">
            Public
          </label>
        </div>
        <p class="access-message">{{ accessMessage }}</p>
        <div class="button-row">
          <button class="copy-link" @click="copyLink">Copy Link</button>
          <button class="done" @click="done">Done</button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      fileName: {
        type: String,
        required: true
      },
      showPopup: {
        type: Boolean,
        default: false
      }
    },
    data() {
      return {
        email: '',
        accessType: 'restricted'
      };
    },
    computed: {
      accessMessage() {
        if (this.accessType === 'restricted') {
          return 'The file is restricted, anyone who has access can see the file.';
        } else if (this.accessType === 'private') {
          return 'The file is private, add a user or copy the link to share.';
        } else {
          return 'The file is public, anyone with the link can access the file.';
        }
      }
    },
    methods: {
      copyLink() {
        // Function to copy the link to clipboard
      },
      done() {
        this.$emit('close-popup');
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
    position: relative;
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
  
  .access-options {
    display: flex;
    justify-content: space-around;
    margin-bottom: 20px;
  }
  
  .access-options label {
    display: flex;
    align-items: center;
    gap: 5px;
  }
  
  .access-message {
    margin-bottom: 60px;
    font-size: 14px;
    color: #555;
  }
  
  .button-row {
    display: flex;
    justify-content: space-between;
    position: absolute;
    bottom: 20px;
    left: 20px;
    right: 20px;
  }
  
  .copy-link {
    margin-left: 0;
  }
  
  .done {
    margin-right: 0;
  }
  </style>
  