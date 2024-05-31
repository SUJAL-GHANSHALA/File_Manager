<template>
  <div class="parent-div">
    <div class="left-sec"></div>
    <div class="right-sec">
      <div class="form-section">
        <div class="top-img">
          <img src="https://t4.ftcdn.net/jpg/00/36/71/79/360_F_36717966_NE47aSRFkCRCnPhzTyH7mXv13stY6aKJ.webp" alt="">
        </div>
        <div class="form-details">
          <form @submit.prevent="handleSubmit">
            <div class="form-group">
              <label for="name">Name</label>
              <input
                type="text"
                id="name"
                v-model="form.name"
                placeholder="Enter your name"
              />
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input
                type="email"
                id="email"
                v-model="form.email"
                placeholder="Enter your email"
              />
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input
                type="password"
                id="password"
                v-model="form.password"
                placeholder="Enter your password"
              />
            </div>
            <div class="form-group">
              <label for="renamePassword">Rename Password</label>
              <input
                type="password"
                id="renamePassword"
                v-model="form.renamePassword"
                placeholder="Enter your password again"
              />
            </div>
            <button type="submit" class="button">Sign Up</button>
          </form>
          <div class="additional-options">
            <p>Already have an account? <router-link to="/login">login</router-link></p>
            <p>or</p>
          </div>
          <button type="button" class="login-with-google-btn" @click="handleGoogleLogin">
            Continue with Google
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SignUp',
  data() {
    return {
      form: {
        name: '',
        email: '',
        password: '',
        renamePassword: '',
      },
    };
  },
  methods: {
    async handleSubmit() {
      console.log(this.form.name);
    console.log(this.form.email);
    console.log(this.form.password)
      if (this.form.password !== this.form.renamePassword) {
        alert('Passwords do not match!');
        return;
      }

      try {
        const response = await fetch('http://127.0.0.1:8000/api/register', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
          body: JSON.stringify({
            name: this.form.name,
            email: this.form.email,
            password: this.form.password
          })
        });

        if (!response.ok) {
          throw new Error('Network response was not ok');
        }

        const data = await response.json();

        const expiryDate = new Date();
        expiryDate.setDate(expiryDate.getDate() + 7); // Expires in 7 days
        document.cookie = `token=${data.token}; expires=${expiryDate.toUTCString()}; path=/`;
        
        alert("success");
        console.log('User registered:', data);
        this.$router.push({ name: 'Home' });
      } catch (error) {
        
        console.error('Registration error:', error);
      }
    },
    handleGoogleLogin() {
      
      // window.location.href = '/auth/google';
    }
  },
  mounted() {
      console.warn("sujal"); //in this function will try to user to redirect page after he successfully sign up
      // let user = localStorage.getItem('user-info'); //this code is by you tuber, just for understanding purpose,like when user logged, he should not visit the signup/signin again
      // if(user){
      //   this.$router.push({name:'Home'})
      // }
  }
};
</script>

<!-- CSS code here -->
<style scoped>
* {
  margin: 0;
  padding: 0;
}

.parent-div {
  display: flex;
  align-items: center;
  width: 100%;
  height: 100vh;
}
.form-section {
  max-width: 400px;
  margin: 0 auto;
}
.top-img{
  margin-bottom: 20px;
  position: relative;
  left: 80px;
}
.top-img img{
    height: 100px;
}
.form-details {
  background-color: #f9f9f9;
  border-radius: 5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

form {
  width: 90%;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
}

.form-group input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.left-sec {
  height: 100vh;
  width: 60%;
  background-image: url("https://maxicus.com/wp-content/uploads/2021/01/Top-9-benefits-of-cloud-storage-monitoring-solution.png");
  background-size: cover;
  background-position: center;
}

.right-sec {
  width: 50%;
  border-radius: 5px;
}

.button {
  display: block;
  width: 105%;
  padding: 10px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.button:hover {
  background-color: #155aa5;
}
.additional-options {
  margin-top: 20px;
  text-align: center;
}

.additional-options p {
  margin-bottom: 10px;
}

.additional-options a {
  color: #007bff;
  text-decoration: none;
}

.additional-options a:hover {
  text-decoration: underline;
}

.google-button {
  display: inline-block;
  padding: 10px 20px;
  background-color: #db4437;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.login-with-google-btn {
  transition: background-color .3s, box-shadow .3s;
  padding: 12px 16px 12px 42px;
  border: none;
  border-radius: 3px;
  color: #757575;
  font-size: 14px;
  font-weight: 500;
  font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen,Ubuntu,Cantarell,"Fira Sans","Droid Sans","Helvetica Neue",sans-serif;
  background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTgiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNMTcuNiA5LjJsLS4xLTEuOEg5djMuNGg0LjhDMTMuNiAxMiAxMyAxMyAxMiAxMy42djIuMmgzYTguOCA4LjggMCAwIDAgMi42LTYuNnoiIGZpbGw9IiM0Mjg1RjQiIGZpbGwtcnVsZT0ibm9uemVybyIvPjxwYXRoIGQ9Ik05IDE4YzIuNCAwIDQuNS0uOCA2LTIuMmwtMy0yLjJhNS40IDUuNCAwIDAgMS04LTIuOUgxVjEzYTkgOSAwIDAgMCA4IDV6IiBmaWxsPSIjMzRBODUzIiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNNCAxMC43YTUuNCA1LjQgMCAwIDEgMC0zLjRWNUgxYTkgOSAwIDAgMCAwIDhsMy0yLjN6IiBmaWxsPSIjRkJCQzA1IiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNOSAzLjZjMS4zIDAgMi41LjQgMy40IDEuM0wxNSAyLjNBOSA5IDAgMCAwIDEgNWwzIDIuNGE1LjQgNS40IDAgMCAxIDUtMy43eiIgZmlsbD0iI0VBNDMzNSIgZmlsbC1ydWxlPSJub256ZXJvIi8+PHBhdGggZD0iTTAgMGgxOHYxOEgweiIvPjwvZz48L3N2Zz4=);
  /* background-position:  80%; */
  background-color: white;
  background-repeat: no-repeat;
  background-position: 12px 11px;
  width: 50%;
  cursor: pointer;
  position: relative;
  left: 90px;
}
</style>