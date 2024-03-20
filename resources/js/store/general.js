import { defineStore } from "pinia";
export const useGeneralStore = defineStore('general', {
    state: () => ({
        isPostOverlay: false,
        isEditPostOverlay:false,
        isCropperModal: false,
        myProfile: false,
        isFileDisplay: []
    }),
    persist: true,
})