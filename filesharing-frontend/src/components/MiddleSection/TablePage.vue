<template>
  <div class="table-page">
    <div v-if="isLoading">
      Loading...
    </div>
    <div v-else>
      <!-- Back Button -->
      <button v-if="folderStack.length > 0" @click="navigateToRoot">Home</button>
      <template v-if="assets.files.length > 0 || assets.folders.length > 0">
        <table class="assets-table">
          <thead>
            <tr>
              <th>Asset Name</th>
              <th>Type</th>
              <th>Size</th>
              <th>Created</th>
              <th>Owner</th>
              <th>Last Modified</th>
              <!-- <th>Options</th> -->
            </tr>
          </thead>
          <tbody>
            <!-- Display folders -->
            <tr v-for="folder in assets.folders.slice().reverse()" :key="folder.id"
              @dblclick="fetchDataForFolder(folder.id)">
              <td>{{ folder.name }}</td>
              <td>Folder</td>
              <td>-</td>
              <td>{{ formatDate(folder.created_at) }}</td>
              <td>
                <div class="owner-td">
                  <img src="../../assets/ShaneSujal.png" alt="" class="owner">
                  <p>Shane</p>
                </div>
              </td>
              <td>{{ formatDate(folder.updated_at) }}</td>
              <td>
                <img src="../../assets/optionbutton.png" alt="Options"
                  @click="showOptions('folder', folder.id, $event)">
                <OptionButton v-if="selectedItem.type === 'folder' && selectedItem.id === folder.id"
                  :position="position" @close="closeOptions" />
              </td>
            </tr>

            <!-- Display files -->
            <tr v-for="file in assets.files.slice().reverse()" :key="file.id">
              <td>
                <a :href="file.url" target="_blank" @click="handleFileAccess">{{ file.name }}</a>
              </td>
              <td>{{ file.extension }}</td>
              <td>{{ formatSize(file.size) }}</td>
              <td>{{ formatDate(file.created_at) }}</td>
              <td>
                <div class="owner-td">
                  <img src="../../assets/ShaneSujal.png" alt="" class="owner">
                  <p>Shane</p>
                </div>
              </td>
              <td>{{ formatDate(file.updated_at) }}</td>
              <td>
                <img src="../../assets/optionbutton.png" alt="Options" @click="showOptions('file', file.id, $event)">
                <OptionButton v-if="selectedItem.type === 'file' && selectedItem.id === file.id" :position="position"
                  @close="closeOptions" />
              </td>
            </tr>
          </tbody>
        </table>
      </template>
      <template v-else>
        <div>No data available</div>
      </template>
    </div>
  </div>
</template>

<script>
import emitter from '../../eventbus.js';
import OptionButton from './optionbButton.vue';

export default {
  name: 'TablePage',
  components: {
    OptionButton,
  },
  data() {
    return {
      selectedItem: { type: '', id: null },
      position: { top: 0, left: 0 },
      isLoading: true,
      assets: {
        files: [],
        folders: []
      },
      folderStack: [] // Stack to keep track of folder navigation
    };
  },
  mounted() {
    // Fetch data for root folder when component is created
    this.fetchDataForRootFolder();
  },
  watch: {
    '$store.state.refreshFolderList'(newValue) {
      if (newValue) {
        this.fetchDataForRootFolder();
        this.$store.dispatch('clearRefreshFolderList'); // Reset the state after fetching
      }
    }
  },
  methods: {
    async fetchDataForRootFolder() {
      try {
        this.isLoading = true; // Set loading state to true
        let token = 'Bearer 1|GezUOhGwza1FcCulW6j3UsWq6EhayrL2v2tXlyLY7e0e92e1';
        const response = await fetch('http://127.0.0.1:8000/api/folders/root/contents', {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': token
          }
        });

        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        this.assets = data;
        this.isLoading = false;
        this.folderStack = []; // Reset folder stack
        console.log(data);
      } catch (error) {
        console.error('Error fetching data:', error);
        alert('Error fetching data: ' + error.message); // Provide user feedback
        this.isLoading = false; // Ensure loading state is updated
      }
    },
    async fetchDataForFolder(folderId) {
      try {
        this.isLoading = true; // Set loading state to true
        let token = 'Bearer 1|GezUOhGwza1FcCulW6j3UsWq6EhayrL2v2tXlyLY7e0e92e1';
        const response = await fetch(`http://127.0.0.1:8000/api/folders/${folderId}/contents`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': token
          }
        });

        if (response.status === 404) {
          // Handle folder with no content
          this.assets = { files: [], folders: [] };
          this.isLoading = false;
          this.folderStack.push(folderId); // Push current folder ID to stack
          return;
        }

        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        this.assets = data;
        this.isLoading = false;
        this.folderStack.push(folderId); // Push current folder ID to stack
        console.log(data);
      } catch (error) {
        console.error('Error fetching data:', error);
        alert('Error fetching data: ' + error.message); // Provide user feedback
        this.isLoading = false; // Ensure loading state is updated
      }
    },
    navigateToRoot() {
      this.fetchDataForRootFolder(); // Fetch data for the root folder
    },
    formatDate(dateString) {
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      return new Date(dateString).toLocaleDateString(undefined, options);
    },
    formatSize(size) {
      const i = Math.floor(Math.log(size) / Math.log(1024));
      return (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
    },
    previewAsset(asset) {
      // Implement preview functionality
      console.log('Preview asset:', asset);
    },
    downloadAsset(asset) {
      // Implement download functionality
      console.log('Download asset:', asset);
    },
    renameAsset(asset) {
      // Implement rename functionality
      console.log('Rename asset:', asset);
    },
    moveToTrash(asset) {
      // Implement move to trash functionality
      console.log('Move asset to trash:', asset);
    },
    viewDetails(asset) {
      // Implement view details functionality
      console.log('View details for asset:', asset);
    },
    handleFileAccess(file) {
      emitter.emit('file-accessed', {
        title: file.name,
        url: file.url,
        type: file.extension,
        accessed_at: new Date().toISOString()
      });
    },
    showOptions(type, id, event) {
      const rect = event.target.getBoundingClientRect();
      const cardWidth = 200; // Width of the card
      const cardHeight = 300; // Estimated height of the card
      const padding = 10; // Some padding to avoid edge collision

      let top = rect.top + window.scrollY;
      let left = rect.left + window.scrollX;

      // Check if the card will overflow on the right
      if (rect.left + cardWidth + padding > window.innerWidth) {
        left = window.innerWidth - cardWidth - padding;
      }

      // Check if the card will overflow on the bottom
      if (rect.top + cardHeight + padding > window.innerHeight) {
        top = window.innerHeight - cardHeight - padding;
      }

      this.position = { top, left };
      this.selectedItem = { type, id };
    },
    closeOptions() {
      this.selectedItem = { type: '', id: null };
    },
  }
};
</script>

