import './bootstrap'
import Alpine from 'alpinejs'

window.Alpine = Alpine

document.addEventListener('alpine:init', () => {
  // ---- store (search) ----
  Alpine.store('search', {
    activeModal: null,
    area: '',
    budget: '',
    time: '',
    ratingMin: null,
    tags: [],
    moods: [],
    selectedRatings: [],

    hasTag(t){ const id = Number(t); return this.tags.includes(id) },
    toggleTag(t){
      const id = Number(t)
      this.tags = this.hasTag(id) ? this.tags.filter(x => x !== id) : [...this.tags, id]
    },

    hasMood(m){ return this.moods.includes(m) },
    toggleMood(m){
      this.moods = this.hasMood(m) ? this.moods.filter(x=>x!==m) : [...this.moods, m]
    },

    toggleRating(n){
      const i = this.selectedRatings.indexOf(n)
      if (i === -1) this.selectedRatings.push(n)
      else this.selectedRatings.splice(i, 1)
      this.selectedRatings.sort((a,b)=>a-b)
    },
    isRatingOn(n){ return this.selectedRatings.includes(n) },
    clearRatings(){ this.selectedRatings = [] },
  })

  // ---- card: heart toggle + open modal ----
  Alpine.data('favoriteFolderModal', (storeId, initialOn = false) => ({
    storeId,
    on: initialOn,
    favoriteOpen: false,

    async toggleAndOpen() {
      const res = await fetch(`/user/stores/${this.storeId}/favorite`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json',
        },
      })

      const data = await res.json()

      if (data.status === 'added') {
        this.on = true
        this.favoriteOpen = true
      } else if (data.status === 'removed') {
        this.on = false
        this.favoriteOpen = false
      }
    },
  }))

  // ---- modal: folder list ----
  Alpine.data('favoriteFoldersUI', (storeId, defaultThumb) => ({
    storeId,
    defaultThumb,
    folders: [],
    selectedFolderIds: [],

    async init() {
      const res = await fetch(`/user/stores/${this.storeId}/favorite/folders`, {
        headers: { 'Accept': 'application/json' },
      })
      const data = await res.json()
      this.folders = data.folders ?? []
      this.selectedFolderIds = data.selected_folder_ids ?? []
    },

    toggleFolder(folderId) {
      if (this.selectedFolderIds.includes(folderId)) {
        this.selectedFolderIds = this.selectedFolderIds.filter(id => id !== folderId)
      } else {
        this.selectedFolderIds = [...this.selectedFolderIds, folderId]
      }
    },
  }))
})

Alpine.start()
