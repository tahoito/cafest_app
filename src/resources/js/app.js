import './bootstrap'
import Alpine from 'alpinejs'

window.Alpine = Alpine

document.addEventListener('alpine:init', () => {
  Alpine.store('search', {
    activeModal: null,
    keyword: '',     
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


  Alpine.store('favModal', {
    openStoreId: null,
    createStoreId: null,

    openList(storeId){
      this.openStoreId = Number(storeId)
    },
    closeList(){
      this.openStoreId = null
    },
    openCreate(storeId){
      this.createStoreId = Number(storeId)
    },
    closeCreate(){
      this.createStoreId = null
    },
  })

  Alpine.store('reviewModal', {
    open: false,
    loading: false,
    data: null,
    error: '',

    close() {
      this.open = false
      this.loading = false
      this.data = null
      this.error = ''
    },
  })

  Alpine.data('favoriteFolderModal', (storeId, initialOn = false) => ({
    storeId: Number(storeId),
    on: initialOn,

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
        Alpine.store('favModal').openList(this.storeId)
      } else if (data.status === 'removed') {
        this.on = false
        Alpine.store('favModal').closeList()
      }
    },
  }))

  Alpine.data('favoriteFoldersUI', (storeId, defaultThumb) => ({
    storeId: Number(storeId),
    defaultThumb,
    folders: [],
    selectedFolderIds: [],

    async init() {
      window.addEventListener('favorite-folder-created', (e) => {
        if (!e?.detail) return
        this.folders = [e.detail, ...this.folders]
      })

      const res = await fetch(`/user/stores/${this.storeId}/favorite/folders`, {
        headers: { 'Accept': 'application/json' },
      })
      const data = await res.json()
      this.folders = data.folders ?? []
      this.selectedFolderIds = data.selected_folder_ids ?? []
    },

    toggleFolder(folderId) {
      const id = Number(folderId)
      if (this.selectedFolderIds.includes(id)) {
        this.selectedFolderIds = this.selectedFolderIds.filter(x => x !== id)
      } else {
        this.selectedFolderIds = [...this.selectedFolderIds, id]
      }
    },
  }))

  // ---- create folder modal ----
  Alpine.data('favoriteFolderCreateUI', (storeId) => ({
    storeId: Number(storeId),
    name: '',
    saving: false,
    error: '',

    async save() {
      this.error = ''
      const n = this.name.trim()
      if (!n) return

      this.saving = true
      try {
        const res = await fetch(`/user/favorite-folders`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
          },
          body: JSON.stringify({ name: n }),
        })

        if (!res.ok) {
          const txt = await res.text()
          throw new Error(txt || '作成に失敗しました')
        }

        const folder = await res.json()
        window.dispatchEvent(new CustomEvent('favorite-folder-created', { detail: folder }))
        this.name = ''

        // 作成後：作成モーダル閉じてリストに戻る（このstoreに）
        Alpine.store('favModal').closeCreate(this.storeId)

      } catch (e) {
        this.error = e?.message ?? 'エラーが発生しました'
      } finally {
        this.saving = false
      }
    },
  }))
})

Alpine.start()
