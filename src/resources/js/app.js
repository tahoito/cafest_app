import './bootstrap';
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

    hasTag(t){ return this.tags.includes(t) },
    toggleTag(t){
      this.tags = this.hasTag(t) ? this.tags.filter(x=>x!==t) : [...this.tags, t]
    },

    hasMood(m){ return this.moods.includes(m) },
    toggleMood(m){
      this.moods = this.hasMood(m) ? this.moods.filter(x=>x!==m) : [...this.moods, m]
    },

    // ratingMin は単一選択にしとくのが自然
    toggleRating(min){
      this.ratingMin = (this.ratingMin === min) ? null : min
    },
    isRatingOn(min){
      return this.ratingMin === min
    },
  })
})

Alpine.start()
