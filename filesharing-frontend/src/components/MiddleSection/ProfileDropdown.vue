<template>
    <div class="dropdown" v-if="isVisible">
      <div class="dropdown-content">
        <div class="profile-header">
          <img src="../../assets/ShaneSujal.png" alt="Profile Picture" class="profile-picture">
          <div class="profile-info">
            <p class="username">Sujal</p>
            <p class="email">sujal@gmail.com</p>
          </div>
        </div>
        <hr class="divider">
        <button class="logout-button" @click="logout">Logout</button>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'ProfileDropdown',
    props: ['isVisible'],
    methods: {
      logout() {
        // Logout logic here
        alert("Logging out...");
      }
    }
  }
  </script>
  
  <style scoped>
  .dropdown {
    position: absolute;
    right: 10px;
    top: 50px;
    background-color: white;
    border: 1px solid #ccc;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    border-radius: 5px;
  }
  
  .dropdown-content {
    padding: 10px;
  }
  
  .profile-header {
    display: flex;
    align-items: center;
  }
  
  .profile-picture {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 10px;
  }
  
  .profile-info {
    display: flex;
    flex-direction: column;
  }
  
  .username {
    font-weight: bold;
    margin: 0;
  }
  
  .email {
    margin: 0;
  }
  
  .divider {
    margin: 10px 0;
    border: 0;
    border-top: 1px solid #ccc;
  }
  
  .logout-button {
    width: 100%;
    padding: 10px;
    background-color: #f44336;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  
  .logout-button:hover {
    background-color: #d32f2f;
  }
  </style>
  