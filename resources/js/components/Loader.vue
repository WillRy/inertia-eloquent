<template>
    <div id="loader" class="loader">
        <svg id="L9" :style="{height: height, width: width, padding: padding}" enable-background="new 0 0 0 0" version="1.1" viewBox="0 0 100 100"
             x="0px"
             xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
             y="0px"
        >
        <path :class="{'fill-principal': corPrincipal}"
              :style="{fill: fill && !corPrincipal}"
              d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50"
              fill="#fff"
        >
          <animateTransform
              attributeName="transform"
              attributeType="XML"
              dur="1s"
              from="0 50 50"
              repeatCount="indefinite"
              to="360 50 50"
              type="rotate"/>
      </path>
    </svg>
    </div>
</template>

<script>
export default {
    name: "Loader",
    props: {
        height: {
            default: '20px'
        },
        width: {
            default: '20px'
        },
        fill: {
            default: '#fff'
        },
        padding: {
            default: '0px'
        },
        corPrincipal: {
            type: Boolean,
            default: false
        }
    }
}
</script>

<style scoped>
svg * {
    width: 100%;
    height: 100%;
}

.loader {
    display: flex;
    align-items: center;
    justify-content: center;
}

.fill-principal {
    fill: var(--cor-principal);
}
</style>
