<template>
  <div class="sidebar">
    <ul>
      <li v-for="(folder, index) in folders" :key="index">
        <a href="#" class="folder" @click="toggleSubfolders(folder)">
          {{ folder.name }}
        </a>
        <ul v-if="folder.open">
          <li v-for="(subfolder, subIndex) in folder.subfolders" :key="subIndex">
            <a href="#" class="folder" @click="toggleSubfolders(subfolder)">
              {{ subfolder.name }}
            </a>
            <ul v-if="subfolder.open">
              <li v-for="(file, fileIndex) in subfolder.files" :key="fileIndex">
                <span class="file">{{ file }}</span>
              </li>
            </ul>
          </li>
        </ul>
      </li>
    </ul>
    <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>
  </div>
  <button @click="createFolder">Create Folder</button>
</template>

<script>
export default {
  data() {
    return {
      folders: [],
      maxLevels: 6,
      errorMessage: ''
    };
  },
  methods: {
    createFolder() {
      if (this.countTotalFolders() >= this.maxLevels) {
        this.errorMessage = "Cannot create more than 6 levels of nested folders.";
        return;
      }
      this.folders.push({ name: `Folder ${this.folders.length + 1}`, open: false, subfolders: [] });
      this.errorMessage = '';
    },
    toggleSubfolders(folder) {
      if (folder.subfolders.length === 0) {
        if (this.countTotalFolders() >= this.maxLevels) {
          this.errorMessage = "Cannot create more than 6 levels of nested folders.";
          return;
        }
        folder.open = true;
        for (let i = 1; i <= 3; i++) {
          folder.subfolders.push({ name: `Subfolder ${i}`, open: false, files: [] });
        }
      } else {
        folder.open = !folder.open;
      }
      this.errorMessage = '';
    },
    countTotalFolders() {
      let count = this.folders.length;
      this.folders.forEach(folder => {
        count += folder.subfolders.length;
        folder.subfolders.forEach(subfolder => {
          if (subfolder.files.length > 0) count++;
        });
      });
      return count;
    }
  }
};
</script>

<style>
.sidebar {
  width: 200px;
  background-color: #f0f0f0;
  padding: 10px;
}
ul {
  list-style-type: none;
  padding: 0;
  margin: 0;
}
.folder {
  display: block;
  padding: 5px 10px;
  text-decoration: none;
  color: #333;
}
.folder:hover {
  background-color: #ddd;
}
.file {
  display: block;
  padding: 5px 20px;
}
.error-message {
  color: red;
  margin-top: 10px;
}
</style>
