import { createStore } from 'vuex';

const store = createStore({
  state: {
    showCreateFolderPopup: false,
    showProfileDropdown: false, // Add state for profile dropdown
    refreshFolderList: false // Add state to trigger folder list refresh
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
    },
    SET_REFRESH_FOLDER_LIST(state, value) {
      state.refreshFolderList = value;
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
    },
    refreshFolderList({ commit }) {
      commit('SET_REFRESH_FOLDER_LIST', true);
    },
    clearRefreshFolderList({ commit }) {
      commit('SET_REFRESH_FOLDER_LIST', false);
    }
  }
});

export default store;
