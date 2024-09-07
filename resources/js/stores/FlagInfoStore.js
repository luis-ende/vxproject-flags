import { defineStore } from "pinia";

export const useFlagInfoStore = defineStore('flagInfoStore', {
    state: () => ({
        flagInfoNotesDialogShow: false,
    })
})