<style>
.table-page {
  padding: 20px;
}

.assets-table {
  width: 100%;
  border-collapse: collapse;
}

.assets-table th, .assets-table td {
  border: none; /* Removed inner borders */
  padding: 8px;
}

.assets-table th {
  background-color: #f2f2f2;
  text-align: left;
  font-weight: bold; /* Made header text bold */
}

.option-button {
  width: 20px;
  height: 20px;
  cursor: pointer;
}

.asset-card {
  position: absolute;
  top: 20px;
  right: 20px;
  width: 300px;
  padding: 20px;
  background-color: white;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  border-radius: 5px;
  z-index: 1000;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.card-header h3 {
  margin: 0;
}

.card-header button {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1.2em;
}
.owner-td {
  display: flex;
  align-items: center;
}
.owner-td img {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  margin-right: 5px;
}

.asset-option {
  display: flex;
  align-items: center;
  cursor: pointer;
}
.asset-option img {
  width: 16px;
  height: 16px;
  margin-right: 5px;
}
</style>




<!-- <template>
  <div class="container">
    <DataTable :value="products" :style="tableStyles">
      <Column field="assetName" header="Asset Name" class="bold-column"></Column>
      <Column field="tag" header="Tag" class="bold-column"></Column>
      <Column field="created" header="Created" class="bold-column"></Column>
      <Column field="owner" header="Owner" class="bold-column"></Column>
      <Column field="lastModified" header="Last Modified" class="bold-column"></Column>
      <Column field="sujal">
        <template #body="slotProps">
          <img :src="slotProps.data.sujal" alt="No " class="image-style" :style="{height: '40px', width:'40px'}" @click="handleimageclick(slotProps.data)">
        </template>
      </Column>
    </DataTable>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const products = ref([
  { assetName: 'Asset 1', tag: 'Tag 1', created: '2023-01-01', owner: 'Owner 1', lastModified: '2023-01-10', sujal: require('../../assets/optionbutton.png')},
  { assetName: 'Asset 2', tag: 'Tag 2', created: '2023-02-01', owner: 'Owner 2', lastModified: '2023-02-10', sujal: require('../../assets/optionbutton.png')},
  { assetName: 'Asset 3', tag: 'Tag 3', created: '2023-03-01', owner: 'Owner 3', lastModified: '2023-03-10', sujal: require('../../assets/optionbutton.png')},
  { assetName: 'Asset 4', tag: 'Tag 4', created: '2023-04-01', owner: 'Owner 4', lastModified: '2023-04-10', sujal: require('../../assets/optionbutton.png')},
  { assetName: 'Asset 5', tag: 'Tag 5', created: '2023-05-01', owner: 'Owner 5', lastModified: '2023-05-10', sujal: require('../../assets/optionbutton.png')},
]);

const tableStyles = {
  minWidth: '55rem',
  minHeight: '20rem',
  margin: '20px 0 0 0',
  boxShadow: '0px 0px 10px rgba(0, 0, 0, 0.2)',
  border: '1px solid #ddd',
  borderRadius: '8px',
  backgroundColor: '#fff',
  padding: '10px',
  color: '#333',
  fontSize: '16px'
};
const handleimageclick = (data) => {
  alert("hi");
  console.log(data);
}
</script>

<style scoped>
.container {
  display: flex;
  justify-content: space-evenly;
}

.bold-column {
  font-weight: bold;
}

.image-style {
  width: 50px;
  height: 50px;
}
</style> -->