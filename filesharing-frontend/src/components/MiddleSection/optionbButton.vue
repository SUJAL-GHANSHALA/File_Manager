<template>
  <div :style="{ top: `${position.top}px`, left: `${position.left}px` }" class="options-menu">
    <button @click="previewAsset">Preview</button>
    <button @click="downloadAsset">Download</button>
    <button @click="renameAsset">Rename</button>
    <button @click="moveToTrash">Move to Trash</button>
    <button @click="viewDetails">Details</button>
    <button @click="closeMenu">Close</button>
  </div>
</template>

<script>
import emitter from '../../eventbus.js';

export default {
  name: 'OptionButton',
  props: {
    position: {
      type: Object,
      required: true
    }
  },
  methods: {
    closeMenu() {
      this.$emit('close');
    },
    previewAsset() {
      // Implement preview functionality
      console.log('Preview asset');
    },
    downloadAsset() {
      // Implement download functionality
      console.log('Download asset');
    },
    renameAsset() {
      // Implement rename functionality
      console.log('Rename asset');
    },
    moveToTrash() {
      // Implement move to trash functionality
      console.log('Move asset to trash');
    },
    viewDetails() {
      emitter.emit('showDetails'); // Emit event to show details
      this.closeMenu(); // Close the options menu
    }
  }
};
</script>

<style scoped>
.options-menu {
  position: absolute;
  background-color: white;
  border: 1px solid #ddd;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  padding: 10px;
  z-index: 1000;
}

.options-menu button {
  display: block;
  width: 100%;
  margin-bottom: 5px;
  padding: 5px;
  border: none;
  background: none;
  text-align: left;
  cursor: pointer;
}

.options-menu button:hover {
  background-color: #f2f2f2;
}
</style>


<!-- <template>
    <div :style="cardStyle" class="option-card" @click.self="close">
      <ul>
        <li @click="preview">Preview</li>
        <li @click="download">Download</li>
        <li @click="rename">Rename</li>
        <li @click="addToStarred">Add to Starred</li>
        <li @click="addTags">Add Tags</li>
        <li @click="move">Move</li>
        <li @click="moveToTrash">Move to Trash</li>
        <li @click="details">Details</li>
      </ul>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      position: {
        type: Object,
        required: true,
      },
    },
    computed: {
      cardStyle() {
        return {
          top: `${this.position.top}px`,
          left: `${this.position.left}px`,
          position: 'absolute',
        };
      },
    },
    methods: {
      close() {
        this.$emit('close');
      },
      preview() {
        // Implement preview functionality
        this.close();
      },
      download() {
        // Implement download functionality
        this.close();
      },
      rename() {
        // Implement rename functionality
        this.close();
      },
      addToStarred() {
        // Implement add to starred functionality
        this.close();
      },
      addTags() {
        // Implement add tags functionality
        this.close();
      },
      move() {
        // Implement move functionality
        this.close();
      },
      moveToTrash() {
        // Implement move to trash functionality
        this.close();
      },
      details() {
        // Implement details functionality
        this.close();
      },
    },
  };
  </script>
  --
  <style scoped>
  .option-card {
    background-color: white;
    border: 1px solid #ccc;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 4px;
    width: 180px;
    z-index: 10;
  }
  
  .option-card ul {
    list-style: none;
    margin: 0;
    padding: 0;
  }
  
  .option-card li {
    padding: 10px 15px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
  }
  
  .option-card li:last-child {
    border-bottom: none;
  }
  
  .option-card li:hover {
    background-color: #f5f5f5;
  }
  </style> -->
  