<template>
    <div class="login-box">

        <img src="/img/logo.svg" alt="">

        <h3>gympoint</h3>

        <form @submit.prevent="submit">
            <BaseInput
                type="text"
                name="login"
                label="Login"
                v-model="form.email"
                :error="form.errors.email"
                :class="{error: form.errors.email}"
            />
            <BaseInput
                label="sua senha"
                type="password"
                name="senha"
                v-model="form.password"
                :error="form.errors.password"
                :class="{error: form.errors.password}"
                autocomplete="autocomplete"
            />

            <button class="btn btn-primary btn-full" :disabled="loading">
                <Loader v-if="loading" height="20px" width="20px"/>
                Entrar
            </button>
            <Link href="/register" class="link-btn link-btn-primary link-btn-center">
                Cadastrar-se
            </Link>
        </form>
    </div>
</template>

<script>
import Publico from '../../Layouts/Publico'
import BaseInput from "../../components/base/form/BaseInput";
import {Link, useForm} from "@inertiajs/inertia-vue3";

export default {
    name: "login",
    components: {BaseInput, Link},
    layout: Publico,
    setup() {
        const form = useForm({
            email: '',
            password: ''
        });
        return {form};
    },
    props: {
        errors: Object,
        exception: String
    },
    data() {
        return {
            loading: false
        }
    },
    methods: {
        submit() {
            this.form.post("/login", {
                onStart: () => {
                    this.loading = true;
                },
                onSuccess: () => {
                    this.loading = false
                },
                onError: () => {
                    this.loading = false;
                }
            });
        }
    },
    created() {
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
