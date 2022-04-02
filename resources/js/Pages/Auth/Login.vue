<template>
    <div class="login-box">

        <img src="/img/logo.svg" alt="">

        <h3>gympoint</h3>

        <form @submit.prevent="submit">
            <BaseInput
                label="seu e-mail"
                type="email"
                placeholder="exemplo@exemplo.com"
                v-model="login.email"
                :class="{error: errors.email}"
                :error="errors.email"
            />
            <BaseInput
                label="sua senha"
                type="password"
                placeholder="******"
                v-model="login.password"
                :class="{error: errors.password}"
                :error="errors.password"
            />
            <button class="btn btn-full btn-primary" type="submit" :disabled="loading">
                <Loader v-if="loading" width="20px" height="20px"/>
                Entrar no sistema
            </button>
            <Link href="/register" class="link-btn link-btn-primary link-btn-center">
                Cadastrar-se
            </Link>
        </form>
    </div>
</template>

<script>
import Publico from '../../Layouts/Publico'
import BaseInput from "../../components/forms/BaseInput";
import {Link} from "@inertiajs/inertia-vue3";

export default {
    name: "login",
    components: {BaseInput, Link},
    layout: Publico,
    props: {
        errors: Object,
        exception: String
    },
    data() {
        return {
            login: {
                email: '',
                password: ''
            },
            loading: false
        }
    },
    methods: {
        submit() {
            this.$inertia.post("/login", this.login, {
                onStart: () => {
                    this.loading = true
                },
                onFinish: () => {
                    this.loading = false
                },
                onError: () => {
                    this.loading = false
                },
                preserveState: true
            });
        }
    }
}
</script>

<style scoped>
.login-box {
    width: 360px;
    background: #fff;
    border-radius: 4px;
    padding: 50px 30px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
}

.login-box img {
    display: block;
    margin: 0 auto;
    margin-bottom: 20px;
}

.login-box h3 {
    text-align: center;
    font-weight: 700;
    font-size: 30px;
    line-height: 35px;
    text-transform: uppercase;
    color: var(--cor-principal);
    margin-bottom: 30px;
}



</style>
