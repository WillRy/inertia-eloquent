<template>
    <div class="header">
        <div class="header-logo">
            <img src="/img/logo.svg" alt="">
            <h1>Gympoint</h1>
        </div>
        <div class="header-nav">
            <ul>
                <li>
                    <Link href="/dashboard/students">
                        alunos
                    </Link>
                </li>
                <li>
                    <Link href="/dashboard/plans">
                        planos
                    </Link>
                </li>
                <li>
                    <Link href="/dashboard/subscriptions">
                        matriculas
                    </Link>
                </li>
            </ul>
        </div>
        <div class="header-user">
            <span class="user">
                {{$page.props.auth.name}}
            </span>
            <Link href="/logout" class="logout">
                sair do sistema
            </Link>
        </div>
    </div>
</template>

<script>
import {Link} from "@inertiajs/inertia-vue3";

export default {
    name: "Header",
    components: {Link},
}
</script>

<style scoped>
.header {
    display: flex;
    align-items: center;
    height: 64px;
    padding: 0px 30px;
    background: #fff;
    position: fixed;
    top: 0;
    width: 100%;
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

.header-logo img{
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
}

.header-nav ul{
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

.header-nav ul li a:hover {
    color: #444444;
}

.header-user .name{
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

@media all and (max-width: 620px) {
    .header-logo {
        display: none;
    }
}
</style>
