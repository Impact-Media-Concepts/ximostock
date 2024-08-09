<template>
  <div class="color-picker">
    <input 
      type="text" 
      :value="color"
      @focus="showColorPicker = true"
      @input="updateColor($event.target.value)"
      :style="{ 
        'background-color': color, 
        'color': getContrastingColor(color, '#FFFFFF') 
      }"
    >
    <input 
      v-if="showColorPicker" 
      type="color" 
      v-model="color"
      @input="updateColor($event.target.value)"
      @blur="hideColorPicker"
      class="hidden-color-picker"
    >
  </div>
</template>

<script>
export default {
  props: {
    modelValue: {
      type: String,
      default: '#ffffff'
    }
  },
  data() {
    return {
      color: this.modelValue,
      showColorPicker: false
    };
  },
  watch: {
    modelValue(newValue) {
      this.color = newValue;
    }
  },
  methods: {
    updateColor(newColor) {
      this.color = newColor;
      this.$emit('update:modelValue', newColor);
    },
    hideColorPicker() {
      this.showColorPicker = false;
    },
    getContrastingColor(color, defaultColor) {
      if (!color) return defaultColor;
      if (color.length === 7) {
        color = color.substring(1);
      }
      
      const r = parseInt(color.substring(0, 2), 16);
      const g = parseInt(color.substring(2, 4), 16);
      const b = parseInt(color.substring(4, 6), 16);
      
      const brightness = (r * 299 + g * 587 + b * 114) / 1000;
      
      return brightness > 150 ? '#717171' : '#FFFFFF';
    }
  }
};
</script>

<style scoped>
.color-picker {
  position: relative;
}

.hidden-color-picker {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
}
</style>
