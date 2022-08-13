<template>
    <div class="register-box">

        <img src="/img/logo.svg" alt="">

        <h3>gympoint</h3>

        <form @submit.prevent="submit">
            <BaseInput
                label="Empresa"
                type="text"
                placeholder="Nome da sua empresa"
                v-model="register.company"
                :class="{error: errors.company}"
                :error="errors.company"
            />

            <BaseInput
                label="E-mail"
                type="email"
                placeholder="exemplo@exemplo.com"
                v-model="register.email"
                :class="{error: errors.email}"
                :error="errors.email"
            />

            <BaseInput
                label="Nome"
                type="text"
                placeholder="Seu nome"
                v-model="register.name"
                :class="{error: errors.name}"
                :error="errors.name"
            />

            <BaseInput
                label="sua senha"
                type="password"
                placeholder="******"
                v-model="register.password"
                :class="{error: errors.password}"
                :error="errors.password"
            />
            <button class="btn btn-full btn-primary" type="submit" :disabled="loading">
                <Loader v-if="loading" width="20px" height="20px"/>
                Realizar cadastro
            </button>
            <Link href="/login" class="link-btn link-btn-primary link-btn-center">
                Já tenho conta, quero realizar login
            </Link>
        </form>
    </div>
</template>

<script>
import Publico from '../../Layouts/Publico'
import BaseInput from "../../components/base/form/BaseInput";
import {Link} from "@inertiajs/inertia-vue3";

export default {
    name: "Register",
    components: {BaseInput, Link},
    layout: Publico,
    props: {
        errors: Object,
        exception: String
    },
    data() {
        return {
            register: {
                email: '',
                password: ''
            },
            loading: false
        }
    },
    methods: {
        submit() {
            this.$inertia.post("/register", this.register, {
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
.register-box {
    width: 360px;
    background: #fff;
    border-radius: 4px;
    padding: 50px 30px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
}

.register-box img {
    display: block;
    margin: 0 auto;
    margin-bottom: 20px;
}

.register-box h3 {
    text-align: center;
    font-weight: 700;
    font-size: 30px;
    line-height: 35px;
    text-transform: uppercase;
    color: var(--cor-principal);
    margin-bottom: 30px;
}


</style>
