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

<template>
  <div class="table-page">
    <div v-if="isLoading">
      Loading...
    </div>
    <div v-else>
      <template v-if="assets.length > 0">
        <table class="assets-table">
          <thead>
            <tr>
              <th>AssetName</th>
              <th>Tag</th>
              <th>Created</th>
              <th>Owner</th>
              <th>Last Modified</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="asset in assets" :key="asset.id">
              <td>{{ asset.name }}</td>
              <td>{{ asset.size }}</td>
              <td>{{ asset.type }}</td>
              <td>{{ asset.owner }}</td>
              <td>{{ asset.modified }}</td>
              <td>
                <img :src="optionsIcon" @click="showOptions(asset)" class="option-button" />
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
export default {
  name: 'TablePage',
  data() {
    return {
      isLoading: true,
      assets: [],
      currentFolderId: null,
      optionsIcon: require('../../assets/optionbutton.png'),
      selectedAsset: null
    };
  },
  created() {
    // Simulate fetching data for the root folder initially
    this.fetchData(null); // Pass null to indicate root folder
  },
  methods: {
    fetchData(folderId) {
      // Simulate fetching data from backend
      setTimeout(() => {
        // Assume data is fetched successfully
        // Modify this logic to fetch data dynamically based on folderId
        if (folderId === null) {
          // Data for root folder
          this.assets = [
            { id: 1, name: 'Asset 1', size: '1MB', type: 'Image', owner: 'User1', modified: '2023-05-01' },
            { id: 2, name: 'Asset 2', size: '2MB', type: 'Video', owner: 'User2', modified: '2023-05-02' },
            // Add more assets as needed
          ];
        } else {
          // Data for subfolder (simulated)
          this.assets = [
            { id: 3, name: 'Subfolder Asset 1', size: '1MB', type: 'Document', owner: 'User3', modified: '2023-05-03' },
            { id: 4, name: 'Subfolder Asset 2', size: '3MB', type: 'Image', owner: 'User4', modified: '2023-05-04' },
            // Add more assets as needed
          ];
        }
        this.isLoading = false;
      }, 2000); // Simulating 2 seconds delay
    },
    showOptions(asset) {
      this.selectedAsset = asset;
    },
    closeCard() {
      this.selectedAsset = null;
    },
    previewAsset() {
      // Implement preview functionality
    },
    downloadAsset() {
      // Implement download functionality
    },
    renameAsset() {
      // Implement rename functionality
    },
    addToStarred() {
      // Implement add to starred functionality
    },
    addTags() {
      // Implement add tags functionality
    },
    moveAsset() {
      // Implement move functionality
    },
    moveToTrash() {
      // Implement move to trash functionality
    },
    viewDetails() {
      // Implement view details functionality
    }
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
  border: 1px solid #ddd;
  padding: 8px;
}

.assets-table th {
  background-color: #f2f2f2;
  text-align: left;
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
  font-size: 20px;
  cursor: pointer;
}

.asset-card ul {
  list-style-type: none;
  padding: 0;
}

.asset-card ul li {
  margin: 10px 0;
}

.asset-card ul li button {
  width: 100%;
  padding: 10px;
  background-color: #5f40db;
  color: white;
  border: none;
  cursor: pointer;
}

.asset-card ul li button:hover {
  background-color: #4831a4;
}
</style>

