import {createStore} from "vuex";
import axios from "axios";

export default createStore({
    state: {
        alunos_reload: {},
        alunos_id_edicao: null,
        alunos_id_exclusao: null,
        planos_reload: {},
        planos_id_edicao: null,
        planos_id_exclusao: null,
        planos_filtro_pesquisa: '',
        planos_filtro_pagina: 1,
        matriculas_reload: {},
        matriculas_id_edicao: null,
        matriculas_id_exclusao: null,
    },
    actions: {
        returnPlanos(context){
            let search = context.state.planos_filtro_pesquisa || '';
            let page = context.state.planos_filtro_pagina || 1;
            /** retorna promise**/
            return axios.get("/dashboard/plans/list", {
                params: {
                    ...(search ? {search: search} : {}),
                    ...(page ? {page: page} : {}),
                }
            });
        }
    },
    mutations: {
        SET_ALUNOS_RELOAD(state, obj) {
            state.alunos_reload = obj;
        },
        SET_ALUNOS_ID_EDICAO(state, id) {
            state.alunos_id_edicao = id;
        },
        SET_ALUNOS_ID_EXCLUSAO(state, id) {
            state.alunos_id_exclusao = id;
        },
        SET_PLANOS_RELOAD(state, obj) {
            state.planos_reload = obj;
        },
        SET_PLANOS_ID_EDICAO(state, id) {
            state.planos_id_edicao = id;
        },
        SET_PLANOS_ID_EXCLUSAO(state, id) {
            state.planos_id_exclusao = id;
        },
        SET_PLANOS_FILTRO_PESQUISA(state, pesquisa) {
            state.planos_filtro_pesquisa = pesquisa;
        },
        SET_PLANOS_FILTRO_PAGINA(state, pagina) {
            state.planos_filtro_pagina = pagina;
        },
        SET_MATRICULAS_RELOAD(state, obj) {
            state.matriculas_reload = obj;
        },
        SET_MATRICULAS_ID_EDICAO(state, id) {
            state.matriculas_id_edicao = id;
        },
        SET_MATRICULAS_ID_EXCLUSAO(state, id) {
            state.matriculas_id_exclusao = id;
        },
    }
})
