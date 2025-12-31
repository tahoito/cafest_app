import './bootstrap'
import Alpine from 'alpinejs'

window.Alpine = Alpine

document.addEventListener('alpine:init', () => {
  Alpine.store('search', {
    activeModal: null,

    area: '',
    budget: '',
    time: '',

    // レビュー：複数選択したいならこれ
    selectedRatings: [],

    tags: [],
    moods: [],

    // --- tags ---
    hasTag(t) { return this.tags.includes(t) },
    toggleTag(t) {
      this.tags = this.hasTag(t)
        ? this.tags.filter(x => x !== t)
        : [...this.tags, t]
    },

    // --- moods ---
    hasMood(m) { return this.moods.includes(m) },
    toggleMood(m) {
      this.moods = this.hasMood(m)
        ? this.moods.filter(x => x !== m)
        : [...this.moods, m]
    },

    // --- ratings ---
    toggleRating(n) {
      const i = this.selectedRatings.indexOf(n)
      if (i === -1) this.selectedRatings.push(n)
      else this.selectedRatings.splice(i, 1)
      this.selectedRatings.sort((a, b) => a - b)
    },
    isRatingOn(n) {
      return this.selectedRatings.includes(n)
    },
    clearRatings() {
      this.selectedRatings = []
    },
  })
})

Alpine.start()
