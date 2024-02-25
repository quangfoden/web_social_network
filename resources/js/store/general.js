import { defineStore } from "pinia";
export const useGeneralStore = defineStore('general', {
    state: () => ({
        isPostOverlay: false,
        isEditPostOverlay:false,
        isCropperModal: false,
        isFileDisplay: []
    }),
    persist: true,
})