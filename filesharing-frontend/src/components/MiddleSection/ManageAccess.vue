<template>
  <div v-if="showPopup" class="popup">
    <div class="popup-content">
      <button class="close-button" @click="$emit('close-popup')">X</button>
      <h2>Manage Access for image6.jpg</h2>
      <input type="email" v-model="email" placeholder="Add people through email">
      <!-- <div class="access-options">
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
      </div> -->
      <p class="access-message">They can see this file in Shared with me</p>
      <div class="button-row">
        <!-- <button class="copy-link" @click="copyLink">Copy Link</button> -->
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
      accessType: 'public'
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
      const filefolderIdCookie = document.cookie.split('; ').find(row => row.startsWith('optionfileId='));
      let fileId = null;
      if (filefolderIdCookie) {
        fileId = filefolderIdCookie.split('=')[1];
      }

      const accessLevel = 'public'; 
      this.token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];

      fetch(`http://127.0.0.1:8000/api/files/${fileId}/generate-link`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': '*',
          'Authorization': 'Bearer ' +this.token
        },
        body: JSON.stringify({ access_control: accessLevel })
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Failed to generate link');
        }
        return response.json();
      })
      .then(data => {
        const link = data.link;
        navigator.clipboard.writeText(link);
        alert('Link copied to clipboard!');
      })
      .catch(error => {
        console.error('Error generating link:', error);
        alert('Failed to generate link');
      });
    },

    async done() {
      try {
        this.token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];

        const filefolderIdCookie = document.cookie.split('; ').find(row => row.startsWith('optionfileId='));
        let fileId = null;
        if (filefolderIdCookie) {
          fileId = filefolderIdCookie.split('=')[1];
        }

        const response = await fetch('http://127.0.0.1:8000/api/file-share', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'Authorization': 'Bearer ' +this.token
          },
          body: JSON.stringify({
            file_id: fileId,
            email: this.email,
            access_control: this.accessType
          })
        });

        if (!response.ok) {
          throw new Error('Failed to share file');
        }

        const data = await response.json();
        alert(data.message || 'File shared successfully!');
        this.$emit('close-popup');
      } catch (error) {
        console.error('Error sharing file:', error);
        alert('Failed to share file.');
      }
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
  justify-content: flex-end;
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
<!-- it was missing prop -->
<!-- fileId: {
      type: Number,
      required: true
    },  -->