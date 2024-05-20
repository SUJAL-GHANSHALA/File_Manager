import { createStore } from 'vuex';

const store = createStore({
  state: {
    showCreateFolderPopup: false,
    showProfileDropdown: false // Add state for profile dropdown
  },
  mutations: {
    toggleCreateFolderPopup(state) {
      state.showCreateFolderPopup = !state.showCreateFolderPopup;
    },
    hideCreateFolderPopup(state) {
      state.showCreateFolderPopup = false;
    },
    toggleProfileDropdown(state) { // Mutation to toggle profile dropdown
      state.showProfileDropdown = !state.showProfileDropdown;
    }
  },
  actions: {
    toggleCreateFolderPopup({ commit }) {
      commit('toggleCreateFolderPopup');
    },
    hideCreateFolderPopup({ commit }) {
      commit('hideCreateFolderPopup');
    },
    toggleProfileDropdown({ commit }) { // Action to toggle profile dropdown
      commit('toggleProfileDropdown');
    }
  }
});

export default store;
