<template>
    <div class="header" v-click-away="fecharDropdown">
        <div class="header-logo">
            <img src="/img/logo.svg" alt="">
            <h1>Gympoint</h1>
        </div>
        <div class="header-hamburguer" @click="dropdown = !dropdown">
            <img src="/img/hamburguer.png" alt="">
        </div>
        <div class="header-nav" :class="{open: dropdown}">
            <ul>
                <li>
                    <Link href="/dashboard/students" :class="{active: $page.url.endsWith('/dashboard/students')}">
                        alunos (Padrão InertiaJS)
                    </Link>
                </li>
                <li>
                    <Link href="/dashboard/plans" :class="{active: $page.url.endsWith('/dashboard/plans')}">
                        planos (Padrão VueJS)
                    </Link>
                </li>
                <li>
                    <Link href="/dashboard/subscriptions"
                          :class="{active: $page.url.endsWith('/dashboard/subscriptions')}">
                        matriculas (Padrão Vue sem router)
                    </Link>
                </li>
                <li>
                    <Link href="/dashboard/subscriptions/url"
                          :class="{active: $page.url.endsWith('/dashboard/subscriptions/url')}">
                        matriculas (Padrão Vue COM router)
                    </Link>
                </li>
            </ul>
            <div class="header-user">
                    <span class="user">
                        {{ $page.props.auth.name }}
                    </span>
                <Link href="/logout" class="logout">
                    sair do sistema
                </Link>
            </div>
        </div>
    </div>
</template>

<script>
import {Link} from "@inertiajs/inertia-vue3";

export default {
    name: "Header",
    components: {Link},
    data() {
        return {
            dropdown: false
        }
    },
    methods: {
        fecharDropdown() {
            this.dropdown = false;
        }
    },
    created() {
        window.addEventListener("resize", this.fecharDropdown);
    },
    beforeUnmount() {
        window.removeEventListener("scroll", this.aumentarPagina);
    },
}
</script>

<style scoped>
.header {
    display: flex;
    align-items: center;
    min-height: 64px;
    padding: 0px 30px;
    background: #fff;
    position: fixed;
    top: 0;
    width: 100%;
    flex-wrap: wrap;
    z-index: 999;
}

.header-logo {
    display: grid;
    grid-template-columns: 45px 1fr 3px;
    gap: 12px;

    align-items: center;
    margin-right: 20px;
}

.header-logo::after {
    content: '';
    display: block;
    height: 32px;
    width: 2px;
    background: #DDDDDD;
}

.header-logo img {
    display: block;
    max-width: 100%;
}

.header-logo h1 {
    font-style: normal;
    font-weight: 700;
    font-size: 15px;
    line-height: 18px;
    color: var(--cor-principal);
    text-transform: uppercase;
    padding-right: 30px;
}

.header-nav {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.header-nav ul {
    display: flex;
    align-items: center;
}

.header-nav ul li a {
    color: #999999;
    font-weight: 700;
    font-size: 15px;
    text-transform: uppercase;
    padding: 10px;
}

.header-nav ul li a:hover,
.header-nav ul li a.active {
    color: #444444;
}

.header-user .name {
    color: #666666;
    font-weight: 700;
    font-size: 14px;
    line-height: 16px;
    margin-bottom: 4px;
    display: block;
}

.header-user .logout {
    display: block;
    font-style: normal;
    font-weight: 400;
    font-size: 14px;
    line-height: 16px;
    text-align: right;

    color: var(--cor-principal);
}

.header-user .logout:hover {
    opacity: 0.6;
}

.header-hamburguer {
    display: none;
}

@media all and (max-width: 900px) {
    .header {
        display: grid;
        grid-template-columns: 1fr 32px;
        gap: 10px;
    }

    .header-logo {
        height: 64px;
        margin: 0;
        grid-row: 1;
    }

    .header-hamburguer {
        width: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        grid-row: 1;
        cursor: pointer;
    }

    .header-logo::after {
        display: none
    }

    .header-nav {
        display: none;
        border-top: 1px solid #ddd;
        padding: 10px 0;
        width: 100%;
        flex-shrink: 0;
        grid-row: 2;
        grid-column: span 2;
    }

    .header-nav.open {
        display: block;
    }

    .header-nav ul {
        width: 100%;
        display: block;
    }

    .header-nav ul li a {
        display: block;
    }

    .header-user .logout {
        text-align: left;
        padding: 5px 0;
    }

    .header-user {
        border-top: 1px solid #ddd;
        padding: 10px 10px;
    }
}
</style>
