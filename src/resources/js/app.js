import './bootstrap'
import Alpine from 'alpinejs'

window.Alpine = Alpine

document.addEventListener('alpine:init', () => {
  Alpine.store('search', {
    activeModal: null,

    area: '',
    budget: '',
    time: '',
    ratingMin: null,

    tags: [],
    moods: [],
    selectedRatings: [],

    hasTag(t){
      const id = Number(t)
      return this.tags.includes(id)
    },
    toggleTag(t){
      const id = Number(t)
      this.tags = this.hasTag(id)
        ? this.tags.filter(x => x !== id)
        : [...this.tags, id]
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

  // ✅ slider は alpine:init の中で登録するのが安全
  Alpine.data('slider', (total) => ({
    active: 0,
    total,
    timer: null,

    showModal: false,
    modalImage: null,

    start() {
      this.stop()
      this.timer = setInterval(() => {
        this.active = (this.active + 1) % this.total
      }, 3000)
    },

    stop() {
      if (this.timer) clearInterval(this.timer)
      this.timer = null
    },

    go(i) {
      this.active = i
      this.start()
    },

    open(src) {
      this.modalImage = src
      this.showModal = true
      this.stop()
    },

    close() {
      this.showModal = false
      this.modalImage = null
      this.start()
    },
  }))
})

Alpine.start()
