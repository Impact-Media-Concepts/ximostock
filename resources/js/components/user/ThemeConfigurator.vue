<template>
  <div class="theme-configurator">
    <div class="content">
      <section>
        <span>Thema instellingen</span>
        <div class="form-group">
          <label for="primary_color">Primaire kleur:</label>
          <color-picker 
            id="primary_color"
            v-model="primary_color">
          </color-picker>
        </div>
        <div class="form-group">
          <label for="secondary_color">Secondaire kleur:</label>
          <color-picker 
            id="secondary_color"
            v-model="secondary_color">
          </color-picker>
        </div>
        <div class="form-group">
          <label for="background_color">Achtergrond kleur:</label>
          <color-picker 
            id="background_color" 
            v-model="background_color">
          </color-picker>
        </div>
        <div class="form-group">
          <label for="text_color">Tekst kleur:</label>
          <color-picker 
            id="text_color" 
            v-model="text_color">
          </color-picker>
        </div>
        <div class="form-group">
          <button @click="save" class="button">Opslaan</button>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
import { defineComponent, inject } from 'vue';
import axios, { Axios } from 'axios';
import ColorPicker from '../components/ColorPicker.vue'; // Import the ColorPicker component
import '../../../scss/user/ThemeConfigurator.scss';

export default defineComponent({
  components: {
    ColorPicker
  },
  props: {
    user: {
      type: Object,
      default: () => ({}),
    },
  },
  data() {
    return {
      primary_color: this.user.primary_color || '#000000',
      secondary_color: this.user.secondary_color || '#000000',
      background_color: this.user.background_color || '#ffffff',
      text_color: this.user.text_color || '#000000',
    };
  },
  methods: {
    save() {

      const newColors = {
        primary_color: this.primary_color,
        secondary_color: this.secondary_color,
        background_color: this.background_color,
        text_color: this.text_color,
      };

      // Set CSS variables
      document.documentElement.style.setProperty('--primary', this.primary_color);
      document.documentElement.style.setProperty('--secondary', this.secondary_color);
      document.documentElement.style.setProperty('--background', this.background_color);
      document.documentElement.style.setProperty('--light', this.background_color);
      document.documentElement.style.setProperty('--text', this.text_color);

      axios.patch(route('user.updateThemeById', this.user.id), newColors)
        .then(() => {
          console.log('saved');
        })
        .catch((error) => {
          console.error(error);
        });


      console.log('save');
    },
  },
  setup() {
    const route = inject('route'); // Injecting route helper
    return {
      route,
    };
  },
});
</script>
