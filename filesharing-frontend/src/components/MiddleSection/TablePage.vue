<template>
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
</style>
