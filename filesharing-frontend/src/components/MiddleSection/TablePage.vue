<template>
  <div class="table-page">
    <div v-if="isLoading">
      Loading...
    </div>
    <div v-else>
      <!-- back Button -->
      <button v-if="folderStack.length > 0" @click="navigateToRoot" class="HomeButton">Home</button>
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
            <!-- display folders -->
            <tr v-for="folder in assets.folders.slice().reverse()" :key="folder.id"
              @dblclick="fetchDataForFolder(folder.id)">
              <td @dblclick="handleFolderClick(folder.id)" class="foldercurser"><img src="../../assets/bluefolder.webp" alt="" class="folderTable">{{ folder.name }}</td>
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
              <td class="optionButton">
                <img src="../../assets/optionbutton.png" alt="Options"
                  @click="showOptions('folder', folder.id, $event)">
                <OptionButton v-if="selectedItem.type === 'folder' && selectedItem.id === folder.id"
                  :position="position" @close="closeOptions" />
              </td>
            </tr>

            <!-- display files -->
            <tr v-for="file in assets.files.slice().reverse()" :key="file.id">
              <td>
                <a :href="file.url" target="_blank" @click="handleFileAccess(file)" class="fileanchor"><img src="../../assets/imageframe2.png" alt="" class="folderTable">{{ file.name }}</a>
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
              <td class="optionButton">
                <img src="../../assets/optionbutton.png" alt="Options" @click="showOptions('file', file.id, $event)" >
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
      folderStack: [] 
    };
  },
  mounted() {
    this.fetchDataForRootFolder();
    emitter.on('share-with-me', this.fetchDataForSharedWithMe);
    emitter.on('starredFilesFetched', this.updateStarredFiles); 
  },
  beforeMount() {
    emitter.off('share-with-me', this.fetchDataForSharedWithMe); 
    emitter.off('starredFilesFetched', this.updateStarredFiles);
  },

  watch: {
    '$store.state.refreshFolderList'(newValue) {
      if (newValue) {
        this.fetchDataForRootFolder();
        this.$store.dispatch('clearRefreshFolderList'); 
      }
    }
  },
  methods: {
    async fetchDataForRootFolder() {
      try {
        this.isLoading = true; 
        this.token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];

        const response = await fetch('http://127.0.0.1:8000/api/folders/root/contents', {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' +this.token
          }
        });

        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        this.assets = data;
        this.isLoading = false;
        this.folderStack = []; 
        console.log(data);
      } catch (error) {
        console.error('Error fetching data:', error);
        this.isLoading = false; 
      }
    },
    async fetchDataForFolder(folderId) {
      try {
        this.token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];
        
        this.isLoading = true; 
        const response = await fetch(`http://127.0.0.1:8000/api/folders/${folderId}/contents`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' +this.token
          }
        });

        if (response.status === 404) {
        
          this.assets = { files: [], folders: [] };
          this.isLoading = false;
          this.folderStack.push(folderId); 
          return;
        }

        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        this.assets = data;
        this.isLoading = false;
        this.folderStack.push(folderId); 
        console.log(data);
      } catch (error) {
        console.error('Error fetching data:', error);
        alert('Error fetching data: ' + error.message); 
        this.isLoading = false; 
      }
    },
    async fetchDataForSharedWithMe() {
      try {
        this.isLoading = true;
        this.token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];

        const response = await fetch('http://127.0.0.1:8000/api/shared-with-me', {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + this.token
          }
        });
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        console.log(data);
        //converting data into format to suit the template table
        const files = data.map(item => ({
          id: item.file.id,
          name: item.file.name,
          url: item.file.url,
          extension: item.file.extension,
          size: this.convertSizeToBytes(item.file.size),
          created_at: item.created_at,
          updated_at: item.updated_at,
          owner: { name: 'Sujal', id: item.user_id } 
        }));

        console.log(files);
        this.assets = { files, folders: [] }; // no folders for shared files
        this.isLoading = false;
        // this.folderStack = [];
        
      } catch (error) {
        console.error('Error fetching data:', error);
        this.isLoading = false;
      }
    },
    convertSizeToBytes(sizeStr) {
      try {
        console.log(`Converting size: ${sizeStr}`); 
        if (typeof sizeStr === 'number') {
          return sizeStr;
        }

        const units = {
          'B': 1,
          'KB': 1024,
          'MB': 1024 * 1024,
          'GB': 1024 * 1024 * 1024,
          'TB': 1024 * 1024 * 1024 * 1024,
        };
        const match = sizeStr.match(/(\d+(?:\.\d+)?)\s*(B|KB|MB|GB|TB)/i);
        if (match) {
          const value = parseFloat(match[1]);
          const unit = match[2].toUpperCase();
          const convertedSize = value * (units[unit] || 1);
          console.log(`Converted size: ${convertedSize} bytes`); 
          return convertedSize;
        } else {
          console.warn(`Size format not recognized: ${sizeStr}`); 
          return NaN;
        }
      } catch (error) {
        console.error(`Error converting size: ${error}`);
        return NaN;
      }
    },

    navigateToRoot() {
      document.cookie = 'folderId=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
      document.cookie = 'filefolderid=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
      this.fetchDataForRootFolder();
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

      console.log('Preview asset:', asset);
    },
    downloadAsset(asset) {
    
      console.log('Download asset:', asset);
    },
    renameAsset(asset) {
     
      console.log('Rename asset:', asset);
    },
    moveToTrash(asset) {
      
      console.log('Move asset to trash:', asset);
    },
    viewDetails(asset) {
    
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
      const expiryDate = new Date(); 
      expiryDate.setDate(expiryDate.getDate() + 1); // 1 day expiry
      document.cookie = `optionfileId=${id}; expires=${expiryDate.toUTCString()}; path=/`;

      const rect = event.target.getBoundingClientRect();
      const cardWidth = 200; 
      const cardHeight = 300;
      const padding = 10; 

      let top = rect.top + window.scrollY;
      let left = rect.left + window.scrollX;

    
      if (rect.left + cardWidth + padding > window.innerWidth) {
        left = window.innerWidth - cardWidth - padding;
      }

     
      if (rect.top + cardHeight + padding > window.innerHeight) {
        top = window.innerHeight - cardHeight - padding;
      }

      this.position = { top, left };
      this.selectedItem = { type, id };
    },
    closeOptions() {
      this.selectedItem = { type: '', id: null };
    },
    handleFolderClick(folderId) {
      //cookie for create folder
      const expiryDate = new Date(); 
      expiryDate.setDate(expiryDate.getDate() + 1); // 1 day expiry
      document.cookie = `folderId=${folderId}; expires=${expiryDate.toUTCString()}; path=/`;

      //cookie for create file
      const fileExpiryDate = new Date();
      fileExpiryDate.setDate(fileExpiryDate.getDate() + 1);
      document.cookie = `filefolderid=${folderId}; expires=${fileExpiryDate.toUTCString()}; path=/`; 
    },
    updateStarredFiles(data) {
        this.assets = { files: data, folders: [] };
    }
  }
};
</script>

<style>
.optionButton{
  cursor: pointer;
}
.HomeButton{
  display: inline-block;
  padding: 10px 20px;
  background-color: rgb(49, 141, 177);
  color: white;
  border: none;
  border-radius: 5px;
  text-align: center;
  text-decoration: none;
  cursor: pointer;
  transition: background-color 0.3s;
}
.HomeButton:hover {
  background-color: #45a049;
}
.foldercurser{
  cursor: pointer;
}
.fileanchor{
  text-decoration: none;
}
.fileanchor:visited{
  color: inherit;
}
.folderTable{
  height: 20px;
  width: 20px;
  /* background-color: black; */
  border-radius: 3px;
  margin-right: 10px;
}
.table-page {
  padding: 20px;
}

.assets-table {
  width: 100%;
  border-collapse: collapse;
}

.assets-table th, .assets-table td {
  border: none;
  padding: 8px;
}

.assets-table th {
  background-color: #f2f2f2;
  text-align: left;
  font-weight: bold; 
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