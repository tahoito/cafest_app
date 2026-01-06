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

    hasTag(t){ const id = Number(t) 
      return this.tags.includes(id)},
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
})

Alpine.start()
