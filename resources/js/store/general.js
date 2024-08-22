import { defineStore } from "pinia";
export const useGeneralStore = defineStore('general', {
    state: () => ({
        isPostOverlay: false,
        isEditPostOverlay: false,
        isCropperModal: false,
        myProfile: false,
        isChatBoxOverLay: false,
        selecFriendId: null,
        filesMessUpload: null,
        isFileDisplay: [],
        isLoadingChatBox: false,
        showClickBack: false
    }),
    persist: true,
})