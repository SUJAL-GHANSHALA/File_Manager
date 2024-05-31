import { createStore } from 'vuex';

const store = createStore({
  state: {
    showCreateFolderPopup: false,
    showProfileDropdown: false, 
    refreshFolderList: false 
  },
  mutations: {
    toggleCreateFolderPopup(state) {
      state.showCreateFolderPopup = !state.showCreateFolderPopup;
    },
    hideCreateFolderPopup(state) {
      state.showCreateFolderPopup = false;
    },
    toggleProfileDropdown(state) { 
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
    toggleProfileDropdown({ commit }) { 
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
