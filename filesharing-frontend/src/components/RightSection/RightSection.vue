<template>
  <div :class="{'detail-main-section-wrapper': true, 'hidden': !isVisible}">
    <div class="topbox">
      <div :class="{'details': true, 'active': activeSection === 'details'}" @click="activeSection = 'details'">
        <h4>Details</h4>
      </div>
      <div :class="{'comments': true, 'active': activeSection === 'comments'}" @click="activeSection = 'comments'">
        <h4 @click="fetchComments">Comments</h4>
      </div>
      <img :src="cross" class="cross-icon" @click="hideDetail" />
    </div>
    <div v-if="activeSection === 'details'">
      <div class="detail-header">
        <p>{{ file.name }}</p> <!-- display file name -->
      </div>
      <div class="img-section">
        <img :src="file.url" /> <!-- display file image -->
      </div>
      <div class="access-section">
        <p>Who has access</p>
        <div class="all-access-data">
          <!-- placeholder for access members -->
        </div>
        <div class="access-btn">
          <button @click="showManageAccess = true">
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
          <p class="filedetails">{{ file.path }}</p> 
          <p><strong>Type</strong></p>
          <p class="filedetails">{{ file.mime }}</p> 
          <p><strong>Size</strong></p>
          <p class="filedetails">{{ formatSize(file.size) }}</p> 
          <p><strong>Owner</strong></p>
          <p class="filedetails">{{ file.user_name }}</p> 
          <p><strong>Modified</strong></p>
          <p class="filedetails">{{ formatDate(file.updated_at) }}</p> 
          <p><strong>Created</strong></p>
          <p class="filedetails">{{ formatDate(file.created_at) }}</p> 
        </div>
      </div>
    </div>
    <div v-else class="comments">
      <div class="comments-section">
        <textarea v-model="newComment" placeholder="Add your comment here..."></textarea>
        <button @click="sendComment">Send</button>
      </div>
      <div v-if="comments.length > 0" class="comments-list">
        <div v-for="comment in comments" :key="comment.id" class="comment">
          <p>{{ comment.user.name }}: {{ comment.content }}</p>
        </div>
      </div>
      <div v-else class="no-comments">
        <p>No comments made or exist.</p>
      </div>
    </div>
    <ManageAccess 
      v-if="showManageAccess" 
      :fileName="file.name" 
      :showPopup="showManageAccess"
      @close-popup="showManageAccess = false"
    />
  </div>
</template>

<script>
import emitter from '../../eventbus.js';
import ManageAccess from '../MiddleSection/ManageAccess.vue';

export default {
  name: 'RightSection',
  components: {
    ManageAccess
  },
  data() {
    return {
      cross: require('../../assets/cross.png'),
      key: require('../../assets/key.png'),
      isVisible: false,
      activeSection: 'details',
      showManageAccess: false,
      newComment: '',
      comments: [],
      file: {} // initialize an empty object to hold file details
    }
  },
  created() {
    emitter.on('showDetails', this.showDetail);
    emitter.on('showDetails', this.getFileDetails);
  },
  beforeUnmount() {
    emitter.off('showDetails', this.showDetail);
    emitter.off('showDetails', this.getFileDetails);
  },
  methods: {
    hideDetail() {
      document.cookie = 'optionfileId=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
      this.isVisible = false;
    },
    showDetail() {
      this.isVisible = true;
      this.activeSection = 'details';
    },
    async getFileDetails() {
      const token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];
      const filefolderIdCookie = document.cookie.split('; ').find(row => row.startsWith('optionfileId='));
      let fileId = null;
      if (filefolderIdCookie) {
        fileId = filefolderIdCookie.split('=')[1];
      }
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/files/${fileId}`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
          },
        });
        if (response.ok) {
          this.file = await response.json(); // update file object with response data
        } else {
          console.error('Failed to get file details:', response.statusText);
        }
      } catch (error) {
        console.error('Error getting file details:', error);
      }
    },
    formatSize(bytes) {
      const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
      if (bytes === 0) return '0 Byte';
      const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
      return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    },
    formatDate(dateString) {
      const date = new Date(dateString);
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      return date.toLocaleDateString(undefined, options);
    },
    async sendComment() {
      if (this.newComment.trim() === '') return;
      const token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];
      const filefolderIdCookie = document.cookie.split('; ').find(row => row.startsWith('optionfileId='));
      let fileId = null;
      if (filefolderIdCookie) {
        fileId = filefolderIdCookie.split('=')[1];
      }
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/files/${fileId}/comments`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            content: this.newComment,
          }),
        });
        if (response.ok) {
          this.newComment = '';
          this.fetchComments();
          document.cookie = 'folderId=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        } else {
          console.error('Failed to send comment:', response.statusText);
        }
      } catch (error) {
        console.error('Error sending comment:', error);
      }
      document.cookie = 'folderId=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    },
    async fetchComments() {
      const token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];
      const filefolderIdCookie = document.cookie.split('; ').find(row => row.startsWith('optionfileId='));
      let fileId = null;
      if (filefolderIdCookie) {
        fileId = filefolderIdCookie.split('=')[1];
      }
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/files/${fileId}/comments`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
          }
        });
        if (response.ok) {
          const data = await response.json();
          this.comments = data.map(comment => ({
            id: comment.id,
            content: comment.content,
            user: comment.user
          }));
          document.cookie = 'folderId=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        } else {
          console.error('Failed to fetch comments:', response.statusText);
          this.comments = [];
        }
      } catch (error) {
        console.error('Error fetching comments:', error);
        this.comments = [];
      }
      document.cookie = 'folderId=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    }
  }
}
</script>



<style>
.hidden {
    display: none;
}
.right-section {
    height: 100vh;
}
.detail-main-section-wrapper {
    position: absolute;
    right: 0;
    background-color: white;
    width: 20%;
    box-shadow: -2px 0 5px rgba(0,0,0,0.1);
    border-left: 1px solid #e0e0e0;
}
.topbox {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
    height: 3rem;
    box-shadow: 0 0.6px 0 grey;
    border-radius: 0 0 0 10px;
}
.topbox .details, .topbox .comments {
    flex: 1;
    text-align: center;
    cursor: pointer;
}
.topbox .active {
    font-weight: bold;
    border-bottom: 2px solid #5f40db;
}
.cross-icon {
    cursor: pointer;
    width: 1rem;
    height: 1rem;
}
.detail-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
    height: 3rem;
    box-shadow: 0 0.6px 0 grey;
}
.detail-header p {
    font-weight: 600;
}
.img-section {
    height: 150px;
    display: flex;
    justify-content: center;
    align-items: center;
    margin:  10px;
}
.img-section img{
  height: 100px;
  margin: 10px;
  border-radius: 10px;
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
    justify-content: center;
}
.access-btn button {
    width: 10rem;
    height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 600;
    gap: 4px;
    background-color: white;
    border: 1px solid #2e2e2e;
    color: #2e2e2e;
    border-radius: 10px;
    margin: 10px;
    cursor: pointer;
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
.filedetails {
    margin-bottom: 10px;
}
.heading-file-detail p {
    font-weight: 600;
    padding: 15px 0;
}
.comments-section {
    margin: 20px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}
.comments-section textarea {
    flex-grow: 1;
    width: calc(100% - 40px);
    padding: 10px;
    margin-bottom: 10px;
    resize: none;
}
.comments-section button {
    width: 100%;
    padding: 10px;
    background-color: #5f40db;
    color: white;
    border: none;
    cursor: pointer;
}
</style>
